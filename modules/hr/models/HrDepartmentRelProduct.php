<?php

namespace app\modules\hr\models;

use app\modules\references\models\Products;
use Yii;

/**
 * This is the model class for table "hr_department_rel_product".
 *
 * @property int $id
 * @property int $hr_department_id
 * @property int $product_id
 * @property int $status_id
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property HrDepartments $hrDepartments
 * @property Products $products
 */
class HrDepartmentRelProduct extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_department_rel_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hr_department_id', 'product_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'default', 'value' => null],
            [['hr_department_id', 'product_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['hr_department_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrDepartments::className(), 'targetAttribute' => ['hr_department_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'product_id' => Yii::t('app', 'Product ID'),
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
        return $this->hasOne(HrDepartments::className(), ['id' => 'hr_department_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }

//    public static function getHrRelProduct($department_id = null){
//        if(!empty($department_id)){
//            $data = self::find()
//                ->alias('herp')
//                ->select([
//                    "herp.id AS id",
//                    "hrd.name AS dep_name",
//                    "p.name AS product_name",
//                    "p.part_number AS part_number",
//                    "sl.name_uz as status_name",
//                    "sl.id as status"
//                ])
//                ->leftJoin(['p' => 'products'],'herp.product_id = p.id')
//                ->leftJoin(['hrd' => 'hr_departments'],'herp.hr_department_id = hrd.id')
//                ->leftJoin(['sl' => 'status_list'],'herp.status_id = sl.id')
//                ->where(['herp.hr_department_id' => $department_id])
//                ->asArray()
//                ->orderBy(['herp.id' => SORT_DESC,'herp.status_id' => SORT_ASC])
//                ->all();
//            return $data ?? [];
//        }
//        return [];
//    }
}
