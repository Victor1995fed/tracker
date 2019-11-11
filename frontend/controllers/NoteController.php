<?php
namespace frontend\controllers;
use frontend\constants\Settings;
use frontend\models\Note;
use Yii;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class NoteController extends AbstractApiController
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($page)
    {

        $offset = ($page - 1) * $this->pageSize;
        $countTask = Note::find()->count();
        $pageCount = ceil($countTask / $this->pageSize);

//        return $countTask;

//        $task = Task::find()->with('category','priority')->orderBy('id DESC')->limit(15)->offset(1)->asArray()->all();

        $task = Note::find()->offset($offset)->limit($this->pageSize)->orderBy('id DESC')->asArray()->all();

        return ['note'=>$task,'countPage'=>$pageCount];
//ERROR:
    }


    public function actionView($id)
    {
        $note = $this->findModel($id);


        return [
            'note' => $note,
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Note();
        $model->date_create = date(Settings::DATE_FORMAT_PHP);
        $model->load(Yii::$app->request->post(), '');

        if ($model->validate() && $model->save()){
            return ['result' => true, 'id' => $model->id];
        }
        else {
//TODO: Сделать возможность передать валидацию для vue с модели YII
            return ['result'=>false, 'message'=>$model->errors];
        }
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post(),'') ) {
            if($model->save()){

                return ['result' => true, 'id' => $model->id];
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
        //TODO:: Вынести удаление файлов с сервера в отдельную функцию в модель File
        $model->delete();
        return true;
    }



    protected function findModel($id)
    {
        if (($model = Note::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
