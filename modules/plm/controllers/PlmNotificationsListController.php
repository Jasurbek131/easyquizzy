<?php

namespace app\modules\plm\controllers;

use app\models\Users;
use app\modules\hr\models\HrEmployeeRelPosition;
use app\modules\hr\models\HrEmployeeRelUsers;
use app\modules\hr\models\UsersRelationHrDepartments;
use app\modules\plm\models\BaseModel;
use app\modules\plm\models\PlmNotificationMessage;
use app\modules\plm\models\PlmSectorRelHrDepartment;
use Faker\Provider\Base;
use Yii;
use app\modules\plm\models\PlmNotificationsList;
use app\modules\plm\models\PlmNotificationsListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PlmNotificationsListController implements the CRUD actions for PlmNotificationsList model.
 */
class PlmNotificationsListController extends Controller
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
     * Lists all PlmNotificationsList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlmNotificationsListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlmNotificationsList model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = PlmNotificationsList::getViews($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new PlmNotificationsList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PlmNotificationsList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PlmNotificationsList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PlmNotificationsList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionExportExcel(){
        header('Content-Type: application/vnd.ms-excel');
        $filename = "plm-notifications-list_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => PlmNotificationsList::find()->select([
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
     * Finds the PlmNotificationsList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlmNotificationsList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PlmNotificationsList::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionAccepted($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        $saved = false;
        try {   
            if($model->status_id < BaseModel::STATUS_ACCEPTED){
                $model->status_id = BaseModel::STATUS_ACCEPTED;
                if($model->save()){
                    $saved = true;
                }
                if($saved) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success',Yii::t("app","Checked successfully"));
                }else{
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error',Yii::t("app","Checked not successfully"));
                }
            }
        } catch (\Exception $e) {
            Yii::info('Not saved' . $e, 'save');
            $transaction->rollBack();
        }
        return $this->redirect(['view', 'id' => $model->id]);
    }


    public function actionAjaxRejected(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];
        $response['status'] = false;
        $response['status'] = "Not saved";
        $data = Yii::$app->request->post();
        if(!empty($data['message'])){
            $plmNotificationMessage = new PlmNotificationMessage();
            $plmNotificationMessage->setAttributes([
                'plm_notification_list_id' => $data['list_id'],
                'message' => $data['message'],
                'status_id' => BaseModel::STATUS_ACTIVE,
            ]);
            /**
              *  @var  $plmNotificationList PlmNotificationsList
             **/
            if($plmNotificationMessage->save()){
                $plmNotificationList = PlmNotificationsList::find()
                    ->where(['id' => $data['list_id'],'status_id' => BaseModel::STATUS_ACTIVE])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit(1)
                    ->one();
                if(!empty($plmNotificationList)){
                    $plmNotificationList->status_id = BaseModel::STATUS_REJECTED; // rad etilgan list
                    if($plmNotificationList->save()){
                        $response['status'] = true;
                        $response['message'] = Yii::t('app', 'Checked successfully');
                        return $response;
                    }
                }
            }else{
                $response['status'] = false;
                $response['message'] = Yii::t('app', 'Checked not successfully');
                return $response;
            }
        }else{
            $response['status'] = false;
            $response['message'] = Yii::t('app', 'Reason is not empty');
            return $response;
        }
        return $response;
    }
}