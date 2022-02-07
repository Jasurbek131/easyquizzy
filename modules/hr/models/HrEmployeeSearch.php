<?php

namespace app\modules\hr\models;

use app\widgets\Language;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * HrEmployeeSearch represents the model behind the search form of `app\modules\hr\models\HrEmployee`.
 */
class HrEmployeeSearch extends HrEmployee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['firstname', 'lastname', 'fathername', 'phone_number', 'email', 'hr_department_id','hr_position_id'], 'safe'],
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
     * @param array $params
     * @return ActiveDataProvider
     * @throws \Exception
     */
    public function search($params)
    {
        $query = HrEmployee::find()
            ->alias('he')
            ->orderBy(['he.id' => SORT_DESC])
            ->joinWith(["hrEmployeeActivePosition.hrDepartments as hd"])
            ->joinWith(["hrEmployeeActivePosition.hrPositions as hp"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate())
            return $dataProvider;

        $query->andFilterWhere([
            'he.status_id' => $this->status_id,
        ]);
        $query->andFilterWhere(['ilike', 'he.firstname', $this->firstname])
            ->andFilterWhere(['ilike', 'he.lastname', $this->lastname])
            ->andFilterWhere(['ilike', 'hd.name', $this->hr_department_id])
            ->andFilterWhere(['ilike', sprintf('hp.%s', Language::widget()), $this->hr_position_id])
            ->andFilterWhere(['ilike', 'he.fathername', $this->fathername])
            ->andFilterWhere(['ilike', 'he.phone_number', $this->phone_number])
            ->andFilterWhere(['ilike', 'he.email', $this->email]);

        return $dataProvider;
    }
}
