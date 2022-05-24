<?php

namespace app\modules\references\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\references\models\EquipmentGroup;

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
            [['name', 'value', 'equipments', 'equipment_type_id'], 'safe'],
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
        ]);

        $query->andFilterWhere(['ilike', 'eg.name', $this->name]);
        $query->andFilterWhere(['ilike', 'e.name', $this->equipments]);
        $query->andFilterWhere(['eg.equipment_type_id' => $this->equipment_type_id]);

        return $dataProvider;
    }
}
