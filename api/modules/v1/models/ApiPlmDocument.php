<?php


namespace app\api\modules\v1\models;


use app\models\BaseModel;
use app\modules\plm\models\PlmDocItemDefects;
use app\modules\plm\models\PlmDocItemEquipments;
use app\modules\plm\models\PlmDocItemProducts;
use app\modules\plm\models\PlmDocumentItems;
use app\modules\plm\models\PlmDocuments;
use app\modules\plm\models\PlmProcessingTime;
use app\modules\plm\models\PlmStops;
use Yii;

class ApiPlmDocument extends PlmDocuments
{

    /**
     * @param $post
     * @return array
     */
    public static function saveData($post): array 
    {
        $document = $post['document'];
        $documentItems = $post['document_items'];

        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        try {
            $doc = new PlmDocuments();
            if (!empty($document['id']))
                $doc = PlmDocuments::findOne($document['id']);
            $doc->setAttributes([
                'reg_date' => date("Y-m-d", strtotime($document['reg_date'])),
                'hr_department_id' => $document['hr_department_id'],
                'organisation_id' => $document['organisation_id'],
                'shift_id' => $document['shift_id'],
                'add_info' => $document['add_info'],
                'status_id' => BaseModel::STATUS_ACTIVE
            ]);
            if (!$doc->save())
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Doc not saved'),
                    'errors' => $doc->getErrors(),
                    'line' => __LINE__
                ];

            if ($response['status']){
                foreach ($documentItems as $item) {
                    $plannedStopped = $item['planned_stopped'];
                    $unplannedStopped = $item['unplanned_stopped'];

                    /**
                     * Planned stop
                     */
                    if (!empty($plannedStopped)) {
                        $planStop = new PlmStops();
                        if ($item['planned_stop_id'])
                            $planStop = PlmStops::findOne($item['planned_stop_id']);
                        $planStop->setAttributes([
                            'reason_id' => $plannedStopped['reason_id'],
                            'begin_date' => date('Y-m-d H:i', strtotime($plannedStopped['begin_date'])),
                            'end_time' => date('Y-m-d H:i', strtotime($plannedStopped['end_time'])),
                            'add_info' => $plannedStopped['add_info'],
                            'status_id' => BaseModel::STATUS_ACTIVE,
                            'stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP
                        ]);
                        if (!$planStop->save()){
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $planStop->getErrors(),
                                'message' => Yii::t('app', 'Planned stop not saved'),
                            ];
                            break;
                        }
                    }

                    /**
                     * Un planned stop
                     */
                    if (!empty($unplannedStopped) && $response['status']) {
                        $unPlanStop = new PlmStops();
                        if ($item['unplanned_stop_id'])
                            $unPlanStop = PlmStops::findOne($item['unplanned_stop_id']);
                        $unPlanStop->setAttributes([
                            'reason_id' => $unplannedStopped['reason_id'],
                            'begin_date' => date('Y-m-d H:i', strtotime($unplannedStopped['begin_date'])),
                            'end_time' => date('Y-m-d H:i', strtotime($unplannedStopped['end_time'])),
                            'bypass' => $unplannedStopped['bypass'],
                            'add_info' => $unplannedStopped['add_info'],
                            'status_id' => BaseModel::STATUS_ACTIVE,
                            'stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP
                        ]);
                        if (!$unPlanStop->save()){
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $unPlanStop->getErrors(),
                                'message' => Yii::t('app', 'Unplanned stop not saved'),
                            ];
                            break;
                        }
                    }
                    
                    if ($response['status'] && $item['start_work'] && $item['end_work']) {
                        $processing = new PlmProcessingTime();
                        if ($item['processing_time_id'])
                            $processing = PlmProcessingTime::findOne($item['processing_time_id']);
                        $processing->setAttributes([
                            'begin_date' => date("Y-m-d H:i", strtotime($item['start_work'])),
                            'end_date' => date("Y-m-d H:i", strtotime($item['end_work'])),
                            'status_id' => BaseModel::STATUS_ACTIVE
                        ]);
                        if (!$processing->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $processing->getErrors(),
                                'message' => Yii::t('app', 'Processing time not saved'),
                            ];
                            break;
                        }
                    }

