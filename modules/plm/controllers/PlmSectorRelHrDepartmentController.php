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
class PlmSectorRelHrDepartmentController extends Controller
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

        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $response = $model->saveRelHrDepartment();
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
        $model = $this->findModel($id)->getCategoriesIdListByDepartment();
        $model->scenario = PlmSectorRelHrDepartment::SCENARIO_CREATE;
        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $model->isUpdate = true;
                $response = $model->saveRelHrDepartment();
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
