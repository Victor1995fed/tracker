<?php
namespace frontend\controllers;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use frontend\models\Category;

class TestController extends Controller
{

    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            [
//                'class' => \yii\filters\ContentNegotiator::className(),
//                'only' => ['index', 'view','create','update'],
//                'formats' => [
//                    'application/json' => \yii\web\Response::FORMAT_JSON,
//                ],
//            ],
//
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'index'  => ['get'],
//                    'view'   => ['get'],
//                    'create' => ['post'],
//                    'update' => ['put'],
//                    'delete' => ['delete'],
//                ],
//            ],
//
//        ];
//    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter' ] = [
            'class' => \yii\filters\Cors::className(),
        ];
        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::className(),
            'formats' => [
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ];

        $behaviors['verbs'] = [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index'  => ['get'],
                    'view'   => ['get'],
                    'create' => ['post'],
                    'update' => ['put'],
                    'delete' => ['delete'],
                    'auth' => ['post'],
                ],
            ];
        // В это место мы будем добавлять поведения (читай ниже)
        return $behaviors;
    }

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
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {

            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT DELETE');

            Yii::$app->end();
            return true;

        }

        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {


        return true;
    }

    public function actionAuth()
    {


        return [
            'access_token' => 'sdsdderterrt',
            'username' => 'admin'
        ];
    }



    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
