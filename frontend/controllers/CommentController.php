<?php
namespace frontend\controllers;
use frontend\constants\Settings;
use frontend\models\Comment;
use frontend\models\Task;
use Yii;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class CommentController extends AbstractApiController
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
                'list'  => ['get'],
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
    public function actionList($id)
    {
        $task = Task::findOne($id);
        return $task->comment;

    }


    public function actionView($id)
    {
        $Comment = $this->findModel($id);
        return [
            'Comment' => $Comment,
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate($id)
    {
        $task = Task::findOne($id);
        $this->checkAccess($task);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $comment= new Comment();
            $comment->date_create = date(Settings::DATE_FORMAT_PHP);

            $comment->load(Yii::$app->request->post(), '');
            $comment->user_id = \Yii::$app->user->identity->id;
            if($comment->save()){
                $comment->link('task',$task);
            }
            else
                throw new HttpException(500, serialize($comment->errors));

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new HttpException(500, $e->getMessage());
        }

        return $comment;
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post(),'') ) {
            if($model->save()){
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
        $model->unlinkAll('task',true);
        $model->delete();
        return true;
    }



    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}
