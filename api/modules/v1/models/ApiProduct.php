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

class ApiProduct extends Products
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
                $product = self::findOne(['id' => $post['id']]);
            else
                $product = new self();

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
    public static function saveApiProductEquipment($post): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            if (empty($post['products']) || empty($post['item']['equipments']))
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Data empty'),
                ];
            /**
             * Product group yaratilyapti
             */
            if ($response['status'] && empty($post['product_group_id'])){
                $productGroup = new ReferencesProductGroup([
                    'status_id' => BaseModel::STATUS_ACTIVE
                ]);
                if (!$productGroup->save()){
                    $response = [
                        'status' => false,
                        'errors' => $productGroup->getErrors(),
                        'message' => Yii::t('app', 'References product group not saved'),
                    ];
                }else{
                    $post['product_group_id'] = $productGroup->id;
                }
            }

            /**
             * Product group rel producut yaratilyapti
             */
            if ($response['status']) {
                ReferencesProductGroupRelProduct::deleteAll(['product_group_id' => $post['product_group_id']]);
                foreach ($post["products"] as $product) {
                    $relProduct = new ReferencesProductGroupRelProduct([
                        'product_group_id' => $post['product_group_id'],
                        'product_id' => $product['value'],
                    ]);
                    if (!$relProduct->save()){
                        $response = [
                            'status' => false,
                            'errors' => $relProduct->getErrors(),
                            'message' => Yii::t('app', 'References product group rel product not saved'),
                        ];
                        break;
                    }
                }
            }

            /**
             * Equipment_group yaratish
             */
            if ($response['status'] && empty($post['item']['equipment_group_id'])) {
                $equipmentGroup = new EquipmentGroup([
                    'name' => 'Generation some text',
                    'equipments_group_type_id' => $post['item']['equipments_group_type_id'],
                    'status_id' => BaseModel::STATUS_ACTIVE,
                ]);
                if (!$equipmentGroup->save()){
                    $response = [
                        'status' => false,
                        'errors' => $equipmentGroup->getErrors(),
                        'message' => Yii::t('app', 'Equipment group not saved'),
                    ];
                }else{
                    $post['item']['equipment_group_id'] = $equipmentGroup->id;
                }
            }
            /**
             * Equipment group rel equipment yaratildi
             */
            if ($response['status']){
                EquipmentGroupRelationEquipment::deleteAll(['equipment_group_id' => $post['item']['equipment_group_id']]);
                foreach ($post['item']['equipments'] as $item) {
                    $relationEquipment = new EquipmentGroupRelationEquipment([
                        'equipment_group_id' => $post['item']['equipment_group_id'],
                        'equipment_id' => $item['value'],
                        'status_id' => BaseModel::STATUS_ACTIVE,
                    ]);
                    if (!$relationEquipment->save()) {
                        $response = [
                            'status' => false,
                            'errors' => $relationEquipment->getErrors(),
                            'message' => Yii::t('app', 'Equipment group rel equipment not saved'),
                        ];
                        break;
                    }
                }
            }

            /**
             * Mahsulot life cycle yaratish
             */
            if ($response['status']){
                if (empty($post['item']['product_lifecycle_id']))
                    $productLifecycle = new ProductLifecycle();
                else
                    $productLifecycle = ProductLifecycle::findOne(['id' => $post['item']['product_lifecycle_id']]);

                $productLifecycle->setAttributes([
                    'product_group_id' => $post['product_group_id'],
                    'equipment_group_id' =>  $post['item']['equipment_group_id'],
                    'lifecycle' => $post['item']['lifecycle'] ?? "",
                    'bypass' => $post["item"]['bypass'] ?? "",
                    'status_id' => BaseModel::STATUS_ACTIVE,
                ]);
                if (!$productLifecycle->save())
                    $response = [
                        'status' => false,
                        'errors' => $productLifecycle->getErrors(),
                        'message' => Yii::t('app', 'Product lifecycle not saved'),
                    ];
            }

            if ($response['status']) {
                $response['equipment_group_id'] =  $post['item']['equipment_group_id'];
                $response['product_group_id'] = $post['product_group_id'];
                $response['product_lifecycle_id'] = $productLifecycle->id;
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
    public static function getProductFormData($id)
    {
        $data = ReferencesProductGroup::find()
            ->alias("rpg")
            ->select([
                "rpg.id",
            ])
            ->with(["referencesProductGroupRelProducts" => function($q){
                $q->from(["references_product_group_rel_product" => "references_product_group_rel_product"])
                    ->select([
                        "product_group_id",
                        "product_id",
                    ])
                    ->with([
                        "products" => function ($e) {
                            $e->from(['p' => 'products'])->select([
                                'name as label',
                                'id as value',
                                'id',
                            ]);
                        },
                    ]);
            }])
            ->with([
                "productLifecycles" => function ($q) {
                    $q->from(["product_lifecycle" => "product_lifecycle"])
                        ->select([
                            "id as product_lifecycle_id",
                            "equipment_group_id",
                            "product_group_id",
                            "bypass",
                            "lifecycle",
                        ])
                        ->with([
                            "equipmentGroup.equipments" => function ($e) {
                                $e->from(['egr' => 'equipment_group_relation_equipment'])->select([
                                    'egr.equipment_id',
                                    'egr.equipment_group_id',
                                    'e.name as label',
                                    'e.id as value'
                                ])->leftJoin('equipments e', 'egr.equipment_id = e.id');
                            },
                        ]);
                }
            ])
            ->where([
                "rpg.id" => $id,
            ])
            ->asArray()
            ->one();

        $response['product_group_id'] = $id;
        $response["products"] = [];
        $response["product_lifecycle"] = [];

        if (!empty($data['referencesProductGroupRelProducts'])){
            foreach ($data['referencesProductGroupRelProducts'] as $item){
                $response["products"][] = [
                    'value' => $item["products"]['value'] ?? '',
                    'label' => $item["products"]['label'] ?? '',
                ];
            }
        }

        if (!empty($data['productLifecycles'])){
            foreach ($data['productLifecycles'] as $item){
                $response["product_lifecycle"][] = [
                    'lifecycle' => $item["lifecycle"] ?? '',
                    'bypass' => $item["bypass"] ?? '',
                    'equipments_group_type_id' => $item["equipmentGroup"]["equipments_group_type_id"] ?? [],
                    'equipment_group_id' => $item["equipment_group_id"] ?? '',
                    'product_lifecycle_id' => $item["product_lifecycle_id"] ?? '',
                    'equipments' => $item["equipmentGroup"]["equipments"] ?? [],
                ];
            }
        }
        return $response;
    }

    /**
     * @param $post
     * @return array
     */
    public static function deleteApiProductEquipment($post):array 
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        try{

            if(empty($post) || empty($post['product_lifecycle_id']))
                $response = [
                    'status' => false,
                    'message' => Yii::t('app', 'Data empty'),
                ];

            if ($response['status']){
                $lifecycle = ProductLifecycle::findOne(['id' => $post['product_lifecycle_id']]);
                if (!empty($lifecycle)){
                    if ($lifecycle->delete() === false)
                        $response = [
                            'status' => false,
                            'message' => Yii::t('app', 'Not deleted'),
                        ];
                    if ($lifecycle->equipment_group_id){
                        EquipmentGroupRelationEquipment::deleteAll(['equipment_group_id' => $lifecycle->equipment_group_id]);
                        EquipmentGroup::deleteAll(['id' => $lifecycle->equipment_group_id]);
                    }
                }else{
                    $response = [
                        'status' => false,
                        'message' => Yii::t('app', 'Product lifecycle not found'),
                    ];
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
}