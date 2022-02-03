<?php

namespace app\modules\hr\controllers;

use app\models\BaseModel;
use app\modules\hr\models\HrEmployeeRelPosition;
use app\modules\mobile\models\MobileTablesRelHrEmployee;
use app\widgets\helpers\Telegram;
use Yii;
use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrEmployeeSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use function Symfony\Component\String\s;

/**
 * HrEmployeeController implements the CRUD actions for HrEmployee model.
 */
class HrEmployeeController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all HrEmployee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HrEmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HrEmployee model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $hrEmployeeRel = HrEmployeeRelPosition::find()
            ->alias('hrerp')
            ->select([
                'hrd.name AS department_name',
                'hrp.name_uz AS position_name',
                'hrerp.begin_date AS begin_date',
                'hrerp.end_date AS end_date',
                'sl.name_uz status_name',
                'sl.id status'
            ])
            ->leftJoin(['hrd'=>'hr_departments'],'hrerp.hr_department_id = hrd.id')
            ->leftJoin(['hrp'=>'hr_positions'],'hrerp.hr_position_id = hrp.id')
            ->leftJoin(['sl' => 'status_list'],'hrerp.status_id = sl.id')
            ->where(['hr_employee_id' => $id])
            ->asArray()
            ->all();
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
                'hrEmployeeRel' => $hrEmployeeRel ?? []
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new HrEmployee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HrEmployee();
        $hrEmployeeRelPosition = [new HrEmployeeRelPosition(['scenario' => HrEmployeeRelPosition::SCENARIO_CREATE])];
        if (Yii::$app->request->isPost) {
            /** begin load multiple input */
            $responsibleHrEmp = Yii::$app->request->post('HrEmployeeRelPosition', []);
            foreach (array_keys($responsibleHrEmp) as $index) {
                $hrEmployeeRelPosition[$index] = new HrEmployeeRelPosition(['scenario' => HrEmployeeRelPosition::SCENARIO_CREATE]);
            }
            /** end load multiple input */
            if ($model->load(Yii::$app->request->post()) && Model::loadMultiple($hrEmployeeRelPosition, Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        foreach ($hrEmployeeRelPosition as $item){
                            $item->setAttributes([
                                'hr_employee_id' => $model->id,
                            ]);
                            if($item->save()){
                                $saved = true;
                            }
                        }
                    }else{
                        $saved = false;
                    }

                    if($saved) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($saved) {
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        $response['message'] = Yii::t('app', 'Ma\'lumotlar yetarli emas!');
                    }
                    return $response;
                }
                if ($saved) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
                'hrEmployeeRelPosition' => $hrEmployeeRelPosition,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HrEmployee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @var $model HrEmployeeRelPosition
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $hrEmployeeRelPosition = $model->hrEmployeeRelPosition;
        /** begin load multiple input */
        $responsibleHrEmp = Yii::$app->request->post('HrEmployeeRelPosition', []);
        foreach (array_keys($responsibleHrEmp) as $index) {
            $hrEmployeeRelPosition[$index] = new HrEmployeeRelPosition();
        }
        /** end load multiple input */
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && Model::loadMultiple($hrEmployeeRelPosition, Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        foreach ($hrEmployeeRelPosition as $item){
                            if(!empty($item->end_date)){
                                $oldHrRelPosition = HrEmployeeRelPosition::findOne([
                                    'hr_employee_id' => $model->id,
                                    'status_id' => BaseModel::STATUS_ACTIVE
                                ]);
                                if(!empty($oldHrRelPosition)){
                                        $oldHrRelPosition->status_id = BaseModel::STATUS_INACTIVE;
                                        $oldHrRelPosition->end_date = $item->end_date;
                                    if($oldHrRelPosition->save()){
                                        $saved = true;
                                    }else{
                                        $user = Yii::$app->user->identity;
                                        Telegram::sendMultiple([Telegram::FAYZULLO],
                                            "#hr_employee_rel_position ni update qilshda xatolik \n" .
                                            "Error: <code>".json_encode($oldHrRelPosition->getErrors(), JSON_PRETTY_PRINT)."</code> \n" .
                                            "Line: <code>".__LINE__."</code> \n" .
                                            "Controller: <code>".__CLASS__."</code> \n" .
                                            "User: <code><b>".$user->username."</b></code> \n",
                                            [Telegram::FAYZULLO]
                                        );
                                    }
                                }
                            }else{
                                if(empty($item->hr_employee_id)){
                                    $item->setAttributes([
                                        'hr_employee_id' => $model->id,
                                    ]);
                                    if($item->save()){
                                        $saved = true;
                                    }
                                }else{
                                    $saved = true;
                                }
                            }
                        }
                    }else{
                        $saved = false;
                    }
                    if($saved) {
                        $transaction->commit();
                    }else{
                        $transaction->rollBack();
                    }
                } catch (\Exception $e) {
                    \yii\helpers\VarDumper::dump($e->getMessage(),10,true);die();
                    Yii::info('Not saved' . $e, 'save');
                    $transaction->rollBack();
                }
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($saved) {
                        $response['status'] = 0;
                        $response['message'] = Yii::t('app', 'Saved Successfully');
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                        $response['message'] = Yii::t('app', 'Ma\'lumotlar yetarli emas!');
                    }
                    return $response;
                }
                if ($saved) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'hrEmployeeRelPosition' => $hrEmployeeRelPosition,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HrEmployee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isDeleted = false;
        $model = $this->findModel($id);
        try {
            if($model->delete()){
                $isDeleted = true;
            }
            if($isDeleted){
                $transaction->commit();
            }else{
                $transaction->rollBack();
            }
        }catch (\Exception $e){
            Yii::info('Not saved' . $e, 'save');
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = Yii::t('app', 'Ma\'lumotlar yetarli emas!');
            if($isDeleted){
                $response['status'] = 0;
                $response['message'] = Yii::t('app','Deleted Successfully');
            }
            return $response;
        }
        if($isDeleted){
            Yii::$app->session->setFlash('success',Yii::t('app','Deleted Successfully'));
            return $this->redirect(['index']);
        }else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Ma\'lumotlar yetarli emas!'));
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "hr-employee_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => HrEmployee::find()->select([
                'id',
            ])->all(),
            'columns' => [
                'id',
            ],
            'headers' => [
                'id' => 'Id',
            ],
            'autoSize' => true,
        ]);
    }
    /**
     * Finds the HrEmployee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HrEmployee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrEmployee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
