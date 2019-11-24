<?php
namespace frontend\controllers;
use frontend\constants\Settings;
use frontend\models\Status;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use frontend\models\Project;
use app\modules\helper\Helper;
use yii\web\HttpException;

class ProjectController extends AbstractApiController
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
                'index'  => ['get'],
                'view'   => ['get'],
                'create' => ['post'],
                'update' => ['put'],
                'delete' => ['delete'],
                'status'=> ['get']
            ],
        ];
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
            return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $project = Project::find()->orderBy('id DESC')->where(['user_id'=>\Yii::$app->user->identity->id])->asArray()->all();

        return $project;
    }


    public function actionView($id)
    {
        $project = $this->findModel($id);
        $this->checkAccess($project);
        return
            [
                'project' => $project,
                'status' => $project->status,
            ];
    }

    public function actionCreate()
    {
        $model = new Project(); //создаём объект
        $model->date = date(Settings::DATE_FORMAT_PHP);
        $status = Yii::$app->request->post('status_id');
        if ($status === null)
            $model->status_id = 7;

        $model->load(Yii::$app->request->post(),'');
        $model->user_id = \Yii::$app->user->identity->id;
        if ($model->validate() && $model->save()) {
            return $model->id;
        } else {
//TODO: Сделать возможность передать валидацию для vue с модели YII

            throw new HttpException(500, serialize($model->errors));
        }

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
        $model = $this->findModel($id);
        $this->checkAccess($model);
        if ($model->load(Yii::$app->request->getBodyParams(),'') && $model->save()) {
             return $model->id;
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
        $this->checkAccess($project);
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