                    if ($response['status']){
                        $docItem = new PlmDocumentItems();
                        if ($item['id'])
                            $docItem = PlmDocumentItems::findOne($item['id']);
                        $docItem->setAttributes([
                            'document_id' => $doc->id,
                            'planned_stop_id' => $planStop->id ?? "",
                            'unplanned_stop_id' => $unPlanStop->id ?? "",
                            'processing_time_id' => $processing->id ?? "",
                            'equipment_group_id' => $item['equipmentGroup']['value'] ?? "",
                        ]);
                        if (!$docItem->save()) {
                            $response = [
                                'status' => false,
                                'line' => __LINE__,
                                'errors' => $docItem->getErrors(),
                                'message' => Yii::t('app', 'Doc item not saved'),
                            ];
                            break;
                        }

                        $products = $item['products'];
                        PlmDocItemProducts::deleteAll(['document_item_id' => $docItem->id]);
                        if (!empty($products)) {
                            foreach ($products as $product) {
                                $newProductItem = new PlmDocItemProducts();
                                $newProductItem->setAttributes([
                                    'document_item_id' => $docItem->id,
                                    'product_id' => $product['product_id'],
                                    'product_lifecycle_id' => $product['product_lifecycle_id'],
                                    'qty' => $product['qty'],
                                    'fact_qty' => $product['fact_qty']
                                ]);
                                if (!$newProductItem->save()) {
                                    $response = [
                                        'status' => false,
                                        'line' => __LINE__,
                                        'errors' => $newProductItem->getErrors(),
                                        'message' => Yii::t('app', 'Doc item product not saved'),
                                    ];
                                    break 2;
                                }

                                $repaired = $product['repaired'] ?? [];
                                foreach ($repaired as $repair) {
                                    if ($repair['count']) {
                                        $newDef = new PlmDocItemDefects();
                                        $newDef->setAttributes([
                                            'type' => BaseModel::DEFECT_REPAIRED,
                                            'doc_item_id' => $docItem->id,
                                            'defect_id' => $repair['value'],
                                            'qty' => $repair['count'],
                                            'status_id' => BaseModel::STATUS_ACTIVE,
                                            'doc_item_product_id' => $newProductItem->id
                                        ]);
                                        if (!$newDef->save()) {
                                            $response = [
                                                'status' => false,
                                                'line' => __LINE__,
                                                'errors' => $newDef->getErrors(),
                                                'message' => Yii::t('app', 'Doc item repaired not saved'),
                                            ];
                                            break 3;
                                        }
                                    }
                                }

                                $scrapped = $product['scrapped'] ?? [];
                                foreach ($scrapped as $scrap) {
                                    if ($scrap['count']) {
                                        $newDef = new PlmDocItemDefects();
                                        $newDef->setAttributes([
                                            'type' => BaseModel::DEFECT_SCRAPPED,
                                            'doc_item_id' => $docItem->id,
                                            'defect_id' => $scrap['value'],
                                            'qty' => $scrap['count'],
                                            'status_id' => BaseModel::STATUS_ACTIVE,
                                            'doc_item_product_id' => $newProductItem->id
                                        ]);
                                        if (!$newDef->save()) {
                                            $response = [
                                                'status' => false,
                                                'line' => __LINE__,
                                                'errors' => $newDef->getErrors(),
                                                'message' => Yii::t('app', 'Doc item scrapped not saved'),
                                            ];
                                            break 3;
                                        }
                                    }
                                }

                            }
                        }

                        $equipments = $item['equipments'];
                        if (!$equipments){
                            PlmDocItemEquipments::deleteAll(['document_item_id' => $docItem->id]);
                            foreach ($equipments as $equipment){
                                $docItemEquipment = new PlmDocItemEquipments([
                                    'document_item_id' => $docItem->id,
                                    'equipment_id' => $equipment['value'],
                                ]);
                                if (!$docItemEquipment->save()){
                                    $response = [
                                        'status' => false,
                                        'line' => __LINE__,
                                        'errors' => $docItemEquipment->getErrors(),
                                        'message' => Yii::t('app', 'Doc item equipment not saved'),
                                    ];
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }
            
            if ($response['status'])
                $transaction->commit();
            else
                $transaction->rollBack();

        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'errors' => $e->getMessage(),
                'line' => __LINE__,
            ];
        }
        return $response;
    }
}