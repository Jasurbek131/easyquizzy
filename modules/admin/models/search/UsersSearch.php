<?php

namespace app\modules\admin\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'hr_organisation_id'], 'integer'],
            [['username', 'password', 'auth_key', 'hr_employee_id'], 'safe'],
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
        $query = Users::find()
            ->alias('u')
            ->select([
                "u.id",
                "u.username",
                "u.username",
            ])
            ->orderBy(["u.id" => SORT_DESC]);

        $query->joinWith(["hrEmployees.hrEmployee as he"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            "u.status_id" => $this->status_id,
            "u.hr_organisation_id" => $this->hr_organisation_id,
        ]);

        $query->andFilterWhere(['ilike', "u.username", $this->username]);

        $query->orFilterWhere(['ilike', "CONCAT_WS(' ', he.lastname, he.firstname, he.fathername)", $this->hr_employee_id])
            ->orFilterWhere(['ilike', "CONCAT_WS(' ', he.lastname, he.fathername, he.firstname)", $this->hr_employee_id])
            ->orFilterWhere(['ilike', "CONCAT_WS(' ', he.firstname, he.lastname, he.fathername)", $this->hr_employee_id])
            ->orFilterWhere(['ilike', "CONCAT_WS(' ', he.firstname, he.fathername, he.lastname)", $this->hr_employee_id])
            ->orFilterWhere(['ilike', "CONCAT_WS(' ', he.fathername, he.lastname, he.firstname)", $this->hr_employee_id])
            ->orFilterWhere(['ilike', "CONCAT_WS(' ', he.fathername, he.firstname, he.lastname)", $this->hr_employee_id]);

        return $dataProvider;
    }
}
