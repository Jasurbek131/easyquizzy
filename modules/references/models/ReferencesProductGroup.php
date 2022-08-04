<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "references_product_group".
 *
 * @property int $id
 * @property int $status_id
 * @property string $name
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property ProductLifecycle[] $productLifecycles
 * @property ReferencesProductGroupRelProduct[] $referencesProductGroupRelProducts
 */
class ReferencesProductGroup extends BaseModel
{
    public $name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'references_product_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
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
    public function getReferencesProductGroupRelProducts()
    {
        return $this->hasMany(ReferencesProductGroupRelProduct::class, ['product_group_id' => 'id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(ReferencesProductGroupRelProduct::class, ['product_group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductLifecycles()
    {
        return $this->hasMany(ProductLifecycle::class, ['product_group_id' => 'id']);
    }

    /**
     * @param $products
     * @return array
     * Mahulotlardan tashkil topgan guruh borligini tekshiradi
     * Agar bor bor bo'lsa uni id qaytarib beradi [status => true, product_group_id => 1]
     * Aks holda [status => false]
     */
    public static function existsProductGroup($products):array
    {
        $rel = ReferencesProductGroupRelProduct::find()
            ->select([
                "array_agg(product_id) as product_ids",
                "MAX(product_group_id) as product_group_id"
            ])
            ->groupBy([
                "product_group_id"
            ])
            ->asArray()
            ->all();

        if (!empty($rel))
            $rel = ArrayHelper::index($rel, 'product_ids');

        $key = "{";
        if (!empty($products)){
            $products = ArrayHelper::getColumn($products, 'value');
            $key .= join(",", $products);
        }
        $key .= "}";

        if (isset($rel[$key]))
            return [
                'status' => true,
                'product_group_id' => $rel[$key]["product_group_id"],
            ];
        else
            return [
                'status' => false,
            ];
    }
}
