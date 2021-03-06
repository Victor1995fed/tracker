<?php

namespace frontend\controllers;

//use Cassandra\Uuid;
use common\models\LoginForm;
use common\models\User;
use Faker\Provider\Uuid;
use Yii;
use yii\filters\VerbFilter;

class UserController extends AbstractApiController
{
    public function actionIndex()
    {
        return true;
        return $this->render('index');
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'index'  => ['get'],
                'view'   => ['get'],
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'edit' => ['get'],
                'test' => ['post'],
                'login' => ['post'],
            ],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return ['user'=>$model->username,'token'=>$model->token];
        } else {
            throw new \yii\web\ForbiddenHttpException();

        }

    }

    public function actionGetHash($password)
    {
       return Yii::$app->security->generatePasswordHash($password);
    }

}
