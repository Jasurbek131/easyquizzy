<?php

namespace app\modules\hr\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrDepartments;

/**
 * HrDepartmentsSearch represents the model behind the search form of `app\modules\hr\models\HrDepartments`.
 */
class HrDepartmentsSearch extends HrDepartments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['name', 'name_ru', 'token'], 'safe'],
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
        $query = HrDepartments::find();

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
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'name_ru', $this->name_ru])
            ->andFilterWhere(['ilike', 'token', $this->token]);

        return $dataProvider;
    }
}
