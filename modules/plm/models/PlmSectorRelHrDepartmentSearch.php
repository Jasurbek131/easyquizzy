<?php

namespace app\modules\plm\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PlmSectorRelHrDepartmentSearch represents the model behind the search form of `app\modules\plm\models\PlmSectorRelHrDepartment`.
 */
class PlmSectorRelHrDepartmentSearch extends PlmSectorRelHrDepartment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['category_id', 'hr_department_id'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PlmSectorRelHrDepartment::find()
            ->alias("psrhd")
            ->select([
                "MAX(psrhd.hr_department_id) as id",
                "MAX(psrhd.hr_department_id) as hr_department_id",
                "psrhd.status_id",
            ])
            ->joinWith("hrDepartments hd")
            ->joinWith("category as c")
            ->groupBy([
                "psrhd.hr_department_id",
                "psrhd.status_id",
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query
            ->andFilterWhere(['psrhd.status_id' => $this->status_id])
            ->andFilterWhere(['ilike', "hd.name" , $this->hr_department_id])
            ->andFilterWhere(['ilike', sprintf("c.name_%s", Yii::$app->language), $this->category_id]);
        return $dataProvider;
    }
}
