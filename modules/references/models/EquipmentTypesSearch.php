<?php

namespace app\modules\references\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\references\models\EquipmentTypes;

/**
 * EquipmentTypesSearch represents the model behind the search form of `app\modules\references\models\EquipmentTypes`.
 */
class EquipmentTypesSearch extends EquipmentTypes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EquipmentTypes::find()
            ->orderBy(["id" => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query->andFilterWhere([
            'id' => $this->id,
            'status_id' => $this->status_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}
