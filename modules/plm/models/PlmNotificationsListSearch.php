<?php

namespace app\modules\plm\models;

use app\modules\hr\models\HrEmployeeRelPosition;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PlmNotificationsListSearch represents the model behind the search form of `app\modules\plm\models\PlmNotificationsList`.
 */
class PlmNotificationsListSearch extends PlmNotificationsList
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plm_doc_item_id', 'defect_type_id', 'defect_count', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at','category_id'], 'integer'],
            [['begin_time', 'end_time', 'add_info'], 'safe'],
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
        $query = PlmNotificationsList::find()
            ->alias('pnl')
            ->select([
                    'pnl.id',
                    'pd.reg_date',
                    'hd.name AS department',
                    'sh.name shift',
                    'product.product',
                    'equipment.equipment',
                    'pnl.defect_type_id',
                    'pnl.begin_time',
                    'pnl.end_time',
                    'defect.defect',
                    'defect.count AS defect_count',
                    'pnl.status_id',
                    'c.token',
                ]);
        $query = $query
            ->leftJoin(['psrd' => 'plm_sector_rel_hr_department'],'pnl.category_id = psrd.category_id')
            ->leftJoin(['pdi' => 'plm_document_items'],'pnl.plm_doc_item_id = pdi.id')
            ->leftJoin(['pd' => 'plm_documents'],'pdi.document_id = pd.id')
            ->leftJoin(['sh' => 'shifts'],'pd.shift_id = sh.id')
            ->leftJoin(['hd' => 'hr_departments'],'pd.hr_department_id = hd.id')
            ->leftJoin(['c' => 'categories'],'pnl.category_id = c.id')
            ->leftJoin(['defect' => PlmNotificationRelDefect::find()
                                    ->alias('pnrd')
                                    ->select([
                                        'pnrd.plm_notification_list_id',
                                        'SUM(pnrd.defect_count) AS count',
                                        "STRING_AGG(DISTINCT d.name_uz,', ') AS defect"
                                    ])
                ->leftJoin(['d' => 'defects'],'pnrd.defect_id = d.id')
                ->groupBy(['pnrd.plm_notification_list_id'])
            ],'defect.plm_notification_list_id = pnl.id')
            ->leftJoin(['product' => PlmDocItemProducts::find()
                                    ->alias('pdip')
                                    ->select([
                                        "pdip.document_item_id",
                                        "STRING_AGG(DISTINCT p.name,', ') AS product",
                                    ])
                ->leftJoin(['p' => 'products'],'pdip.product_id = p.id')
                ->groupBy(['pdip.document_item_id'])
            ],'product.document_item_id = pnl.plm_doc_item_id')
            ->leftJoin(['equipment' => PlmDocItemEquipments::find()
                                    ->alias('pdie')
                                    ->select([
                                        "pdi.id",
                                        "STRING_AGG(DISTINCT e.name,', ') AS equipment",
                                    ])
                ->leftJoin(['e' => 'equipments'],'e.id = pdie.equipment_id')
                ->leftJoin(['pdi' => 'plm_document_items'],'pdi.id = pdie.document_item_id')
                ->groupBy(['pdi.id'])
            ],'equipment.id = pdi.id');
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
        $hr_department = HrEmployeeRelPosition::getActiveHrDepartment();
        $query = $query->andWhere(['=','psrd.hr_department_id', $hr_department['hr_department_id']]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'plm_doc_item_id' => $this->plm_doc_item_id,
            'begin_time' => $this->begin_time,
            'end_time' => $this->end_time,
            'defect_type_id' => $this->defect_type_id,
            'defect_count' => $this->defect_count,
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['ilike', 'add_info', $this->add_info]);
        $query->andFilterWhere(['!=', 'pnl.status_id', BaseModel::STATUS_INACTIVE]);
        $query->orderBy(['pnl.status_id' => SORT_ASC]);
        return $dataProvider;
    }
}
