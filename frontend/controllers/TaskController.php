<?php
namespace frontend\controllers;
use frontend\models\Category;
use frontend\models\Priorities;
use frontend\models\Project;
use Yii;
use yii\web\Controller;
use frontend\models\Task;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class TaskController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                [
                    'class' => \yii\filters\ContentNegotiator::className(),
                    'only' => ['index', 'view','create','edit'],
                    'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                    ],
                ],
                ];
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

//        $task = Task::find()->with('category','priority')->orderBy('id DESC')->limit(15)->offset(1)->asArray()->all();
        $task = Task::find()->with('category','priority')->orderBy('id DESC')->asArray()->all();

        return $task;

    }

    public function actionTest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ["BTC"=>["USD"=>45,"EUR"=>58],"ETH"=>["USD"=>455,"EUR"=>584]];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();
        $model->date = date('Y-m-d');
        $status = Yii::$app->request->post('status');
        if ($status === null)
            $model->status = 'new';

        $model->load(Yii::$app->request->post(), '');
        if ($model->validate() && $model->save())
            return ['result' => true, 'id' => $model->id];
        else {
//TODO: Сделать возможность передать валидацию для vue с модели YII
            return ['result'=>false, 'message'=>$model->errors];
            }


    }


    public function actionEdit()
    {
        $category = Category::find()->select('title, id')->orderBy('id DESC')->asArray()->all();
        $project = Project::find()->select('title, id')->orderBy('id DESC')->asArray()->all();
        $priority = Priorities::find()->select('title, id')->orderBy('id DESC')->asArray()->all();

        return [
            'category' => $category,
            'project' => $project,
            'priority' => $priority,
        ];


    }

    public function actionView($id)
    {
        $task = $this->findModel($id);
        $category = $task->category;
        $priority = $task->priority;
        return [
            'task' => $task,
            'category' => $category,
            'priority' => $priority
        ];
    }

    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
