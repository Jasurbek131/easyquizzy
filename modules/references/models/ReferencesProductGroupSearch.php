<?php

namespace app\modules\references\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductsSearch represents the model behind the search form of `app\modules\references\models\Products`.
 */
class ReferencesProductGroupSearch extends ReferencesProductGroup
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
        $query = ReferencesProductGroup::find()
            ->alias("rpg")
            ->orderBy(["id" => SORT_DESC])
            ->joinWith(["referencesProductGroupRelProducts.products as p"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query->andFilterWhere(['rpg.status_id' => $this->status_id])
            ->andFilterWhere(["ilike", 'p.name', $this->name]);
        return $dataProvider;
    }
}
