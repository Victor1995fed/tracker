<?php
namespace frontend\controllers;
use app\models\File;
use app\models\UploadForm;
use frontend\models\Category;
use frontend\models\Priority;
use frontend\models\Project;
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

    public $pageSize = 5;

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
    public function actionIndex($page)
    {

        $offset = ($page - 1) * $this->pageSize;
        $countTask = Task::find()->count();
        $pageCount = ceil($countTask / $this->pageSize);

//        return $countTask;

//        $task = Task::find()->with('category','priority')->orderBy('id DESC')->limit(15)->offset(1)->asArray()->all();

        $task = Task::find()->with('category','priority')->offset($offset)->limit($this->pageSize)->orderBy('id DESC')->asArray()->all();

        return ['task'=>$task,'countPage'=>$pageCount];

    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
//        $uploadForm = new UploadForm();
//         $uploadForm->file =  UploadedFile::getInstancesByName( 'fileee');
//        return $uploadForm->file;
//        return $_FILES;

        $model = new Task();
        $model->date = date('Y-m-d');
        $status = Yii::$app->request->post('status');
        if ($status === null)
            $model->status = 'new';

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
        $files = $task->file;
        return [
            'task' => $task,
            'category' => $category,
            'priority' => $priority,
            'files' => $files
        ];
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
