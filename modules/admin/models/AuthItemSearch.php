<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AuthItemsSearch represents the model behind the search form of `app\modules\admin\models\AuthItem`.
 */
class AuthItemSearch extends AuthItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data', 'category', 'name_for_user'], 'safe'],
            [['type', 'created_at', 'updated_at', 'role_type'], 'integer'],
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
        $query = AuthItem::find()
        ->orderBy(['created_at' => SORT_DESC]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role_type' => $this->role_type,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'name_for_user', $this->name_for_user])
            ->andFilterWhere(['ilike', 'rule_name', $this->rule_name])
            ->andFilterWhere(['ilike', 'data', $this->data]);

        return $dataProvider;
    }
}
