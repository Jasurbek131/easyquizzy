<?php

namespace app\modules\references\controllers;

use app\components\PermissionHelper as P;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `admin` module
 */
class BaseController extends Controller
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
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws ForbiddenHttpException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        if (Yii::$app->authManager->getPermission(Yii::$app->controller->id."/".Yii::$app->controller->action->id)) {
            if (!P::can(Yii::$app->controller->id . "/" . Yii::$app->controller->action->id)) {
                throw new ForbiddenHttpException(Yii::t('app', 'Access denied'));
            }
        }

        return parent::beforeAction($action);
    }
}
