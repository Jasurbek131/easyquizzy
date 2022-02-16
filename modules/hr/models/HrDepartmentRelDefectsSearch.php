<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrDepartmentRelDefects;

/**
 * HrDepartmentRelDefectsSearch represents the model behind the search form of `app\modules\hr\models\HrDepartmentRelDefects`.
 */
class HrDepartmentRelDefectsSearch extends HrDepartmentRelDefects
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hr_department_id', 'defect_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = HrDepartmentRelDefects::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'hr_department_id' => $this->hr_department_id,
            'defect_id' => $this->defect_id,
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}