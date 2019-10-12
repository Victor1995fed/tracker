<?php
namespace frontend\controllers;
use Yii;
use yii\filters\VerbFilter;
use frontend\models\Category;

class CategoryController extends AbstractApiController
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
//        $category = Category::find()->orderBy('id DESC')->asArray()->all();
        //Список категорий с родительскими
        $category = Category::find()->select(['CAT.title catTitle', 'CAT.id catId', 'PARENT.title parentTitle', 'PARENT.id parentId'])->from('category CAT')->leftJoin('category PARENT','CAT.parent_id = PARENT.id')->asArray()->all();

        return $category;
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category(); //создаём объект
        $model->load(Yii::$app->request->post(),'');
        if ($model->validate() && $model->save()) {
            return ['result'=>true, 'id'=>$model->id];
        }
        else{
            return ['result'=>false, 'message'=>$model->errors];
        }

    }


    /**
     * Updates an existing Category model.
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
        if ($model->load(Yii::$app->request->getBodyParams(),'') && $model->save()) {
            return ['result'=>true, 'id'=>$model->id];
        }
        else
            return $model->errors;
//        return $model;
    }


    public function actionView($id)
    {

        $category = $this->findModel($id);
        //Получаем родительскую задачу, если есть
        if($category->parent_id !== null){
            $parentCategory = $this->findModel($category->parent_id);
        }
        return [
            'category' => $category,
            'parentCategory' => $parentCategory ?? null
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
        $category =  $this->findModel($id);
        $category->delete();
        return true;
    }

    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
