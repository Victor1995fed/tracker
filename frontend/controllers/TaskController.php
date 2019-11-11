<?php
namespace frontend\controllers;
use app\models\File;
use app\models\UploadForm;
use app\modules\helpers\UploadFileExt;
use frontend\constants\Settings;
use frontend\models\Category;
use frontend\models\Priority;
use frontend\models\Project;
use frontend\models\Status;
use frontend\models\TaskSearch;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\Controller;
use frontend\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class TaskController extends AbstractApiController
{

    public $pageSize = 10;
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
                'edit' => ['get'],
                'test' => ['get']
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



    public function actionIndex($page)
    {

        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $pagesize = $dataProvider->pagination->pageSize;// it will give Per Page data.
        $total = $dataProvider->totalCount; //total records // 15
        $totalPage =(int) (($total + $pagesize - 1) / $pagesize);

        return ['task'=>$dataProvider->getModels(),'countPage'=>$totalPage];
    }

    public function actionView($id)
    {
        $task = $this->findModel($id);
        $this->checkAccess($task);
        $category = $task->category;
        $priority = $task->priority;
        $status = $task->status;
        $files = $task->file;
        $project = $task->project;
        //Получаем родительскую задачу, если есть
        if($task->parent_id !== null){
            $parentTask = $this->findModel($task->parent_id);
        }

        //change format date

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
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
//        TODO: Добавить исключения
        $model = new Task();
        $model->date = date(Settings::DATE_FORMAT_PHP);
        $status = Yii::$app->request->post('status_id');
        if ($status === null)
            $model->status_id = 1;

        $model->load(Yii::$app->request->post(), '');
        $model->user_id = \Yii::$app->user->identity->id;
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


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->checkAccess($model);
        if ($model->load(Yii::$app->request->post(),'') ) {
            //Сумма трудозатрат
            $spending = Yii::$app->request->post('spending');
            if ($spending !== null){
                $model->spending =  round((int) $this->findModel($id)->spending + $spending, 1);
            }
            if($model->save()){
                $warning = null;
                $fileSave = $this->saveFile($model);
                if(!$fileSave['result']){
                    $warning = $fileSave['errors'];
                }
                //TODO:: Добавить тесты для этого контроллера
                return ['result' => true, 'id' => $model->id,'warning'=>$warning];
            }
            else
                return $model->errors;
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
        $this->checkAccess($model);
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


    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function saveFile($model){
        //    TODO:: Добавить проверку уникальности загружаемых файлов, через md5_file
        $uploadForm = new UploadForm();

//        $uploadForm->file  = UploadedFile::getInstancesByName( 'file');
        $uploadForm->file  = UploadFileExt::getInstancesByName( 'file');
//        throw new \Exception(serialize($uploadForm->file));
        if(empty($uploadForm->file))
            return ['result'=>true];
        if ($dataFiles = $uploadForm->upload()) {

            foreach ($dataFiles as $file){
                $files  = new File();
                $files->url = $file['path'];
                $files->title = $file['name'];
                $files->uuid = $file['uuid'];
                $files->date_create = date(Settings::DATE_FORMAT_PHP);
                $files->save();
                $files->id;
                $model->link('file', $files);
                //TODO:: Добавить обработку ошибок!!!
            }
            return ['result'=>true];
        }
        else
            return ['result'=>false,'errors'=>$uploadForm->errors];
    }


}
