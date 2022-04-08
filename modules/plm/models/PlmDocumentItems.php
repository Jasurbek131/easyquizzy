<?php

namespace app\modules\plm\models;

use app\models\BaseModel;
use app\modules\references\models\EquipmentGroup;
use Yii;

/**
 * This is the model class for table "plm_document_items".
 *
 * @property int $id
 * @property int $planned_stop_id
 * @property int $unplanned_stop_id
 * @property int $status_id
 * @property float $lifecycle
 * @property float $bypass
 * @property float $target_qty
 * @property int $processing_time_id
 * @property int $document_id
 * @property int $equipment_group_id
 *
 * @property EquipmentGroup $equipmentGroup
 * @property PlmDocuments $plmDocuments
 * @property PlmProcessingTime $plmProcessingTime
 * @property PlmStops $plmStops
 * @property PlmDocItemDefects[] $plmDocItemDefects
 * @property PlmDocItemProducts[] $plmDocItemProducts
 */
class PlmDocumentItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plm_document_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['planned_stop_id', 'unplanned_stop_id', 'processing_time_id', 'document_id', 'equipment_group_id'], 'default', 'value' => null],
            [['planned_stop_id', 'unplanned_stop_id', 'processing_time_id', 'document_id', 'equipment_group_id', 'status_id'], 'integer'],
            [[ 'lifecycle', 'bypass', 'target_qty'], 'number'],
            [['equipment_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentGroup::class, 'targetAttribute' => ['equipment_group_id' => 'id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmDocuments::class, 'targetAttribute' => ['document_id' => 'id']],
            [['processing_time_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmProcessingTime::class, 'targetAttribute' => ['processing_time_id' => 'id']],
            [['planned_stop_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmStops::class, 'targetAttribute' => ['planned_stop_id' => 'id']],
            [['unplanned_stop_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlmStops::class, 'targetAttribute' => ['unplanned_stop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'planned_stop_id' => Yii::t('app', 'Planned Stop ID'),
            'unplanned_stop_id' => Yii::t('app', 'Unplanned Stop ID'),
            'processing_time_id' => Yii::t('app', 'Processing Time ID'),
            'document_id' => Yii::t('app', 'Document ID'),
            'equipment_group_id' => Yii::t('app', 'Equipment Group ID'),
        ];
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getEquipmentGroup()
    {
        return $this->hasOne(EquipmentGroup::class, ['id' => 'equipment_group_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlmDocuments()
    {
        return $this->hasOne(PlmDocuments::class, ['id' => 'document_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlmProcessingTime()
    {
        return $this->hasOne(PlmProcessingTime::class, ['id' => 'processing_time_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlanned_stopped()
    {
        return $this->hasOne(PlmStops::class, ['id' => 'planned_stop_id']);
    }
    /**
     * @return yii\db\ActiveQuery
     */
    public function getUnplanned_stopped()
    {
        return $this->hasOne(PlmStops::class, ['id' => 'unplanned_stop_id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlanned_stops()
    {
        return $this->hasMany(PlmStops::class, ['document_item_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getUnplanned_stops()
    {
        return $this->hasMany(PlmStops::class, ['document_item_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPlmDocItemDefects()
    {
        return $this->hasMany(PlmDocItemDefects::class, ['doc_item_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(PlmDocItemProducts::class, ['document_item_id' => 'id']);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getEquipments()
    {
        return $this->hasMany(PlmDocItemEquipments::class, ['document_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications_status()
    {
        return $this->hasMany(PlmNotificationsList::class, ['plm_doc_item_id' => 'id']);
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public static  function getAdditionalData($id)
    {
        $language = Yii::$app->language;
        $data =  self::find()
            ->alias("pdi")
            ->with([
                'planned_stops' => function ($e) use ($language) {
                    $e->from(['ps1' => 'plm_stops'])
                        ->select([
                            'ps1.id',
                            'ps1.begin_date',
                            "to_char(ps1.begin_date, 'DD.MM.YYYY HH24:MI:SS') as format_begin_date",
                            "to_char(ps1.end_time, 'DD.MM.YYYY HH24:MI:SS') as format_end_time",
                            'ps1.end_time',
                            'ps1.add_info',
                            'ps1.category_id',
                            "ps1.document_item_id",
                            "c.name_{$language} as category_name",
                        ])
                        ->leftJoin(["c" => "categories"], "ps1.category_id = c.id")
                        ->where([
                            'ps1.stopping_type' => \app\modules\plm\models\BaseModel::PLANNED_STOP,
                            'ps1.status_id' => BaseModel::STATUS_ACTIVE,
                        ]);
                },
                'unplanned_stops' => function ($e) use ($language) {
                    $e->from(['ps2' => 'plm_stops'])
                        ->select([
                            'ps2.id',
                            'ps2.begin_date',
                            'ps2.end_time',
                            'ps2.add_info',
                            'ps2.category_id',
                            'ps2.bypass',
                            "ps2.document_item_id",
                            "c.name_{$language} as category_name",
                        ])
                        ->leftJoin(["c" => "categories"], "ps2.category_id = c.id")
                        ->where([
                            'ps2.stopping_type' => \app\modules\plm\models\BaseModel::UNPLANNED_STOP,
                            'ps2.status_id' => BaseModel::STATUS_ACTIVE,
                        ]);
                },
                'notifications_status' => function($ns){
                    $ns->from(['pnl' => "plm_notifications_list"])
                        ->select([
                            "pnl.id",
                            "pnl.plm_doc_item_id",
                            "pnl.status_id",
                            "pnl.category_id",
                            "pnl.stop_id",
                            "c.token",
                        ])
                        ->joinWith(["messages"])
                        ->leftJoin(["c" => "categories"], 'pnl.category_id = c.id');
//                                    ->where(["NOT IN", "pnl.status_id" , [\app\modules\plm\models\BaseModel::STATUS_REJECTED]]);
                }
            ])
            ->where([
                "pdi.id" => $id,
                "pdi.status_id" => BaseModel::STATUS_ACTIVE
            ])
            ->asArray()
            ->one();
        $data["notifications_status"] = PlmNotificationsList::formatterNotificationStatus($data["notifications_status"] ?? []);
        return $data;
    }
}
