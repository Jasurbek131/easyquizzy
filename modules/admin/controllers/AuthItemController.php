<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\AuthItemChild;
use app\modules\admin\models\AuthItemSearch;
use Yii;
use app\modules\admin\models\AuthItem;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AuthItemsController implements the CRUD actions for AuthItem model.
 */
class AuthItemController extends Controller
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
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['type' => 1]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPermissions()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['type' => 2]);
        return $this->render('permissions', [
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
     * @param null $permission
     * @return array|string|Response
     * @throws yii\base\Exception
     */
    public function actionCreate($permission = null)
    {
        $model = new AuthItem();
        if (!$permission) {
            $models = AuthItem::find()->where(['type' => 2])->all();
            $perms = ArrayHelper::map($models, 'name', 'name', 'category');
            $model->type = 1;
        } else {
            $model->type = 2;
            $models = [new AuthItem()];
            $model->new_permissions = [
                [
                    'name' => 'index',
                    'description' => 'Index',
                ],
                [
                    'name' => 'create',
                    'description' => 'Create',
                ],
                [
                    'name' => 'update',
                    'description' => 'Update',
                ],
                [
                    'name' => 'delete',
                    'description' => 'Delete',
                ],
                [
                    'name' => 'view',
                    'description' => 'View',
                ],
            ];
        }
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $response = [];
                if (!$permission) {
                    if ($model->save()) {
                        $perms = Yii::$app->request->post()['AuthItem']['perms'];
//                        $parents = Yii::$app->request->post()['AuthItem']['parents'];
                        if ($perms[0] == 1) {
                            ArrayHelper::remove($perms, 0);
                            foreach ($perms as $key => $value) {
                                if ((int)$value == 1) {
                                    $auth = Yii::$app->authManager;
                                    $role = Yii::$app->authManager->getRole($model->name);
                                    $perm = Yii::$app->authManager->getPermission($key);
                                    $auth->addChild($role, $perm);
                                }
                            }
                        }
//                        if (!empty($parents)) {
//                            foreach ($parents as $key => $value) {
//                                $auth = Yii::$app->authManager;
//                                $sub_role = Yii::$app->authManager->getRole($value);
//                                $top_role = Yii::$app->authManager->getRole($model->name);
//                                $auth->addChild($top_role, $sub_role);
//                            }
//                        }
                        $response['status'] = 0;
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                    }
                } else {
                    $data = Yii::$app->request->post();
                    if (!empty($data['AuthItem']['new_permissions'])) {
                        foreach ($data['AuthItem']['new_permissions'] as $item) {
                            $dataAI = [];
                            $m = new AuthItem();
                            $dataAI['AuthItem']['name'] = $data['AuthItem']['name'] . '/' . $item['name'];
                            $dataAI['AuthItem']['category'] = $data['AuthItem']['category'];
                            $dataAI['AuthItem']['description'] = $item['description'];
                            $dataAI['AuthItem']['type'] = 2;
                            if ($m->load($dataAI) && $m->save()) {
                                $auth = Yii::$app->authManager;
                                $role = Yii::$app->authManager->getRole($m->category);
                                $permission = Yii::$app->authManager->getPermission($m->name);
                                $auth->addChild($role, $permission);
                            }
                        }
                    }
                    $response['status'] = 0;
                }
                return $response;
            }
            if ($model->save()) {
                $perms = Yii::$app->request->post()['AuthItem']['perms'];
//                $parents = Yii::$app->request->post()['AuthItem']['parents'];
                if ($perms[0] == 1) {
                    ArrayHelper::remove($perms, 0);
                    foreach ($perms as $key => $value) {
                        if ((int)$value == 1) {
                            $auth = Yii::$app->authManager;
                            $role = Yii::$app->authManager->getRole($model->name);
                            $perm = Yii::$app->authManager->getPermission($key);
                            $auth->addChild($role, $perm);
                        }
                    }
                }
//                if (!empty($parents)) {
//                    foreach ($parents as $key => $value) {
//                        $auth = Yii::$app->authManager;
//                        $sub_role = Yii::$app->authManager->getRole($value);
//                        $top_role = Yii::$app->authManager->getRole($model->name);
//                        $auth->addChild($top_role, $sub_role);
//                    }
//                }
                return $this->redirect(['view', 'id' => $model->name]);
            }
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
                'perms' => (!$permission) ? $perms : null,
                'permission' => $permission,
            ]);
        }
        return $this->render('create', [
            'model' => $model,
            'perms' => (!$permission) ? $perms : null,
            'permission' => $permission,
        ]);
    }

    public function actionCreatePermission()
    {
        $model = new AuthItem();
        $model->type = 2;
        $models = [new AuthItem()];
        $model->new_permissions = [
            [
                'name' => 'index',
                'description' => 'Index',
            ],
            [
                'name' => 'create',
                'description' => 'Create',
            ],
            [
                'name' => 'update',
                'description' => 'Update',
            ],
            [
                'name' => 'delete',
                'description' => 'Delete',
            ],
            [
                'name' => 'view',
                'description' => 'View',
            ],
        ];
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if (!empty($data['AuthItem']['new_permissions'])) {
                foreach ($data['AuthItem']['new_permissions'] as $item) {
                    $dataAI = [];
                    $m = new AuthItem();
                    $dataAI['AuthItem']['name'] = $data['AuthItem']['name'] . '/' . $item['name'];
                    $dataAI['AuthItem']['category'] = $data['AuthItem']['category'];
                    $dataAI['AuthItem']['description'] = $item['description'];
                    $dataAI['AuthItem']['type'] = 2;

                    if ($m->load($dataAI) && $m->save()) {
                        $auth = Yii::$app->authManager;
                        $role = Yii::$app->authManager->getRole($m->category);
                        $permission = Yii::$app->authManager->getPermission($m->name);
                        $auth->addChild($role, $permission);
                    }
                }
            }
            return $this->redirect(['permissions']);
        }
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            $auth = Yii::$app->authManager;
//            $role = Yii::$app->authManager->getRole($model->category);
//            $permission = Yii::$app->authManager->getPermission($model->name);
//            $auth->addChild($role,$permission);
//
//            return $this->redirect(['view', 'id' => $model->name]);
//        }

        return $this->render('create_permission', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @throws yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $models = AuthItem::find()->where(['type' => 2])->all();
        $perms = ArrayHelper::map($models, 'name', 'name', 'category');
        $parents = AuthItem::find()->select('name')->where(['type' => '1'])->andWhere(['!=', 'name', $model->name])->asArray()->all();
        if ($model->load(Yii::$app->request->post())) {
            try {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($model->save()) {
                        if ($model->type !== 2) {
                            $auth = Yii::$app->authManager;
                            $perms = Yii::$app->request->post()['AuthItem']['perms'];
                            $parents = Yii::$app->request->post()['AuthItem']['parents'];

                            $this->deletePerms($model->name);
                            ArrayHelper::remove($perms, 0);

                            foreach ($perms as $key => $value) {
                                if ((int)$value == 1) {

                                    $auth = Yii::$app->authManager;
                                    $role = Yii::$app->authManager->getRole($model->name);
                                    $perm = Yii::$app->authManager->getPermission($key);
                                    $auth->addChild($role, $perm);
                                }
                            }

//                            $this->deleteParents($model->name);
//                            if (!empty($parents)) {
//                                foreach ($parents as $key => $value) {
//                                    $auth = Yii::$app->authManager;
//                                    $sub_role = Yii::$app->authManager->getRole($value);
//                                    $top_role = Yii::$app->authManager->getRole($model->name);
//                                    $auth->addChild($top_role, $sub_role);
//                                }
//                            }

                        }

                        if ($model->type == 2) {
                            $auth = Yii::$app->authManager;
                            $this->deletePermission($model->name);
                            $role = Yii::$app->authManager->getRole($model->category);
                            $permission = Yii::$app->authManager->getPermission($model->name);
                            $auth->addChild($role, $permission);
                        }
                        $response['status'] = 0;
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                    }
                    return $response;
                }
                if ($model->save()) {
                    if ($model->type !== 2) {
                        $auth = Yii::$app->authManager;
                        $perms = Yii::$app->request->post()['AuthItem']['perms'];
                        $parents = Yii::$app->request->post()['AuthItem']['parents'];

                        $this->deletePerms($model->name);
                        if ($perms[0] == 1) {
                            ArrayHelper::remove($perms, 0);

                            foreach ($perms as $key => $value) {
                                if ((int)$value == 1) {

                                    $auth = Yii::$app->authManager;
                                    $role = Yii::$app->authManager->getRole($model->name);
                                    $perm = Yii::$app->authManager->getPermission($key);
                                    $auth->addChild($role, $perm);
                                }
                            }
                        }

//                        $this->deleteParents($model->name);
//                        if (!empty($parents)) {
//                            foreach ($parents as $key => $value) {
//                                $auth = Yii::$app->authManager;
//                                $sub_role = Yii::$app->authManager->getRole($value);
//                                $top_role = Yii::$app->authManager->getRole($model->name);
//                                $auth->addChild($top_role, $sub_role);
//                            }
//                        }

                    }

                    if ($model->type == 2) {
                        $auth = Yii::$app->authManager;
                        $this->deletePermission($model->name);
                        $role = Yii::$app->authManager->getRole($model->category);
                        $permission = Yii::$app->authManager->getPermission($model->name);
                        $auth->addChild($role, $permission);
                    }
                    return $this->redirect(['view', 'id' => $model->name]);
                }
            } catch (\Exception $e) {
                \yii\helpers\VarDumper::dump($e, 10, true);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'perms' => $perms,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
            'perms' => $perms,
        ]);
    }

    public function actionChangeChild($id)
    {
        $model = $this->findModel($id);
        $oldValue = $model->getParenList($model->name, false);
        if ($model->load(Yii::$app->request->post())) {
            try {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    $response = [];
                    if ($model->save()) {
                        $parents = Yii::$app->request->post()['AuthItem']['parents'];
                        if (!empty($parents)) {
                            $this->saveChildRoll($oldValue, $parents, $model);
                        }
                        $response['status'] = 0;
                    } else {
                        $response['status'] = 1;
                        $response['errors'] = $model->getErrors();
                    }
                    return $response;
                }
                if ($model->save()) {
                    $parents = Yii::$app->request->post()['AuthItem']['parents'];
                    if (!empty($parents)) {
                        $this->saveChildRoll($oldValue, $parents, $model);
                    }else{
                        AuthItemChild::deleteAll(['parent' => $model->name]);
                    }
                    return $this->redirect(['view', 'id' => $model->name]);
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error',$e->getMessage());
            }
        }
        return $this->render('change-child', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return void|Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax) {
            if ($this->findModel($id)->delete()) {
                echo "success";
            } else {
                echo "fail";
            }
            exit();
        }
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionExportExcel()
    {
        header('Content-Type: application/vnd.ms-excel');
        $filename = "auth-items_" . date("d-m-Y-His") . ".xls";
        header('Content-Disposition: attachment;filename=' . $filename . ' ');
        header('Cache-Control: max-age=0');
        \moonland\phpexcel\Excel::export([
            'models' => AuthItem::find()->select([
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
     * @param $id
     * @return AuthItem|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * @param $parent
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    protected function deletePerms($parent)
    {

        foreach (AuthItemChild::find()
                     ->where(['parent' => $parent])
                     ->andWhere(['like', 'child', '%/%', false])
                     ->all() as $child) {
            $child->delete();
        }
    }

    /**
     * @param $parent
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    protected function deleteParents($parent)
    {

        foreach (AuthItemChild::find()
                     ->where(['parent' => $parent])
                     ->andWhere(['not like', 'child', '%/%', false])
                     ->all() as $child) {
            $child->delete();
        }
    }

    /**
     * @param $perm
     * @throws \Throwable
     * @throws yii\db\StaleObjectException
     */
    protected function deletePermission($perm)
    {
        $model = AuthItemChild::find()->where(['child' => $perm])->one();
        if(!empty($model))
            $model->delete();
    }

    /**
     * @param array $oldValue
     * @param $parents
     * @param AuthItem|null $model
     * @throws \yii\base\Exception
     */
    public function saveChildRoll(array $oldValue, $parents, ?AuthItem $model): void
    {
        foreach ($oldValue as $key => $item) {
            if ($parents[$key] != 'on') {
                AuthItemChild::deleteAll(['parent' => $item['parent'], 'child' => $item['child']]);
            }
        }
        foreach ($parents as $key => $item) {
            if (empty($oldValue[$key])) {
                $auth = Yii::$app->authManager;
                $sub_role = Yii::$app->authManager->getRole($key);
                $top_role = Yii::$app->authManager->getRole($model->name);
                $auth->addChild($top_role, $sub_role);
            }
        }
    }
}
