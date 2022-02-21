<?php

namespace app\modules\plm\models;

use app\modules\hr\models\HrEmployeeRelPosition;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\plm\models\PlmNotificationsList;

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
            [['id', 'plm_doc_item_id', 'defect_id', 'defect_type_id', 'defect_count', 'reason_id', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'plm_sector_list_id'], 'integer'],
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
                    'hd.name AS department',
                    'sh.name shift',
                    'product.product',
                    'pnl.defect_type_id',
                    'pnl.begin_time',
                    'pnl.end_time',
                    'r.name_uz AS reason',
                    'defect.defect',
                    'defect.count',
                    'pnl.status_id'
                ]);
        $query = $query
            ->leftJoin(['psrd' => 'plm_sector_rel_hr_department'],'pnl.plm_sector_list_id = psrd.plm_sector_list_id')
            ->leftJoin(['pdi' => 'plm_document_items'],'pnl.plm_doc_item_id = pdi.id')
            ->leftJoin(['pd' => 'plm_documents'],'pdi.document_id = pd.id')
            ->leftJoin(['sh' => 'shifts'],'pd.shift_id = sh.id')
            ->leftJoin(['hd' => 'hr_departments'],'pd.hr_department_id = hd.id')
            ->leftJoin(['r' => 'reasons'],'pnl.reason_id = r.id')
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
            ],'product.document_item_id = pnl.plm_doc_item_id');

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
            'defect_id' => $this->defect_id,
            'defect_type_id' => $this->defect_type_id,
            'defect_count' => $this->defect_count,
            'reason_id' => $this->reason_id,
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'plm_sector_list_id' => $this->plm_sector_list_id,
        ]);

        $query->andFilterWhere(['ilike', 'add_info', $this->add_info]);
        return $dataProvider;
    }
}
