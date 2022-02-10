<?php

namespace app\modules\references\models;

use app\models\BaseModel;
use app\modules\admin\models\AdminLogs;
use Yii;

/**
 * This is the model class for table "product_lifecycle".
 *
 * @property int $id
 * @property int $product_id
 * @property int $equipment_group_id
 * @property int $lifecycle
 * @property int $time_type_id
 * @property int $status_id
 * @property array $equipments
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property EquipmentGroup $equipmentGroup
 * @property ReferencesProductLifecycleRelEquipment $referencesProductLifecycleRelEquipments
 * @property Products $products
 * @property TimeTypesList $timeTypesList
 */
class ProductLifecycle extends BaseModel
{
    /**
     * @var
     * Mahsulotlar ro'yxati uchun
     */
    public $equipments;

    /**
     * @var bool
     * Mahsulot lifecycle malumotlari yangilanayotgan bo'lsa: true bo'ladi
     */
    public $isUpdate = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_lifecycle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'lifecycle', 'time_type_id', 'status_id', 'equipments'], 'required'],
            [['product_id', 'equipment_group_id', 'lifecycle', 'time_type_id', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['equipment_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentGroup::class, 'targetAttribute' => ['equipment_group_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['time_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimeTypesList::class, 'targetAttribute' => ['time_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Products'),
            'equipment_group_id' => Yii::t('app', 'Equipment Group'),
            'equipments' => Yii::t('app', 'Equipments'),
            'lifecycle' => Yii::t('app', 'Lifecycle'),
            'time_type_id' => Yii::t('app', 'Time Types List'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentGroup()
    {
        return $this->hasOne(EquipmentGroup::class, ['id' => 'equipment_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTypesList()
    {
        return $this->hasOne(TimeTypesList::class, ['id' => 'time_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferencesProductLifecycleRelEquipments()
    {
        return $this->hasMany(ReferencesProductLifecycleRelEquipment::class, ['product_lifecycle_id' => 'id']);
    }

    /**
     * @param array $oldAttiributes
     * @return array
     */
    public function saveProductLifecycle($oldAttiributes = []): array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        try{

            if (!$this->save())
                $response = [
                    'status' => false,
                    'message' => 'Product lifecycle not saved',
                    'errors' => $this->getErrors()
                ];

            if ($response['status']){

                if ($this->isUpdate){
                    ReferencesProductLifecycleRelEquipment::deleteAll(["product_lifecycle_id" => $this->id]);
                    if (
                        $oldAttiributes["product_id"] != $this->attributes["product_id"] ||
                        $oldAttiributes["lifecycle"] != $this->attributes["lifecycle"] ||
                        $oldAttiributes["time_type_id"] != $this->attributes["time_type_id"]
                    ){
                        $response = AdminLogs::saveLog(
                            $oldAttiributes,
                            $this->attributes,
                            self::tableName(),
                            self::class
                        );
                    }
                }

                if ($response['status']){
                    $response = ReferencesProductLifecycleRelEquipment::checkExists($this->id, $this->product_id, $this->equipments);
                }

                if ($response['status']){
                    foreach ($this->equipments as $equipment){
                        $rel = new ReferencesProductLifecycleRelEquipment([
                            'product_lifecycle_id' => $this->id,
                            'equipment_id' => $equipment,
                        ]);
                        if (!$rel->save()){
                            $response = [
                                'status' => false,
                                'message' => 'Product rel equipment not saved',
                                'errors' => $rel->getErrors()
                            ];
                            break;
                        }
                    }
                }
            }

            if($response['status'])
                $transaction->commit();
            else
                $transaction->rollBack();

        } catch(\Exception $e){
            $transaction->rollBack();
            $response = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }

    public static function getProductLifecycleList($one = false, $id = null) {
        $list = ProductLifecycle::find()->alias('pl')->select([
            'p.id as value', "p.name as label", "MAX(pl.lifecycle) as lifecycle"
        ])->innerJoin('products p', 'pl.product_id = p.id')
            ->where(['pl.status_id' => BaseModel::STATUS_ACTIVE])
            ->groupBy('p.id')
            ->asArray();
        if ($one) {
            return $list->andWhere(['pl.id' => $id])->one();
        }
        return $list->all();
    }
}
