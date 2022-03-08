<?php

namespace app\modules\admin\models;

use app\models\BaseModel;
use Yii;

/**
 * This is the model class for table "admin_logs".
 *
 * @property int $id
 * @property array $old_attribute
 * @property array $new_attribute
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 */
class AdminLogs extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['old_attribute', 'new_attribute', 'table_name', 'class_name'], 'safe'],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'old_attribute' => Yii::t('app', 'Old Attribute'),
            'new_attribute' => Yii::t('app', 'New Attribute'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @param $old_attribute
     * @param $new_attribute
     * @param $table_name
     * @param $class_name
     * @return array
     */
    public static function saveLog($old_attribute, $new_attribute, $table_name, $class_name)
    {
        $response = [
            'status' => true,
            'message' => Yii::t('app','Success'),
        ];
        if (is_array($old_attribute) && is_array($new_attribute)){

            $log = new self([
                "old_attribute" => $old_attribute,
                "new_attribute" => $new_attribute,
                "table_name" => $table_name,
                "class_name" => $class_name,
            ]);
            if (!$log->save())
                $response = [
                    'status' => false,
                    'errors' => $log->getErrors(),
                    'message' => Yii::t('app','Log not saved'),
                ];

        }else{
            $response = [
                'status' => false,
                'message' => Yii::t('app','Attributes not array'),
            ];
        }

        return $response;
    }
}
