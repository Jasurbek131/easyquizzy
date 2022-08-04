<?php

namespace app\modules\hr\controllers;

use app\models\BaseModel;
use Yii;
use app\modules\hr\models\HrDepartmentRelShifts;
use app\modules\hr\models\search\HrDepartmentRelShiftsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * HrDepartmentRelShiftsController implements the CRUD actions for HrDepartmentRelShifts model.
 */
class HrDepartmentRelShiftsController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
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
     * Lists all HrDepartmentRelShifts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HrDepartmentRelShiftsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HrDepartmentRelShifts model.
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
     * Creates a new HrDepartmentRelShifts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HrDepartmentRelShifts();
        $data = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        if (Yii::$app->request->isPost) {
            $exists = HrDepartmentRelShifts::findOne([
                "hr_department_id" => $data['department_id'],
                "shift_id" => $post["HrDepartmentRelShifts"]["shift_id"],
            ]);
            if ($exists){
                $model = clone $exists;
                $model->status_id = BaseModel::STATUS_ACTIVE;
            }
            if ($model->load($post)) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    $model->hr_department_id = ($data['department_id']) ? ($data['department_id']) : null;
                    if($model->save()){
                        $saved = true;
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
                        $response['hr_department_id'] = $model->hr_department_id;
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
            ]);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HrDepartmentRelShifts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param  $department_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$department_id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isPost) {
            $exists = HrDepartmentRelShifts::find()->where([
                "hr_department_id" => $data['department_id'],
                "shift_id" => $post["HrDepartmentRelShifts"]["shift_id"],
            ])
            ->andWhere(["<>","id", $id])
            ->limit(1)
            ->one();
            if ($exists){
                $model = $exists;
                $model->status_id = BaseModel::STATUS_ACTIVE;
            }
            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        $saved = true;
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
                        $response['hr_department_id'] = $model->hr_department_id;
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
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing HrDepartmentRelShifts model.
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
     * Finds the HrDepartmentRelShifts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HrDepartmentRelShifts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrDepartmentRelShifts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
