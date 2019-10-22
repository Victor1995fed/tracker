<?php
namespace frontend\controllers;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;


class AbstractApiController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            //TODO:: Раскомментить авторизацию
//            'authenticator'=>[
//                'class'=>HttpBearerAuth::class,
//                'except' => ['options','login'],
//            ],

        ];
    }


    public function beforeAction($action)
    {
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT DELETE');
            Yii::$app->end();
            return true;

        }
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


}
