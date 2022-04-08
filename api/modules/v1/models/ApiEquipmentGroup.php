<?php


namespace app\api\modules\v1\models;


use app\models\BaseModel;
use app\modules\references\models\EquipmentGroup;
use app\modules\references\models\EquipmentGroupRelationEquipment;
use app\modules\references\models\ProductLifecycle;
use app\modules\references\models\Products;
use app\modules\references\models\ReferencesProductGroup;
use app\modules\references\models\ReferencesProductGroupRelProduct;
use Yii;
use yii\helpers\ArrayHelper;

class ApiEquipmentGroup extends EquipmentGroup implements ApiEquipmentGroupInterface
{

    /**
     * @param $post
     * @return array
     */
    public static function saveApiProduct($post): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            if ($post['id'])
                $product = Products::findOne(['id' => $post['id']]);
            else
                $product = new Products();

            $product->setAttributes([
                'name' => $post['name'],
                'part_number' => $post['part_number'],
                'status_id' => $post['status_id'],
            ]);

            if (!$product->save())
                $response = [
                    'status' => false,
                    'errors' => $product->getErrors(),
                    'message' => Yii::t('app', 'Product not saved'),
                ];

            if ($response['status']) {
                $response['item'] = [
                    'value' => $product->id,
                    'label' => $product->name. " (".$product->part_number.")",
                ];
                $transaction->commit();
            } else
                $transaction->rollBack();

        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }

    /**
     * @param $post
     * @return array
     */
    public static function saveApiEquipmentGroup($post): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            if (empty($post))
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Data not send'),
                ];

            if ($response['status']){
                foreach ($post as $item){

                    if (empty($item['cycles']) || empty($item['equipments'])){
                        $response = [
                            'status' => false,
                            'message' => Yii::t('app', 'Data empty'),
                        ];
                        break;
                    }
                    //TODO optimallashtirish kerak.
                    /**
                     * Equipment_group yaratish
                     * O'zim ham zo'rga yozib oldim tushunmoqchi bo'lganlarga sabr
                     */
                    $relGroupCreate = false;
                    if (isset($item["equipment_group_id"]) && !empty($item["equipment_group_id"])){
                        EquipmentGroupRelationEquipment::deleteAll(["equipment_group_id" => $item["equipment_group_id"]]);
                        $existsEquipmentGroup = EquipmentGroup::existsGroup($item["equipments"]);
                        if ($existsEquipmentGroup["status"]){
                            $equipmentGroup =  EquipmentGroup::findOne(['id' => $existsEquipmentGroup['equipment_group_id']]);
                        }else{
                            $equipmentGroup = EquipmentGroup::findOne(['id' => $item['equipment_group_id']]);
                            $relGroupCreate = true;
                        }
                    }else{
                        $existsEquipmentGroup = EquipmentGroup::existsGroup($item["equipments"]);
                        if ($existsEquipmentGroup["status"]){
                            $equipmentGroup =  EquipmentGroup::findOne(['id' => $existsEquipmentGroup['equipment_group_id']]);
                            $existsEquipmentGroup["equipment_type_id"] = $equipmentGroup->equipment_type_id;
                        }else{
                            $equipmentGroup = new EquipmentGroup();
                            $relGroupCreate = true;
                        }
                    }
                    $existsEquipmentGroup["equipment_type_id"] = $equipmentGroup->equipment_type_id;
                    $equipmentGroup->setAttributes([
                        'name' => $item['name'],
                        'value' => $item['value'] ?? '',
                        'equipment_type_id' => $item['equipment_type_id'],
                        'status_id' => BaseModel::STATUS_ACTIVE,
                    ]);
                    if (!$equipmentGroup->save()){
                        $response = [
                            'status' => false,
                            'errors' => $equipmentGroup->getErrors(),
                            'message' => Yii::t('app', 'Equipment group not saved'),
                        ];
                        break;
                    }
                    if ($response['status'] && isset($item["equipment_group_id"]) && !empty($item["equipment_group_id"])){
                        if ($existsEquipmentGroup["equipment_type_id"] != $equipmentGroup->equipment_type_id){
                            ProductLifecycle::deleteAll(["equipment_group_id" => $item["equipment_group_id"]]);
                            $relGroupCreate = true;
                        }
                    }

                    /**
                     * Equipment group rel equipment yaratildi
                     */
                    if ($relGroupCreate){
                        foreach ($item['equipments'] as $i) {
                            $relationEquipment = new EquipmentGroupRelationEquipment([
                                'equipment_group_id' => $equipmentGroup->id,
                                'equipment_id' => $i['value'],
                                'status_id' => BaseModel::STATUS_ACTIVE,
                            ]);
                            if (!$relationEquipment->save()) {
                                $response = [
                                    'status' => false,
                                    'errors' => $relationEquipment->getErrors(),
                                    'message' => Yii::t('app', 'Equipment group rel equipment not saved'),
                                ];
                                break 2;
                            }
                        }
                    }

                    $response['lifecycle_ids'] = [];
                    if (!empty($item["cycles"])){
                        foreach ($item["cycles"] as $cycle){
                            /**
                             * Product group yaratilyapti
                             */
                            $existsProductGroup = ReferencesProductGroup::existsProductGroup($cycle["products"]);
                            if($existsProductGroup["status"] == false){
                                $productGroup = new ReferencesProductGroup([
                                    'status_id' => BaseModel::STATUS_ACTIVE
                                ]);
                                if (!$productGroup->save()){
                                    $response = [
                                        'status' => false,
                                        'errors' => $productGroup->getErrors(),
                                        'message' => Yii::t('app', 'References product group not saved'),
                                    ];
                                    break 2;
                                }else{
                                    $cycle['product_group_id'] = $productGroup->id;
                                }
                            }else{
                                $cycle['product_group_id'] = $existsProductGroup["product_group_id"];
                            }
                            /**
                             * Product group rel product yaratilyapti
                             */
                            if ($existsProductGroup["status"] === false) {
                                foreach ($cycle["products"] as $product) {
                                    $relProduct = new ReferencesProductGroupRelProduct([
                                        'product_group_id' => $cycle['product_group_id'],
                                        'product_id' => $product['value'],
                                    ]);
                                    if (!$relProduct->save()){
                                        $response = [
                                            'status' => false,
                                            'errors' => $relProduct->getErrors(),
                                            'message' => Yii::t('app', 'References product group rel product not saved'),
                                        ];
                                        break 3;
                                    }
                                }
                            }
                            
                            /**
                             * Mahsulot life cycle yaratish
                             */
                            if (empty($cycle['lifecycle_id']))
                                $productLifecycle = new ProductLifecycle();
                            else
                                $productLifecycle = ProductLifecycle::findOne(['id' => $cycle['lifecycle_id']]);
                            $productLifecycle->setAttributes([
                                'product_group_id' => $cycle['product_group_id'],
                                'equipment_group_id' =>  $equipmentGroup->id,
                                'lifecycle' => $cycle['lifecycle'] ?? "",
                                'bypass' => $cycle['bypass'] ?? "",
                                'status_id' => BaseModel::STATUS_ACTIVE,
                            ]);
                            if (!$productLifecycle->save()){
                                $response = [
                                    'status' => false,
                                    'errors' => $productLifecycle->getErrors(),
                                    'message' => Yii::t('app', 'Product lifecycle not saved'),
                                ];
                                break 2;
                            }
                               
                            $response["lifecycle_ids"][] = $productLifecycle->id;
                        }
                    }

                    $response["equipment_group_id"] = $equipmentGroup->id;
                }
            }
            if ($response['status']) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getEquipmentGroupFormData($id):array
    {
        $data = EquipmentGroup::find()
            ->alias("eg")
            ->select([
                "eg.id as equipment_group_id",
                "eg.id",
                "eg.name",
                "eg.value",
                "eg.equipment_type_id",
            ])
            ->with([
                'equipments' => function($e){
                    $e->from(['egr' => 'equipment_group_relation_equipment'])->select([
                        'egr.equipment_id',
                        'egr.equipment_group_id',
                        'e.name as label',
                        'e.id as value'
                    ])->leftJoin('equipments e', 'egr.equipment_id = e.id');
                },
                "cycles" => function ($q) {
                    $q->from(["product_lifecycle" => "product_lifecycle"])
                        ->select([
                            "id as lifecycle_id",
                            "equipment_group_id",
                            "product_group_id",
                            "bypass",
                            "lifecycle",
                        ])
                        ->with([
                            "productGroup.referencesProductGroupRelProducts" => function ($pgr) {
                                $pgr->from(["references_product_group_rel_product" => "references_product_group_rel_product"])
                                ->select([
                                    "product_group_id",
                                    "product_id",
                                ])
                                ->with([
                                    "products" => function ($e) {
                                        $e->from(['p' => 'products'])->select([
                                            "CONCAT(name, ' (', part_number, ')') as label",
                                            'id as value',
                                            'id',
                                        ]);
                                    },
                                ]);
                            },
                        ]);
                }
            ])
            ->where([
                "eg.id" => $id,
            ])
            ->asArray()
            ->one();

        if (!empty($data['cycles'])){
            foreach ($data['cycles'] as $cycle => $item){
                if ($item['productGroup'] && $item["productGroup"]["referencesProductGroupRelProducts"]){
                    foreach ($item["productGroup"]["referencesProductGroupRelProducts"] as $product){
                        $data['cycles'][$cycle]["products"][] = $product["products"] ?? [];
                    }

                }
            }
        }

        return $data ?? [];
    }

    /**
     * @param $post
     * @return array
     */
    public static function deleteApiEquipmentGroup($post):array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        try{
            if(empty($post) || empty($post['equipment_group_id']))
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Data empty'),
                ];
            if ($response['status'] && !empty($post["cycles"])){
                $cycle_ids = ArrayHelper::getColumn($post["cycles"], "lifecycle_id");
                if ($cycle_ids)
                    ProductLifecycle::deleteAll(['id' => $cycle_ids]);

                if ($post["equipment_group_id"]){
                    EquipmentGroupRelationEquipment::deleteAll(['equipment_group_id' => $post["equipment_group_id"]]);
                    $equipmentGroup = EquipmentGroup::findOne(['id' => $post["equipment_group_id"]]);
                    if ($equipmentGroup){
                        $equipmentGroup->status_id = BaseModel::STATUS_INACTIVE;
                        if ($equipmentGroup->save()){
                            $response = [
                                'status' => false,
                                'errors' => $equipmentGroup->getErrors(),
                                'message' => Yii::t('app', 'Equipment group not deleted'),
                            ];
                        }
                    }
                }
            }

            if($response['status']){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        } catch(\Exception $e){
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return  $response;
    }

    /**
     * @param $post
     * @return array
     */
    public static function deleteApiProduct($post):array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        try{
            if(empty($post))
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Data empty'),
                ];

            if ($response['status'] && $post["lifecycle_id"]){
                 ProductLifecycle::deleteAll(['id' => $post["lifecycle_id"]]);
            }

            if($response['status']){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        } catch(\Exception $e){
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return  $response;
    }
}