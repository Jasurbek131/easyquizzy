<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "references_product_lifecycle_rel_equipment".
 *
 * @property int $id
 * @property int $product_lifecycle_id
 * @property int $equipment_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property Equipments $equipments
 * @property ProductLifecycle $productLifecycle
 */
class ReferencesProductLifecycleRelEquipment extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'references_product_lifecycle_rel_equipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_lifecycle_id', 'equipment_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['product_lifecycle_id', 'equipment_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Equipments::class, 'targetAttribute' => ['equipment_id' => 'id']],
            [['product_lifecycle_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductLifecycle::class, 'targetAttribute' => ['product_lifecycle_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_lifecycle_id' => Yii::t('app', 'Product Lifecycle ID'),
            'equipment_id' => Yii::t('app', 'Equipment ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasOne(Equipments::class, ['id' => 'equipment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductLifecycle()
    {
        return $this->hasOne(ProductLifecycle::class, ['id' => 'product_lifecycle_id']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
            $this->status_id = \app\models\BaseModel::STATUS_ACTIVE;

        return parent::beforeSave($insert);
    }

    /**
     * @param $id
     * @return array
     */
    public static function getEquipmentsByProduct($id): array
    {
        $equipments = self::find()
            ->where([
                "product_lifecycle_id" => $id
            ])
            ->asArray()
            ->all();
        if (!empty($equipments))
            return ArrayHelper::getColumn($equipments, "equipment_id");
        return [];
    }

    /**
     * @param $product_id
     * @param $equipments
     * @return array
     */
    public static function checkExists($id, $product_id, $equipments = []): array
    {
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];

        $query = self::find()
            ->alias('rplre')
            ->select([
                "CONCAT_WS('_',MAX(pl.product_id)::varchar(255), array_agg(rplre.equipment_id)) as key"
            ])
            ->leftJoin(['pl' => "product_lifecycle"], "rplre.product_lifecycle_id = pl.id")
            ->where([
                "pl.product_id" => $product_id,
            ])
            ->andFilterWhere(['!=', 'rplre.product_lifecycle_id', $id])
            ->groupBy([
                "pl.id"
            ])
            ->asArray()
            ->all();

        if (!empty($query))
            $query = ArrayHelper::index($query, "key");
        
        $key = $product_id;
        if (!empty($equipments))
            $key .= "_{".join(",", $equipments)."}";

        if (array_key_exists($key, $query) !== false)
            $response = [
                'status' => false,
                'message' => Yii::t('app', 'This item has'),
            ];

        return $response;
    }
}
