<?php
namespace frontend\controllers;
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
                    'only' => ['index', 'view'],
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $task = Task::find()->with('category','priority')->orderBy('id DESC')->limit(1,2)->all();
        return $task;
//        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionTest()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        {"BTC":{"USD":11290.45,"EUR":10037.03},"ETH":{"USD":267.54,"EUR":238.09}}
        return ["BTC"=>["USD"=>45,"EUR"=>58],"ETH"=>["USD"=>455,"EUR"=>584]];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task(); //создаём объект
        $model->date = date('Y-m-d');
        $status = Yii::$app->request->post('status');
        if($status === null)
            $model->status = 'new';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
