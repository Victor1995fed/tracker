<?php
namespace frontend\controllers;
use app\models\File;
use app\models\UploadForm;
use frontend\models\Category;
use frontend\models\Priority;
use frontend\models\Project;
use frontend\models\Status;
use Yii;
use yii\web\Controller;
use frontend\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class TaskController extends Controller
{

    public $pageSize = 10;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
                [
                    'class' => \yii\filters\ContentNegotiator::className(),
                    'only' => ['index', 'view','create','edit','update','delete'],
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
    public function actionIndex($page)
    {

        $offset = ($page - 1) * $this->pageSize;
        $countTask = Task::find()->count();
        $pageCount = ceil($countTask / $this->pageSize);

//        return $countTask;

//        $task = Task::find()->with('category','priority')->orderBy('id DESC')->limit(15)->offset(1)->asArray()->all();

        $task = Task::find()->with('category','priority')->offset($offset)->limit($this->pageSize)->orderBy('id DESC')->asArray()->all();

        return ['task'=>$task,'countPage'=>$pageCount];
//ERROR:
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
        $status = Yii::$app->request->post('status_id');
        if ($status === null)
            $model->status_id = 1;

        $model->load(Yii::$app->request->post(), '');

        if ($model->validate() && $model->save()){
            $warning = null;
            $fileSave = $this->saveFile($model);
            if(!$fileSave['result']){
                $warning = $fileSave['errors'];
            }
           return ['result' => true, 'id' => $model->id,'warning'=>$warning];
        }
        else {
//TODO: Сделать возможность передать валидацию для vue с модели YII
            return ['result'=>false, 'message'=>$model->errors];
            }


    }


    public function actionEdit()
    {
        $category = Category::find()->select('title, id')->orderBy('id DESC')->asArray()->all();
        $project = Project::find()->select('title, id')->orderBy('id DESC')->asArray()->all();
        $priority = Priority::find()->select('title, id')->orderBy('id DESC')->asArray()->all();
        $status = Status::find()->select('title, id, code')->orderBy('id ASC')->asArray()->all();

        return [
            'category' => $category,
            'project' => $project,
            'priority' => $priority,
            'status'=> $status
        ];


    }

    public function actionView($id)
    {
        $task = $this->findModel($id);
        $category = $task->category;
        $priority = $task->priority;
        $status = $task->status;
        $files = $task->file;
        $project = $task->project;
        //Получаем родительскую задачу, если есть
        if($task->parent_id !== null){
            $parentTask = $this->findModel($task->parent_id);
        }


        return [
            'task' => $task,
            'category' => $category,
            'priority' => $priority,
            'files' => $files,
            'status'=>$status,
            'project'=>$project,
            'parent_task'=>$parentTask ?? null
        ];
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
        $files = $model->file;
        //TODO:: Вынести удаление файлов с сервера в отдельную функцию в модель File
        //Удаление файлов с сервера
        foreach ($files as $key => $file){
            if($file['url'] != '')
                    @unlink($file['url']);
        }
        $model->unlinkAll('file',true);
        $model->delete();
        return true;
    }

    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post(),'') ) {
            //Сумма трудозатрат
            $spending = Yii::$app->request->post('spending');
            if ($spending !== null){
                $model->spending =  round((int) $this->findModel($id)->spending + $spending, 1);
            }
//            && $model->save()
            if($model->save()){
                return ['result'=>true, 'id'=>$model->id];
            }
            else
                return $model->errors;
        }

        return $model;

    }

    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function saveFile($model){
        $uploadForm = new UploadForm();

        $uploadForm->file  = UploadedFile::getInstancesByName( 'file');
        if(empty($uploadForm->file))
            return ['result'=>true];
        if ($dataFiles = $uploadForm->upload()) {

            foreach ($dataFiles as $file){
                $files  = new File();
                $files->url = $file['path'];
                $files->title = $file['name'];
                $files->uuid = $file['uuid'];
                $files->date_create = date('Y-m-d');
                $files->save();
                $files->id;
                $model->link('file', $files);
            }
            return ['result'=>true];
        }
        else
            return ['result'=>false,'errors'=>$uploadForm->errors];
    }


}
