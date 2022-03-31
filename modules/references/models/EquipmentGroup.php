<?php

namespace app\modules\references\models;

use app\models\BaseModel;
use app\modules\hr\models\HrDepartmentRelEquipment;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "equipment_group".
 * @property-read ActiveQuery $cycles
 * @property-read ActiveQuery $equipmentType
 * @property string $id [integer]
 * @property string $name [varchar(255)]
 * @property string $status_id [integer]
 * @property string $created_at [integer]
 * @property string $created_by [integer]
 * @property string $updated_at [integer]
 * @property string $updated_by [integer]
 * @property int $value [numeric(20,3)]
 * @property string $equipments_group_type_id [integer]
 * @property string $equipment_type_id [integer]
 */
class EquipmentGroup extends BaseModel
{
    public $equipments;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'equipment_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status_id'], 'required'],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'equipments_group_type_id'], 'default', 'value' => null],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['equipment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentTypes::class, 'targetAttribute' => ['equipment_type_id' => 'id']],
            [["value"], "number"],
            [['equipments'], 'safe']
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
            'equipment_type_id' => Yii::t('app', 'Equipment Type'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }

    /**
     * @return ActiveQuery
     */
    public function getEquipments(): ActiveQuery
    {
        return $this->hasMany(EquipmentGroupRelationEquipment::class, ['equipment_group_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCycles(): ActiveQuery
    {
        return $this->hasMany(ProductLifecycle::class, ['equipment_group_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEquipmentType(): ActiveQuery
    {
        return $this->hasOne(EquipmentTypes::class,  ['equipment_type_id' => 'id']);
    }

    /**
     * @param null $key
     * @param bool $isArray
     * @return array|string|ActiveRecord[]
     */
    public static function getList($key = null, $isArray = false)
    {
        if (!is_null($key)) {
            $one = self::findOne($key);
            if (!empty($one)) {
                return $one['name'];
            }
            return "";
        }
        $list = self::find()
            ->select(['id as value', "name as label"])
            ->asArray()
            ->where(['status_id' => \app\models\BaseModel::STATUS_ACTIVE])
            ->all();
        if ($isArray) {
            return $list;
        }
        return ArrayHelper::map($list, 'value', 'label');
    }

    /**
     * @return array|yii\db\ActiveRecord[]
     */
    public static function getEquipmentGroupList($department_id)
    {
        $lists = EquipmentGroup::find()
            ->alias('eg')
            ->select([
                'eg.id as value',
                'eg.name as label',
                'eg.id',
                'eg.equipment_type_id',
            ])->with([
                'equipments' => function ($e) use ($department_id) {
                    $e->from(['egr' => 'equipment_group_relation_equipment'])
                    ->select([
                        'egr.equipment_id',
                        'egr.equipment_group_id',
                        'e.name as label',
                        'e.id as value'
                    ])->leftJoin('equipments e', 'egr.equipment_id = e.id')
                      ->leftJOin(['hre'=>'hr_department_rel_equipment'], 'e.id = hre.equipment_id')
                      ->where(['IN','hre.hr_department_id', $department_id])
                      ->andWhere(['hre.status_id' => BaseModel::STATUS_ACTIVE]);
                },
                'cycles' => function ($pl) {
                    $pl->from(['pl' => 'product_lifecycle'])
                        ->select([
                            'pl.id as product_lifecycle_id',
                            'pl.lifecycle',
                            'pl.bypass',
                            'pl.equipment_group_id',
                            'pl.product_group_id',
                        ])
                        ->with(["productGroup.products" => function ($rp) {
                            $rp->from(["rp" => "references_product_group_rel_product"])
                                ->select([
                                    "rp.product_group_id",
                                    "rp.product_id",
                                    "p.name as label",
                                    "p.id as value",
                                ])
                                ->leftJoin('products p', 'rp.product_id = p.id');
                        }]);
                }
            ])->where(['eg.status_id' => BaseModel::STATUS_ACTIVE])
            ->asArray()
            ->all();

        if (!empty($lists)) {
            foreach ($lists as $list_key => $list) {
                if ($list["cycles"]) {
                    $lists[$list_key] = self::getProductList($lists, $list, $list_key);
                    unset($lists[$list_key]["cycles"]);
                }
            }
        }
        return $lists;
    }

    /**
     * @param $lists
     * @param $list
     * @param $list_key
     * @return mixed
     */
    public static function getProductList($lists, $list, $list_key){
        $i =  0;
        foreach ($list["cycles"] as $cycle_key => $cycle) {
            if ($cycle["productGroup"]["products"]) {
                foreach ($cycle["productGroup"]["products"] as $product_key => $product) {
                    $lists[$list_key]["product_list"][$i] = $product;
                    $lists[$list_key]["product_list"][$i]["lifecycle"] = $cycle["lifecycle"] ?? [];
                    $lists[$list_key]["product_list"][$i]["bypass"] = $cycle["bypass"] ?? [];
                    $lists[$list_key]["product_list"][$i]["equipment_group_id"] = $cycle["equipment_group_id"];
                    $i++;
                }
            }
        }
        return $lists[$list_key];
    }

    /**
     * @param $equipments
     * @return array
     * Uskunalardan tashkil topgan guruh borligini tekshiradi
     * Agar bor bor bo'lsa uni id qaytarib beradi [status => true, equipment_group_id => 1]
     * Aks holda [status => false]
     */
    public static function existsGroup($equipments): array
    {
        $rel = EquipmentGroupRelationEquipment::find()
            ->select([
                "array_agg(equipment_id) as equipment_ids",
                "MAX(equipment_group_id) as equipment_group_id"
            ])
            ->groupBy([
                "equipment_group_id"
            ])
            ->asArray()
            ->all();

        if (!empty($rel))
            $rel = ArrayHelper::index($rel, 'equipment_ids');

        $key = "{";
        if (!empty($equipments)) {
            $equipments = ArrayHelper::getColumn($equipments, 'value');
            $key .= join(",", $equipments);
        }
        $key .= "}";

        if (isset($rel[$key]))
            return [
                'status' => true,
                'equipment_group_id' => $rel[$key]["equipment_group_id"],
            ];
        else
            return [
                'status' => false,
            ];
    }
}
