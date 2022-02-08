<?php

namespace app\modules\hr\controllers;

use app\models\BaseModel;
use app\modules\hr\models\HrDepartmentRelDefects;
use app\modules\hr\models\HrDepartmentRelEquipment;
use app\modules\hr\models\HrDepartmentRelProduct;
use app\modules\hr\models\HrDepartmentRelShifts;
use app\modules\hr\models\HrEmployeeRelUsers;
use app\modules\references\models\Shifts;
use kartik\tree\controllers\NodeController;
use kartik\tree\models\Tree;
use kartik\tree\TreeSecurity;
use kartik\tree\TreeView;
use Yii;
use app\modules\hr\models\HrDepartments;
use app\modules\hr\models\HrDepartmentsSearch;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * HrDepartmentsController implements the CRUD actions for HrDepartments model.
 */
class HrDepartmentsController extends NodeController
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
     * Lists all HrDepartments models.
     * @return mixed
     */
    /*public function actionIndex()
    {
        return $this->render('index', [
            'query' => HrDepartments::find()->addOrderBy('root, lft')
        ]);
    }*/

    /**
     * @return \yii\web\Response
     * @throws ErrorException
     * @throws InvalidConfigException
     */
    public function actionSave()
    {
        $post = Yii::$app->request->post();
        static::checkValidRequest(false, !isset($post['treeNodeModify']));
        $data = static::getPostData();
        $parentKey = ArrayHelper::getValue($data, 'parentKey', null);
        $treeNodeModify = ArrayHelper::getValue($data, 'treeNodeModify', null);
        $currUrl = ArrayHelper::getValue($data, 'currUrl', '');
        $treeClass = TreeSecurity::getModelClass($data);
        $module = TreeView::module();
        $keyAttr = $module->dataStructure['keyAttribute'];
        $nodeTitles = TreeSecurity::getNodeTitles($data);

        if ($treeNodeModify) {
            $node = new $treeClass;
            $successMsg = Yii::t('app', 'The {node} was successfully created.', $nodeTitles);
            $errorMsg = Yii::t('app', 'Error while creating the {node}. Please try again later.', $nodeTitles);
        } else {
            $tag = explode("\\", $treeClass);
            $tag = array_pop($tag);
            $id = $post[$tag][$keyAttr];
            $node = $treeClass::findOne($id);
            $successMsg = Yii::t('app', 'Saved the {node} details successfully.', $nodeTitles);
            $errorMsg = Yii::t('app', 'Error while saving the {node}. Please try again later.', $nodeTitles);
        }
        $node->activeOrig = $node->active;
        $isNewRecord = $node->isNewRecord;
        $node->load($post);
        $errors = $success = false;
        if (Yii::$app->has('session')) {
            $session = Yii::$app->session;
        }
        if ($treeNodeModify) {
            if ($parentKey == TreeView::ROOT_KEY) {
                $node->makeRoot();
            } else {
                $parent = $treeClass::findOne($parentKey);
                if ($parent->isChildAllowed()) {
                    $node->appendTo($parent);
                } else {
                    $errorMsg = Yii::t('app', 'You cannot add children under this {node}.', $nodeTitles);
                    if (Yii::$app->has('session')) {
                        $session->setFlash('error', $errorMsg);
                    } else {
                        throw new ErrorException("Error saving {node}!\n{$errorMsg}", $nodeTitles);
                    }
                    return $this->redirect($currUrl);
                }
            }
        }
        if ($node->save()) {
            // check if active status was changed
            if (!$isNewRecord && $node->activeOrig != $node->active) {
                if ($node->active) {
                    $success = $node->activateNode(false);
                    $errors = $node->nodeActivationErrors;
                } else {
                    $success = $node->removeNode(true, false); // only deactivate the node(s)
                    $errors = $node->nodeRemovalErrors;
                }
            } else {
                $success = true;
            }
            if (!empty($errors)) {
                $success = false;
                $errorMsg = "<ul style='padding:0'>\n";
                foreach ($errors as $err) {
                    $errorMsg .= "<li>" . Yii::t('app', "{node} # {id} - '{name}': {error}",
                            $err + $nodeTitles) . "</li>\n";
                }
                $errorMsg .= "</ul>";
            }
        } else {
            $errorMsg = '<ul style="margin:0"><li>' . implode('</li><li>', $node->getFirstErrors()) . '</li></ul>';
        }
        if (Yii::$app->has('session')) {
            $session->set(ArrayHelper::getValue($post, 'nodeSelected', 'kvNodeId'), $node->{$keyAttr});
            if ($success) {
                $session->setFlash('success', $successMsg);
            } else {
                $session->setFlash('error', $errorMsg);
            }
        } elseif (!$success) {
            throw new ErrorException("Error saving {node}!\n{$errorMsg}", $nodeTitles);
        }
        return $this->redirect($currUrl);
    }

    /**
     * Displays a single HrDepartments model.
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
     * Creates a new HrDepartments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->get();
        if(!Yii::$app->request->isAjax)
            return $this->redirect('index');
        $model = new HrDepartments();
        
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                $model->parent_id = $data['department_id'] ?? null;
                if ($model->save()) {
                    $response['status'] = 0;
                } else {
                    $response['status'] = 1;
                    $response['errors'] = $model->getErrors();
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $response;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        if (Yii::$app->request->isAjax) {
            $model->parent_id = $data['department_id'] ?? null;
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing HrDepartments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($department_id)
    {
        $model = $this->findModel($department_id);
        if (Yii::$app->request->isPost) {
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
                        $response['model'] = ['name' => $model->name, 'id' => $model->id];
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
     * Deletes an existing HrDepartments model.
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
        $filename = "hr-departments_".date("d-m-Y-His").".xls";
        header('Content-Disposition: attachment;filename='.$filename .' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => HrDepartments::find()->select([
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
     * Finds the HrDepartments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HrDepartments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HrDepartments::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionIndex($deb = null)
    {
        $tree = HrDepartments::getTreeViewHtmlForm();
        return $this->render('dep-index',[
            'tree' => $tree,
            'deb' => $deb,
        ]);
    }

    public function actionGetItemsAjax(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->get('id');
        $response = [];
        if (!empty($id)){
            $child = HrDepartments::find()
                ->where(['parent_id' => $id])
                ->andWhere(['status_id' => BaseModel::STATUS_ACTIVE])
                ->all();
            $shifts = HrDepartmentRelShifts::getHrRelShift($id);
            $equipments = HrDepartmentRelEquipment::getHrRelEquipment($id);
            $products = HrDepartmentRelProduct::getHrRelProduct($id);
            $defects = HrDepartmentRelDefects::getHrRelDefect($id);
            $response['shifts'] = $shifts;
            $response['equipments'] = $equipments;
            $response['products'] = $products;
            $response['defects'] = $defects;
            $response['delete'] = false;
            if (!empty($child)) {
                $response['delete'] = true;
            }
            return $response;
        }

    }

}
