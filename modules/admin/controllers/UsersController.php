<?php

namespace app\modules\admin\controllers;

use app\models\BaseModel;
use app\modules\admin\models\AuthAssignment;
use app\modules\admin\models\AuthItem;
use Yii;
use app\models\Users;
use app\modules\admin\models\search\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends BaseController
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users([
            'scenario' => Users::SCENARIO_CREATE
        ]);

        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $response = $model->saveUser();
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

        $roles = AuthItem::getRoles(true);
        $role_types = AuthItem::getRoleType();
        if ($request->isAjax)
            return $this->renderAjax('create', [
                'model' => $model,
                'roles' => $roles,
                'role_types' => $role_types,
            ]);

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
            'role_types' => $role_types,
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->hr_employee_id = !empty($model->hrEmployees) ? $model->hrEmployees[0]["hr_employee_id"] : "";
        $model->roles = AuthAssignment::getUserRoles($id);
        $model->password = "";
        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $model->isUpdate = true;
                $response = $model->saveUser();
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

        $roles = AuthItem::getRoles(true);
        $role_types = AuthItem::getRoleType();

        if ($request->isAjax)
            return $this->renderAjax('update', [
                'model' => $model,
                'roles' => $roles,
                'role_types' => $role_types,
            ]);

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
            'role_types' => $role_types,
        ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isDeleted = true;
        $model = $this->findModel($id);
        $model->hr_employee_id = !empty($model->hrEmployees) ? $model->hrEmployees[0]["hr_employee_id"] : "";
        try {
            $model->status_id = BaseModel::STATUS_INACTIVE;
            if(!$model->save())
                $isDeleted = false;

            if($isDeleted)
                $transaction->commit();
            else
                $transaction->rollBack();

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
        }else{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "users_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => Users::find()->select([
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
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
