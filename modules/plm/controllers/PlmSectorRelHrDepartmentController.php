<?php

namespace app\modules\plm\controllers;

use app\modules\hr\models\UsersRelationHrDepartments;
use Yii;
use app\modules\plm\models\PlmSectorRelHrDepartment;
use app\modules\plm\models\PlmSectorRelHrDepartmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PlmSectorRelHrDepartmentController implements the CRUD actions for PlmSectorRelHrDepartment model.
 */
class PlmSectorRelHrDepartmentController extends BaseController
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
     * Lists all PlmSectorRelHrDepartment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlmSectorRelHrDepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlmSectorRelHrDepartment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PlmSectorRelHrDepartment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlmSectorRelHrDepartment([
            "scenario" => PlmSectorRelHrDepartment::SCENARIO_CREATE
        ]);

        $data = Yii::$app->request->get();
        $request = Yii::$app->request;
        $model->hr_department_id = $data['department_id'];
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $response = $model->setType(PlmSectorRelHrDepartment::CONFIRM_TYPE)->saveRelHrDepartment();
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if ($response['status']) {
                        $response['status'] = 0;
                        $response['hr_department_id'] = $model->hr_department_id;
                    }else
                        $response['status'] = 1;

                    return $response;
                }

                if ($response['status'])
                    return $this->redirect(['view', 'id' => $model->id]);

            }
        }

        if ($request->isAjax)
            return $this->renderAjax('create', [
                'model' => $model,
            ]);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PlmSectorRelHrDepartment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id)
            ->setType(PlmSectorRelHrDepartment::CONFIRM_TYPE)
            ->getCategoriesIdListByDepartment();

        $model->scenario = PlmSectorRelHrDepartment::SCENARIO_CREATE;
        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $model->isUpdate = true;
                $response = $model->setType(PlmSectorRelHrDepartment::CONFIRM_TYPE)->saveRelHrDepartment();
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if ($response['status']) {
                        $response['status'] = 0;
                        $response['hr_department_id'] = $model->hr_department_id;
                    }else
                        $response['status'] = 1;

                    return $response;
                }

                if ($response['status'])
                    return $this->redirect(['view', 'id' => $model->id]);

            }
        }

        if ($request->isAjax)
            return $this->renderAjax('create', [
                'model' => $model,
            ]);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PlmSectorRelHrDepartment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $isDeleted = true;
        try {
            if ($id)
                PlmSectorRelHrDepartment::deleteAll([
                    "hr_department_id" => $id,
                    "type" => PlmSectorRelHrDepartment::CONFIRM_TYPE
                ]);

        }catch (\Exception $e){
            Yii::info('Not saved' . $e, 'save');
            $isDeleted = false;
        }
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            if($isDeleted){
                $response['status'] = 0;
                $response['message'] = Yii::t('app','Deleted Successfully');
            }
            return $response;
        }
        if($isDeleted){
            Yii::$app->session->setFlash('success',Yii::t('app','Deleted Successfully'));
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the PlmSectorRelHrDepartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlmSectorRelHrDepartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlmSectorRelHrDepartment::findOne(["hr_department_id" => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
