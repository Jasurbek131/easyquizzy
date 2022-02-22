<?php

namespace app\modules\plm\models;

use app\modules\hr\models\HrEmployeeRelPosition;
use app\modules\references\models\Defects;
use app\modules\references\models\Reasons;
use Yii;

/**
 * This is the model class for table "plm_notifications_list".
 *
 * @property int $id
 * @property int $plm_doc_item_id
 * @property int $plm_sector_list_id
 * @property string $begin_time
 * @property string $end_time
 * @property int $defect_id
 * @property int $defect_type_id
 * @property int $defect_count
 * @property int $reason_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 * @property float $by_pass
 * @property string $add_info
 *
 * @property Defects $defects
 * @property PlmDocumentItems $plmDocumentItems
 * @property Reasons $reasons
 */
class PlmNotificationsList extends BaseModel
{
    public $department;
    public $shift;
    public $product;
    public $defect;
    public $reason;
    public $equipment;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_notifications_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plm_doc_item_id', 'defect_id', 'defect_type_id', 'defect_count', 'reason_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at','by_pass'], 'default', 'value' => null],
            [['plm_doc_item_id', 'defect_id', 'defect_type_id', 'defect_count', 'reason_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['begin_time', 'end_time'], 'safe'],
            [['add_info'], 'string'],
            [['defect_id'], 'exist', 'skipOnError' => true, 'targetClass' => Defects::className(), 'targetAttribute' => ['defect_id' => 'id']],
            [['plm_doc_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocumentItems::className(), 'targetAttribute' => ['plm_doc_item_id' => 'id']],
            [['reason_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reasons::className(), 'targetAttribute' => ['reason_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'plm_doc_item_id' => Yii::t('app', 'Plm Doc Item ID'),
            'begin_time' => Yii::t('app', 'Begin Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'defect_id' => Yii::t('app', 'Defect ID'),
            'defect_type_id' => Yii::t('app', 'Defect Type ID'),
            'defect_count' => Yii::t('app', 'Defect Count'),
            'reason_id' => Yii::t('app', 'Reason ID'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'add_info' => Yii::t('app', 'Add Info'),
            'by_pass' => Yii::t('app', 'Bypass Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDefects()
    {
        return $this->hasOne(Defects::className(), ['id' => 'defect_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlmDocumentItems()
    {
        return $this->hasOne(PlmDocumentItems::className(), ['id' => 'plm_doc_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReasons()
    {
        return $this->hasOne(Reasons::className(), ['id' => 'reason_id']);
    }

    public static function getViews($id = null){
        $query = [];
        $hr_department = HrEmployeeRelPosition::getActiveHrDepartment(); // foydalanuvchini faol bo'limini olish
        if(!empty($id)){
            $query = self::find()
                ->alias('pnl')
                ->select([
                    'pnl.*',
                    'hd.name AS department',
                    'sh.name shift',
                    'product.product',
                    'equipment.equipment',
                    'r.name_uz AS reason',
                    'defect.defect',
                    'defect.count AS defect_count',
                ])
                ->leftJoin(['psrd' => 'plm_sector_rel_hr_department'],'pnl.plm_sector_list_id = psrd.plm_sector_list_id')
                ->leftJoin(['pdi' => 'plm_document_items'],'pnl.plm_doc_item_id = pdi.id')
                ->leftJoin(['pd' => 'plm_documents'],'pdi.document_id = pd.id')
                ->leftJoin(['sh' => 'shifts'],'pd.shift_id = sh.id')
                ->leftJoin(['hd' => 'hr_departments'],'pd.hr_department_id = hd.id')
                ->leftJoin(['r' => 'reasons'],'pnl.reason_id = r.id')
                ->leftJoin(['defect' => PlmNotificationRelDefect::find()
                    ->alias('pnrd')
                    ->select([
                        'pnrd.plm_notification_list_id',
                        'SUM(pnrd.defect_count) AS count',
                        "STRING_AGG(DISTINCT d.name_uz,', ') AS defect"
                    ])
                    ->leftJoin(['d' => 'defects'],'pnrd.defect_id = d.id')
                    ->groupBy(['pnrd.plm_notification_list_id'])
                ],'defect.plm_notification_list_id = pnl.id')
                ->leftJoin(['product' => PlmDocItemProducts::find()
                    ->alias('pdip')
                    ->select([
                        "pdip.document_item_id",
                        "STRING_AGG(DISTINCT p.name,', ') AS product",
                    ])
                    ->leftJoin(['p' => 'products'],'pdip.product_id = p.id')
                    ->groupBy(['pdip.document_item_id'])
                ],'product.document_item_id = pnl.plm_doc_item_id')
                ->leftJoin(['equipment' => PlmDocItemEquipments::find()
                    ->alias('pdie')
                    ->select([
                        "pdi.id",
                        "STRING_AGG(DISTINCT e.name,', ') AS equipment",
                    ])
                    ->leftJoin(['e' => 'equipments'],'e.id = pdie.equipment_id')
                    ->leftJoin(['pdi' => 'plm_document_items'],'pdi.id = pdie.document_item_id')
                    ->groupBy(['pdi.id'])
                ],'equipment.id = pdi.id')
            ->where(['pnl.id' => $id])
            ->andWhere(['=','psrd.hr_department_id', $hr_department['hr_department_id']])
            ->andFilterWhere(['!=','pnl.status_id', BaseModel::STATUS_INACTIVE])
            ->asArray()
            ->one();
        }
        return $query;
    }
}
