<?php

namespace app\modules\references\controllers;

use app\models\BaseModel;
use app\modules\references\models\EquipmentGroupRelationEquipment;
use Yii;
use app\modules\references\models\EquipmentGroup;
use app\modules\references\models\EquipmentGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EquipmentGroupController implements the CRUD actions for EquipmentGroup model.
 */
class EquipmentGroupController extends BaseController
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
     * Lists all EquipmentGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EquipmentGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EquipmentGroup model.
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
     * Creates a new EquipmentGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EquipmentGroup();
        $models = [new EquipmentGroupRelationEquipment()];
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        $saved = true;
                        $eqGroupRelEq = Yii::$app->request->post('EquipmentGroupRelationEquipment');
                        if (!empty($eqGroupRelEq)) {
                            $i = 1;
                            foreach ($eqGroupRelEq as $item) {
                                $newEqGrRelEq = new EquipmentGroupRelationEquipment();
                                $newEqGrRelEq->setAttributes([
                                    'equipment_group_id' => $model->id,
                                    'equipment_id' => $item['equipment_id'],
                                    'work_order' => $i++,
                                    'status_id' => BaseModel::STATUS_ACTIVE
                                ]);
                                if ($newEqGrRelEq->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    $response['errors'] = $newEqGrRelEq->getErrors();
                                    break;
                                }
                            }
                        } else {
                            $saved = false;
                        }
                    } else {
                        $saved = false;
                        $response['errors'] = $model->getErrors();
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
                        $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
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
                'models' => $models
            ]);
        }
        return $this->render('create', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = EquipmentGroupRelationEquipment::find()
            ->where(['equipment_group_id' => $model->id])->all();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                $saved = false;
                try {
                    if($model->save()){
                        $saved = true;
                        $eqGroupRelEq = Yii::$app->request->post('EquipmentGroupRelationEquipment');
                        EquipmentGroupRelationEquipment::deleteAll(['equipment_group_id' => $model->id]);
                        if (!empty($eqGroupRelEq)) {
                            $i = 1;
                            foreach ($eqGroupRelEq as $item) {
                                $updateEqGrRelEq = new EquipmentGroupRelationEquipment();
                                $updateEqGrRelEq->setAttributes([
                                    'equipment_group_id' => $model->id,
                                    'equipment_id' => $item['equipment_id'],
                                    'work_order' => $i++,
                                    'status_id' => BaseModel::STATUS_ACTIVE
                                ]);
                                if ($updateEqGrRelEq->save()) {
                                    $saved = true;
                                } else {
                                    $saved = false;
                                    $response['errors'] = $updateEqGrRelEq->getErrors();
                                    break;
                                }
                            }
                        } else {
                            $saved = false;
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
                        $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
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
                'models' => $models
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'models' => $models
        ]);
    }

    /**
     * @param $id
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $isDeleted = false;
        $model = $this->findModel($id);
        try {
            $model->status_id = BaseModel::STATUS_INACTIVE;
            if($model->save()){
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

    /**
     * @param $id
     * @return EquipmentGroup|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = EquipmentGroup::findOne((integer)$id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @return string
     */
    public function actionNewCreate()
    {
        return $this->render('new_create');
    }

    /**
     * @return string
     */
    public function actionNewUpdate()
    {
        return $this->render('new_update');
    }
}
