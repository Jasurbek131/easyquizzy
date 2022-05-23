<?php

namespace app\modules\hr\controllers;

use app\modules\hr\models\HrEmployeeRelPosition;
use Exception;
use Yii;
use app\modules\hr\models\HrEmployee;
use app\modules\hr\models\HrEmployeeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * HrEmployeeController implements the CRUD actions for HrEmployee model.
 */
class HrEmployeeController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws Exception
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
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $hrEmployeeRel = HrEmployee::getEmployeeData($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
                'hrEmployeeRel' => $hrEmployeeRel ?? []
            ]);
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'hrEmployeeRel' => $hrEmployeeRel,
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
        $request = Yii::$app->request;

        if ($request->isPost) {
            if ($model->load($request->post())) {

                $response = $model->saveEmployee();
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if ($response['status'])
                        $response['status'] = 0;
                    else
                        $response['status'] = 1;

                    return $response;
                }

                if ($response['status'])
                    return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('create', [
                'model' => $model,
            ]);

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
        $position = $model->hrEmployeeActivePosition;
        if($position){
            $model->hr_department_id = $position->hr_department_id ?? "";
            $model->hr_position_id = $position->hr_position_id ?? "";
            $model->hr_organisation_id = $position->hr_organisation_id ?? "";
            $model->begin_date = $position->begin_date ? date('d.m.Y', strtotime($position->begin_date)) : "";
        }

        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $model->isUpdate = true;
                $response = $model->saveEmployee();
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if ($response['status'])
                        $response['status'] = 0;
                    else
                        $response['status'] = 1;

                    return $response;
                }

                if ($response['status'])
                    return $this->redirect(['view', 'id' => $model->id]);

            }
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('update', [
                'model' => $model,
            ]);

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
        }catch (Exception $e){
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

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetEmployeeData($id)
    {
        $request = Yii::$app->request;

        if ($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;

            $response = ['status' => false];
            $employee = $this->findModel($id);
            if(!empty($employee)){

                $data = $employee->hrEmployeeActivePosition;
                $response = [
                    'status' => true,
                    'employee' => [
                        "organisations_name" => $data->hrOrganisations->name,
                        "department_name" => $data->hrDepartments->name,
                        "phone_number" => $employee->phone_number,
                        "email" => $employee->email,
                    ]
                ];
            }

            return  $response;
        }

    }
}
