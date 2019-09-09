<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\Category;

class CategoryController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'only' => ['index', 'view','create'],
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
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

        $category = Category::find()->orderBy('id DESC')->asArray()->all();

        return $category;
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Category(); //создаём объект
        $model->load(Yii::$app->request->post(),'');
        if ($model->validate() && $model->save()) {
            return ['result'=>true, 'id'=>$model->id];
        }
        else{
            return ['result'=>false, 'message'=>$model->errors];
        }



        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }
    public function actionView($id)
    {
        $category = $this->findModel($id);
        return $category;
    }

    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
