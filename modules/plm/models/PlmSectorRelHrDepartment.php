<?php

namespace app\modules\plm\models;

use app\modules\hr\models\HrDepartments;
use app\modules\references\models\Categories;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "plm_setting_accepted_sector_rel_hr_department".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $type
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $categories
 * @property int $updated_by
 * @property boolean $isUpdate
 * @property int $category_id
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 */
class PlmSectorRelHrDepartment extends BaseModel
{
    const CONFIRM_TYPE = 1;

    const CATEGORIES_TYPE = 2;

    public $department;
    public $shift;
    public $product;
    public $defect;
    public $reason;
    public $equipment;
    public $reg_date;
    public $token;
    public $types;
    public $doc_number;

    public $equipment_group_name;
    /**
     * @var array
     */
    public $categories = [];

    /**
     * @var bool
     */
    public $isUpdate = false;

    /**
     * @var
     * To'xtalish sababini olish uchun
     */
    public $category_name;

    /**
     * @var
     */
    const SCENARIO_CREATE = "scenario_create";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_sector_rel_hr_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id','status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_department_id','status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'type'], 'integer'],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::class, 'targetAttribute' => ['hr_department_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['hr_department_id'], 'required'],
            [['categories'], 'safe'],
            [['categories'], "required", "on" => self::SCENARIO_CREATE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'hr_department_id' => Yii::t('app', 'Hr Department ID'),
            'category_id' => Yii::t('app', 'Categories'),
            'categories' => Yii::t('app', 'Categories'),
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
    public function getHrDepartments()
    {
        return $this->hasOne(HrDepartments::class, ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * @return array
     */
    public function saveRelHrDepartment():array
    {
        $transaction = Yii::$app->db->beginTransaction();
        $response = [
            'status' => true,
            'message' => Yii::t('app', 'Success'),
        ];
        try {
            if ($response['status']) {
                if (!empty($this->categories)) {
                    if ($this->isUpdate && $this->hr_department_id)
                        PlmSectorRelHrDepartment::deleteAll([
                            "hr_department_id" => $this->hr_department_id,
                            "type" => $this->type
                        ]);

                    foreach ($this->categories as $item) {
                        /**
                         * @var self $item
                         */
                        $rel = new self([
                            "hr_department_id" => $this->hr_department_id,
                            "category_id" => $item,
                            "status_id" => \app\models\BaseModel::STATUS_ACTIVE,
                            'type'=> $this->type
                        ]);
                        if (!$rel->save()) {
                            $response = [
                                'status' => false,
                                'errors' => $rel->getErrors(),
                                'message' => Yii::t('app', 'Rel item not saved'),
                            ];
                            break;
                        }
                    }
                } else {
                    $response = [
                        'status' => false,
                        'message' => Yii::t('app', 'Categories are empty'),
                    ];
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
                'message' => $e->getMessage(),
            ];
        }
        return $response;
    }

    /**
     * @param $type
     * @return PlmSectorRelHrDepartment
     */
    public function setType($type): PlmSectorRelHrDepartment
    {
        $new = clone $this;
        $new->type = $type;
        return $new;
    }
    /**
     * @return $this
     */
    public function getCategoriesIdListByDepartment(): self
    {
        $data = self::find()
            ->select([
                "category_id"
            ])
            ->where(["hr_department_id" => $this->hr_department_id])
            ->where(["type" => $this->type])
            ->asArray()
            ->all();

        if ($data)
            $data = ArrayHelper::getColumn($data, "category_id");

        $new = clone $this;
        $new->categories = $data;
        return $new;
    }

    /**
     * @param $id
     * @return string
     */
    public static function getCategoriesByDepartment(int $id): string
    {
        $list = self::find()
            ->alias('psrhd')
            ->select([
                'c.id',
                sprintf('c.name_%s as name', Yii::$app->language)
            ])
            ->leftJoin('categories c', 'psrhd.category_id = c.id')
            ->where(['psrhd.hr_department_id' => $id])
            ->andWhere(['psrhd.status_id' => \app\models\BaseModel::STATUS_ACTIVE])
            ->asArray()
            ->all();
        $text = "";
        if (!empty($list)) {
            foreach ($list as $item) {
                $text .= "<span class='badge badge-primary mr-1'>" . $item['name'] . "</span>";
            }
        }
        return $text;
    }

    /**
     * @param null $department_id
     * @param  $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getHrRelDeparea($type, $department_id = null)
    {
        if (!empty($department_id)) {
            $data = self::find()
                ->alias('psrhd')
                ->select([
                    "hrd.id AS id",
                    "hrd.name AS dep_name",
                    sprintf("ARRAY_TO_STRING(ARRAY_AGG(DISTINCT CONCAT('<span class=".'"badge badge-primary mr-1"'.">',c.name_%s,'</span>')),' ') AS category", Yii::$app->language),
                    "MAX(sl.name_uz) as status_name",
                    "MAX(sl.id) as status"
                ])
                ->leftJoin(['c' => 'categories'], 'psrhd.category_id = c.id')
                ->leftJoin(['hrd' => 'hr_departments'], 'psrhd.hr_department_id = hrd.id')
                ->leftJoin(['sl' => 'status_list'], 'psrhd.status_id = sl.id')
                ->where([
                    'psrhd.hr_department_id' => $department_id,
                    'psrhd.type' => $type
                ])
                ->groupBy(["hrd.id"])
//                ->orderBy(['psrhd.id' => SORT_DESC, 'psrhd.status_id' => SORT_ASC])
                ->asArray()
                ->all();
            return $data ?? [];
        }
        return [];
    }
}
