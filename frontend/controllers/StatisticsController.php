<?php
namespace frontend\controllers;
use frontend\constants\Settings;
use frontend\models\Status;
use frontend\models\Task;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\filters\VerbFilter;
use app\modules\helper\Helper;
use yii\web\HttpException;

class StatisticsController extends AbstractApiController
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
                'test' => ['get']
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
        //TODO:: Добавить реальную дату завершения, и фильтровать тоже по ней
        $queryCurrentMouth = '(MONTH(`date`) = MONTH(NOW())
    AND YEAR(`date`) = YEAR(NOW())) or (MONTH(`date_end`) = MONTH(NOW())
    AND YEAR(`date_end`) = YEAR(NOW()) and status_id = '.'3'.')'; //TODO:: Заменить на константу
        $countAll = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            ])
            ->count();
        $countDone = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            'status_id' => 3 //TODO:: Заменить на константы
            ])
            ->count();

        $countNew = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            'status_id' => 1
        ])
            ->count();

        $allTaskCurrentMouth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
        ])
            ->andWhere($queryCurrentMouth)->asArray()->all();

        //Общее кол-во трудозатрат по проектам
        $spending = Task::find()
            ->select([new Expression('SUM(task.spending) as sum_spending'), 'project.id','project.title'])
            ->leftJoin('project','task.project_id = project.id')
            ->where([
                'task.user_id'=>\Yii::$app->user->identity->id,
            ])
            ->groupBy('task.project_id')->asArray()->all();


        return
            [
                'count_done'=> $countDone,
                'spending' => $spending,
                'count_new'=> $countNew,
                'count_other' => $countAll - $countDone - $countNew,
                'count_create_in_current_month'=>$countAll,
                'line_chart' => $this->partDataPeriod($allTaskCurrentMouth)
                //TODO:: И так далее...
            ];
    }

    public function actionTest()
    {
        $queryCurrentMouth = '(MONTH(`date`) = MONTH(NOW())
    AND YEAR(`date`) = YEAR(NOW())) or (MONTH(`date_end`) = MONTH(NOW())
    AND YEAR(`date_end`) = YEAR(NOW()))';
        $allTaskCurrentMouth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
        ])
            ->andWhere($queryCurrentMouth)->asArray()->all();

        return $this->partDataPeriod($allTaskCurrentMouth);
    }

    private function getPeriodWeek()
    {
        $start = new \DateTime('first day of this month');
        $end = new \DateTime('last day of this month');
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($start, $interval, $end);
        $weekNumber = 1;
        $weeks = [];
        foreach ($dateRange as $date) {
            $weeks[$weekNumber][] = $date->format('d');
            if ($date->format('w') == 0) {
                $weekNumber++;
            }
        }
        return $weeks;
    }

    private function partDataPeriod($queryCurrentMouth)
    {
        $week = $this->getPeriodWeek();
        $keys = array_fill_keys(array_keys($week),0);
        $result = [
            'done'=>$keys,
            'all'=>$keys,
            'week'=>$week
        ];
        $count = 0;
        //FIXME:: Неправильно отображаются выполненные задачи, требуется, чтобы для них учитывалась date_end , а не date
        foreach ($queryCurrentMouth as $key => $value){
                foreach ($week as $k=>$v){
                    $dateCreate = strtotime($value['date']);
                    $day = date('d', $dateCreate);
                    if(in_array($day, $v)){
                        switch ($value['status_id']){

//                            case '1':
//                                $keyResult = 'new';
//                                break;
                            case '3':
                                $result['done'][$k] = $result['done'][$k] + 1;
                                break;
                            default:
                                $result['all'][$k] = $result['all'][$k] + 1;
                                break;
                        }
                        break;
                    }

                }
        }
        return $result;
    }


}
