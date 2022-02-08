<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $part_number
 * @property int $status_id
 * @property array $equipments
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property ProductLifecycle[] $productLifecycles
 * @property int $equipment_group_id [integer]
 */
class Products extends BaseModel
{

    /**
     * @var array
     * Mahsulot uskunalarini olish uchun
     */
    public $equipments;

    /**
     * @var bool
     * Maxsulot malumotlari yangilanayotgan bo'lsa: true bo'ladi
     */
    public $isUpdate = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'part_number', 'status_id', 'equipments'], 'required'],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code', 'part_number'], 'string', 'max' => 100],
            [['equipment_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentGroup::class, 'targetAttribute' => ['equipment_group_id' => 'id']],
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
            'code' => Yii::t('app', 'Code'),
            'part_number' => Yii::t('app', 'Part Number'),
            'equipments' => Yii::t('app', 'Equipments'),
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
    public function getProductLifecycles()
    {
        return $this->hasMany(ProductLifecycle::class, ['product_id' => 'id']);
    }

    /**
     * @param null $key
     * @param bool $isArray
     * @return array|string|\yii\db\ActiveRecord[]
     */
    public static function getList($key = null, $isArray = false) {
        if (!is_null($key)){
            $product = self::findOne($key);
            if (!empty($product)) {
                return $product['name'] . ' (' . $product['part_number'] . ')';
            }
            return "";
        }
        $list = self::find()
            ->select(['id as value', "CONCAT(name, ' (', part_number, ')') as label"])
            ->asArray()
            ->where(['status_id' => \app\models\BaseModel::STATUS_ACTIVE])
            ->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }

    /**
     * @return array
     */
    public function saveProduct(): array
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
                    'message' => 'Product not saved',
                    'errors' => $this->getErrors()
                ];

            if ($response['status']){

                if ($this->isUpdate)
                    ReferencesProductRelEquipment::deleteAll(["product_id" => $this->id]);

                foreach ($this->equipments as $equipment){
                    $rel = new ReferencesProductRelEquipment([
                        'product_id' => $this->id,
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
}
