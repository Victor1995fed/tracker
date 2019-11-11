<?php
namespace frontend\controllers;
use \yii\web\HttpException;
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
            'authenticator'=>[
                'class'=>HttpBearerAuth::class,
                'except' => ['options','login'],
            ],

        ];
    }


    public function beforeAction($action)
    {
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT DELETE');
            Yii::$app->getResponse()->getHeaders()->set('Access-Control-Allow-Headers', '*');

            Yii::$app->end();
            return true;

        }
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    protected function checkAccess($model)
    {
        if ($model->user_id !== \Yii::$app->user->identity->id){
            throw new HttpException('404', 'Нет прав для просмотра данной страницы');
        }
    }


}
