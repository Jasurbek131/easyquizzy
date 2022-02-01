<?php

namespace app\api\modules\v2\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;

/**
 * Country Controller API
 */
class DefaultController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public $enableCsrfValidation = false;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }

    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actionIndex(){
        $response['status'] = 'true';
        return $response;
    }
}
