<?php

namespace app\modules\references\controllers;

use app\models\BaseModel;
use app\modules\references\models\ReferencesProductGroupSearch;
use Yii;
use app\modules\references\models\Products;
use app\modules\references\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends BaseController
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
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
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
     * @return array|string|Response
     */
    public function actionCreate()
    {
        $model = new Products();

        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $response = $model->saveProduct();
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if ($response['status'])
                        $response['status'] = 0;
                    else
                        $response['status'] = 1;

                    return $response;
                }

                if ($response['status'])
                    return $this->redirect(['index']);
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
     * @param $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $request = Yii::$app->request;
        if ($request->isPost) {
            if ($model->load($request->post())) {
                $response = $model->saveProduct();
                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    if ($response['status'])
                        $response['status'] = 0;
                    else
                        $response['status'] = 1;

                    return $response;
                }

                if ($response['status'])
                    return $this->redirect(['index']);

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
     * @param $id
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $app = Yii::$app;
        $isDeleted = false;
        $model = $this->findModel($id);
        $transaction = $app->db->beginTransaction();
        try {
            $model->status_id = BaseModel::STATUS_INACTIVE;
            if ($model->save()) {
                $isDeleted = true;
            }
            if ($isDeleted) {
                $transaction->commit();
            } else {
                $transaction->rollBack();
            }
        } catch (\Exception $e) {
            Yii::info('Not saved' . $e, 'save');
        }
        if ($app->request->isAjax) {
            $app->response->format = Response::FORMAT_JSON;
            $response = [];
            $response['status'] = 1;
            $response['message'] = Yii::t('app', 'Hatolik yuz berdi');
            if ($isDeleted) {
                $response['status'] = 0;
                $response['message'] = Yii::t('app', 'Deleted Successfully');
            }
            return $response;
        }
        if ($isDeleted) {
            $app->session->setFlash('success', Yii::t('app', 'Deleted Successfully'));
            return $this->redirect(['index']);
        } else {
            $app->session->setFlash('error', Yii::t('app', 'Hatolik yuz berdi'));
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * @param $id
     * @return Products|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id): ?Products
    {
        if (($model = Products::findOne((integer)$id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
