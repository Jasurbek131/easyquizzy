<?php

namespace app\modules\references\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EquipmentGroupSearch represents the model behind the search form of `app\modules\references\models\EquipmentGroup`.
 */
class EquipmentGroupSearch extends EquipmentGroup
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'value', 'equipments'], 'safe'],
            [[ 'repair_is_ok', 'is_plan_quantity_entered_manually'], 'boolean']
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
        $query = EquipmentGroup::find()
            ->alias("eg")
            ->leftJoin(["egre" => 'equipment_group_relation_equipment'], 'egre.equipment_group_id = eg.id')
            ->leftJoin(["e" => 'equipments'], 'egre.equipment_id = e.id')
            ->orderBy(["eg.id" => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query->andFilterWhere([
            'eg.status_id' => $this->status_id,
            'eg.value' => $this->value,
            'eg.repair_is_ok' => $this->repair_is_ok,
            'eg.is_plan_quantity_entered_manually' => $this->is_plan_quantity_entered_manually,
        ]);

        $query->andFilterWhere(['ilike', 'eg.name', $this->name]);
        $query->andFilterWhere(['ilike', 'e.name', $this->equipments]);

        return $dataProvider;
    }
}
