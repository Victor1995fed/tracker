<?php

namespace frontend\controllers;
use app\models\Tag;
use frontend\constants\Settings;
use frontend\models\Note;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;

/**
 * Site controller
 */
class TagController extends AbstractApiController
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'list'  => ['get'],
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
            ],
        ];
        return $behaviors;
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionList()
    {
        return 'test';
    }



    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tag();
        $model->load(Yii::$app->request->post(), '');
        $model->user_id = \Yii::$app->user->identity->id;;
        if ($model->validate() && $model->save()){
            return  $model->id;
        }
        else {
            throw new HttpException(500, serialize($model->errors));
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post(),'') ) {
            if($model->save()){
                return $model->id;
            }
            else
                throw new HttpException(500, serialize($model->errors));
        }

        return $model;

    }

    /**
     * Deletes an existing Dish model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        //TODO:: Вынести удаление файлов с сервера в отдельную функцию в модель File
        $model->delete();
        return true;
    }



    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
