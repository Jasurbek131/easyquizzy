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
     * @var
     * Bo'lim malumotlarini olish uchun
     */
    public $department;

    /**
     * @var
     * Ishlab chiqarish hujjati nomerini olish uchun
     */
    public $doc_number;

    /**
     * @var
     * Ishlab chiqarish hujjati vaqtini olish uchun
     */
    public $reg_date;

    /**
     * @var
     * Ishlab chiqarish hujjati smenasini olish uchun
     */
    public $shift;

    /**
     * @var
     * Ishlab chiqarish hujjati uskunalarini olish uchun
     */
    public $equipment;

    /**
     * @var
     * Tasdiqlash turini olish uchun
     */
    public $categories;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'plm_doc_item_id', 'defect_type_id', 'defect_count', 'status_id', 'created_by', 'created_at', 'updated_by', 'updated_at', 'category_id'], 'integer'],
            [['begin_time', 'end_time', 'add_info', 'department', 'doc_number', 'reg_date', 'shift', 'equipment', 'categories'], 'safe'],
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
    public function search(array $params): ActiveDataProvider
    {
        $this->load($params);

        $query = PlmSectorRelHrDepartment::find()
            ->alias('psrd')
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
                'pd.doc_number',
            ])
            ->leftJoin(['pnl' => 'plm_notifications_list'], 'pnl.category_id = psrd.category_id')
            ->leftJoin(['ps' => 'plm_stops'], 'pnl.stop_id = ps.id')
            ->leftJoin(['pdi' => 'plm_document_items'], 'pnl.plm_doc_item_id = pdi.id')
            ->leftJoin(['pd' => 'plm_documents'], 'pdi.document_id = pd.id')
            ->leftJoin(['sh' => 'shifts'], 'pd.shift_id = sh.id')
            ->leftJoin(['hd' => 'hr_departments'], 'pd.hr_department_id = hd.id')
            ->leftJoin(['c' => 'categories'], 'pnl.category_id = c.id')
            ->leftJoin(['defect' => PlmNotificationRelDefect::find()
                ->alias('pnrd')
                ->select([
                    'pnrd.plm_notification_list_id',
                    'SUM(pnrd.defect_count) AS count',
                    "STRING_AGG(DISTINCT d.name_uz,', ') AS defect"
                ])
                ->leftJoin(['d' => 'defects'], 'pnrd.defect_id = d.id')
                ->groupBy(['pnrd.plm_notification_list_id'])
            ], 'defect.plm_notification_list_id = pnl.id')
            ->leftJoin(['product' => PlmDocItemProducts::find()
                ->alias('pdip')
                ->select([
                    "pdip.document_item_id",
                    "STRING_AGG(DISTINCT p.name,', ') AS product",
                ])
                ->leftJoin(['p' => 'products'], 'pdip.product_id = p.id')
                ->groupBy(['pdip.document_item_id'])
            ], 'product.document_item_id = pnl.plm_doc_item_id')
            ->innerJoin(['equipment' => PlmDocItemEquipments::find()
                ->alias('pdie')
                ->select([
                    "pdi.id as pdi_id",
                    "STRING_AGG(DISTINCT e.name,', ') AS equipment",
                ])
                ->innerJoin(['e' => 'equipments'], 'e.id = pdie.equipment_id')
                ->leftJoin(['pdi' => 'plm_document_items'], 'pdi.id = pdie.document_item_id')
                ->andFilterWhere(['ilike', 'e.name', $this->equipment])
                ->groupBy(['pdi.id'])
            ], 'equipment.pdi_id = pdi.id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!$this->validate()) {
            return $dataProvider;
        }
        $hr_department = HrEmployeeRelPosition::getActiveHrDepartment();
        $query = $query->andWhere(['=', 'psrd.hr_department_id', $hr_department['hr_department_id']]);
        $query->andFilterWhere([
            'pnl.status_id' => $this->status_id,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['ilike', 'hd.name', $this->department]);
        $query->andFilterWhere(['ilike', 'pd.doc_number', $this->doc_number]);
        $query->andFilterWhere(['ilike', 'sh.name', $this->shift]);
        $query->andFilterWhere(['!=', 'pnl.status_id', BaseModel::STATUS_INACTIVE]);
        $query->orderBy(['pnl.status_id' => SORT_ASC]);
        return $dataProvider;
    }
}
