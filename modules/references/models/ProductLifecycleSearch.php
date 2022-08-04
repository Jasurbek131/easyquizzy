<?php

namespace app\modules\references\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\references\models\ProductLifecycle;

/**
 * ProductLifecycleSearch represents the model behind the search form of `app\modules\references\models\ProductLifecycle`.
 */
class ProductLifecycleSearch extends ProductLifecycle
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'equipment_group_id', 'lifecycle', 'bypass', 'time_type_id', 'status_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
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
        $query = ProductLifecycle::find()
            ->orderBy(["id" => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'equipment_group_id' => $this->equipment_group_id,
            'lifecycle' => $this->lifecycle,
            'bypass' => $this->bypass,
            'time_type_id' => $this->time_type_id,
            'status_id' => BaseModel::STATUS_ACTIVE,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
