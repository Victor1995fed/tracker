<?php
namespace frontend\controllers;
use Yii;
use yii\rest\Controller;


class AbstractApiController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],

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
