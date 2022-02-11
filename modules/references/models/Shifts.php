<?php

namespace app\modules\references\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "shifts".
 *
 * @property int $id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property string $code
 * @property int $status_id
 * @property float $value
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class Shifts extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shifts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'start_time', 'end_time', 'status_id', 'value'], 'required'],
            [['start_time', 'end_time'], 'safe'],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'default', 'value' => null],
            [['status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'code'], 'string', 'max' => 50],
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
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'code' => Yii::t('app', 'Code'),
            'value' => Yii::t('app', 'Value'),
            'status_id' => Yii::t('app', 'Status ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
    public static function  getList()
    {
        $query = self::find()->select(['id','name'])->where(['status_id' => \app\models\BaseModel::STATUS_ACTIVE])->asArray()->all();
        if(!empty($query)){
            return ArrayHelper::map($query, 'id', 'name');
        }
    }

}
