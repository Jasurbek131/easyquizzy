<?php

namespace app\modules\plm\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\plm\models\PlmSettingAcceptedSectorRelHrDepartment;

/**
 * PlmSettingAcceptedSectorRelHrDepartmentSearch represents the model behind the search form of `app\modules\plm\models\PlmSettingAcceptedSectorRelHrDepartment`.
 */
class PlmSettingAcceptedSectorRelHrDepartmentSearch extends PlmSettingAcceptedSectorRelHrDepartment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'hr_department_id', 'plm_sector_list_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
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
        $query = PlmSettingAcceptedSectorRelHrDepartment::find();

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
            'hr_department_id' => $this->hr_department_id,
            'plm_sector_list_id' => $this->plm_sector_list_id,
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
