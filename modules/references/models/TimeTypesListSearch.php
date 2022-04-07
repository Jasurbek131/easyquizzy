<?php

namespace app\modules\references\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\references\models\TimeTypesList;

/**
 * TimeTypesListSearch represents the model behind the search form of `app\modules\references\models\TimeTypesList`.
 */
class TimeTypesListSearch extends TimeTypesList
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'code', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'safe'],
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
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TimeTypesList::find();

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
            'code' => $this->code,
            'status_id' => BaseModel::STATUS_ACTIVE,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}
