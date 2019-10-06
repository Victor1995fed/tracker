<?php
namespace frontend\controllers;
use frontend\models\Status;
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
                'only' => ['index', 'view','create','update','delete','status'],
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

        $model = new Project(); //создаём объект

        $model->date = date('Y-m-d');
        $status = Yii::$app->request->post('status_id');
        if ($status === null)
            $model->status_id = 7;
        $model->load(Yii::$app->request->post(),'');

        if ($model->validate() && $model->save()) {
            return ['result'=>true, 'id'=>$model->id];
        } else {
//TODO: Сделать возможность передать валидацию для vue с модели YII

           return ['result'=>false, 'message'=>$model->errors];
        }



    }
    public function actionView($id)
    {
        $project = $this->findModel($id);
        $status = $project->status;
        return
            [
              'project' => $project,
              'status' => $status,
            ];
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

    public function actionStatus()
    {
     $status = Status::find()->where(['id' => [7,8]])->all();
     return $status;

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
