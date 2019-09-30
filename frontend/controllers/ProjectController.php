<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\Project;
use app\modules\helper\Helper;
class ProjectController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'only' => ['index', 'view','create','update','delete'],
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

        $project = Project::find()->orderBy('id DESC')->asArray()->all();

        return $project;
//        return $this->render('index');
    }

    public function actionTest()
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
//return 0;

//        return $_POST;
//        return Yii::$app->request->post();
//        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//return Yii::$app->request->post();
        $model = new Project(); //создаём объект

        $model->date = date('Y-m-d');
//        return Yii::$app->request->post();
//        print_r(Yii::$app->request->post());
//        return 'test';
        $model->load(Yii::$app->request->post(),'');

        if ($model->validate() && $model->save()) {
            return ['result'=>true, 'id'=>$model->id];
        } else {
//TODO: Сделать возможность передать валидацию для vue с модели YII

//            return Helper::setValidations($model->rules());
//            return $model->validators;
            // данные не корректны: $errors - массив содержащий сообщения об ошибках
           return ['result'=>false, 'message'=>$model->errors];
        }



    }
    public function actionView($id)
    {
        $project = $this->findModel($id);
        return $project;
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
//        return ['er','er'];
        $model = $this->findModel($id);
//        $data = Ingredients::find()->select(['title', 'id'])->where('active = 1')->indexBy('id')->column();
        if ($model->load(Yii::$app->request->post(),'') && $model->save()) {
             return ['result'=>true, 'id'=>$model->id];
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
        $project =  $this->findModel($id);
//        $dish->unlinkAll('ingredients',true);
        $project->delete();
        return true;
    }


    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
