<?php
namespace frontend\controllers;
use app\models\File;
use frontend\models\Tag;
use app\models\UploadForm;
use frontend\modules\helpers\UploadFileExt;
use frontend\constants\Settings;
use frontend\constants\TaskStatus;
use frontend\models\Category;
use frontend\models\Comment;
use frontend\models\History;
use frontend\models\Priority;
use frontend\models\Project;
use frontend\models\Status;
use frontend\models\TaskSearch;
use Yii;
use yii\filters\VerbFilter;
use frontend\models\Task;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class TaskController extends AbstractApiController
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
                'file-delete' => ['delete'],
                'edit' => ['get'],
                'test' => ['get'],
                'get-history' =>['get']
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

    public function actionTest($utc)
    {

        $time_client = $utc * 3600;
        $real_time = time() + $time_client;

       return date("Y-m-d", $real_time);
    }

    public function actionView($id)
    {
        $task = $this->findModel($id);
        $this->checkAccess($task);
        //Получаем родительскую задачу, если есть
        if($task->parent_id !== null){
            $parentTask = $this->findModel($task->parent_id);
        }
        return [
            'task' => $task,
            'category' => $task->category,
            'priority' => $task->priority,
            'files' => $task->file,
            'status'=> $task->status,
            'project'=> $task->project,
            'parent_task' => $parentTask ?? null,
            'tag' => $task->tag,
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        //TODO: Перенести функции сохранения файлов в модель
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = new Task();
            $model->date = date(Settings::DATE_FORMAT_PHP);
            $status = Yii::$app->request->post('status_id');
            if ($status === null)
                $model->status_id = TaskStatus::NEW;
            $model->load(Yii::$app->request->post(), '');
            $model->user_id = \Yii::$app->user->identity->id;
            if ($model->validate() && $model->save()) {
                $warning = null;
                $fileSave = $this->saveFile($model);
                $transaction->commit();
                return  $model->id;
            } else {
//TODO: Сделать возможность передать валидацию для vue с модели YII
                throw new HttpException(500, serialize($model->errors));
            }
        }
        catch (Exception $e) {
            $transaction->rollBack();
            throw new HttpException(500, $e->getMessage());
        }
    }


    public function actionUpdate($id)
    {
        //TODO:: Добавить сценарии
        $model = $this->findModel($id);
        $this->checkAccess($model);
        $modelOldAttributes = $model->getAttributes();
        $model->date_start = null;
        $model->date_end = null;
        if ($model->load(Yii::$app->request->post(),'') ) {
            //Сумма трудозатрат
            $spending = Yii::$app->request->post('spending');
            if ($spending !== null){
                $model->spending =  round((float) $this->findModel($id)->spending + $spending, 1);
            }
            $modelDirtyAttributes = $model->getDirtyAttributes();

            if($model->validate() && $model->save(false)){
                $fileSave = $this->saveFile($model);
                $fieldsChanged = History::getChangesFields($modelDirtyAttributes, $modelOldAttributes);
                if(!empty($fieldsChanged)){
                    //Запись в историю
                    $history = new History();
                    $history->user_id = \Yii::$app->user->identity->id;
                    $history->comment = $model->changedFieldsToString($fieldsChanged);
                    $history->save();
                    $history->link('task',$model);
                }
//                #######################################
                //TODO:: Добавить тесты для этого контроллера
                return  $model->id;
            }
            else
                throw new HttpException(500, serialize($model->errors));
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
        $comments = $model->comment;
        //TODO:: Вынести удаление файлов с сервера в отдельную функцию в модель File
        //Удаление файлов с сервера
        foreach ($files as $key => $file){
            if($file['url'] != '')
                @unlink($file['url']);
        }
        $model->unlinkAll('file',true);


        //Удаление комментариев
        $model->unlinkAll('comment',true);
        foreach ($comments as $key => $one){
            $comment = Comment::findOne($one['id']);
            $comment->delete();
        }
        $model->delete();
        return true;
    }

    public function actionEdit()
    {
        $userId = \Yii::$app->user->identity->id;
        $category = Category::find()->select('title, id')->where(['user_id'=>$userId])->orderBy('id DESC')->asArray()->all();
        $project = Project::find()->select('title, id')->where(['user_id'=>$userId])->orderBy('id DESC')->asArray()->all();
        $priority = Priority::find()->select('title, id')->orderBy('id DESC')->asArray()->all();
        $status = Status::find()->select('title, id, code')->orderBy('id ASC')->asArray()->all();
        $tag = Tag::find()->where(['user_id' => $userId])->asArray()->all();

        return [
            'category' => $category,
            'project' => $project,
            'priority' => $priority,
            'status'=> $status,
            'tag' => $tag
        ];


    }

    public function actionGetHistory($id)
    {
        $model = $this->findModel($id);
        return $model->history;
    }

    public function actionFileDelete($id,$uuid)
    {
        $model =  $this->findModel($id);
        $this->checkAccess($model);
        $model->unlink('file',
            File::find()
                ->where(['uuid' => $uuid])
                ->one()
        );
        return true;

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
        $uploadForm->file  = UploadFileExt::getInstancesByName( 'file');
        if(empty($uploadForm->file))
            return true;
        if ($dataFiles = $uploadForm->upload()) {
            try{
                foreach ($dataFiles as $file){
                    if(isset($file['findOne'])){
                       $files =  $file['findOne'];
                    }
                    else{
                        $files  = new File();
                        $files->url = $file['path'];
                        $files->title = $file['name'];
                        $files->uuid = $file['uuid'];
                        $files->date_create = date(Settings::DATE_FORMAT_PHP);
                        $files->file_hash = $file['file_hash'];
                        $files->save();
                        $files->id;
                    }
                    $model->link('file', $files);

                }
                return true;
            }
            catch (Exception $e) {
                throw new HttpException(500, $e->getMessage());
            }

        }
        else
            throw new HttpException(500, serialize($uploadForm->errors));
    }


}
