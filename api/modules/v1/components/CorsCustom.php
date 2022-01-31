<?php

namespace app\api\modules\v1\components;


use Yii;

use yii\filters\Cors;


class CorsCustom extends Cors
{
    /**
     * @param $action
     * @return bool
     * @throws yii\base\ExitException
     */
    public function beforeAction($action)
    {

        parent::beforeAction($action);

        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {

            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT');

            Yii::$app->end();

        }
        return true;
    }
}
