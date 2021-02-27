<?php
namespace frontend\controllers;
use frontend\constants\Settings;
use frontend\constants\TaskStatus;
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
        $queryCurrentMouth = 'MONTH(`date`) = MONTH(NOW())
    AND YEAR(`date`) = YEAR(NOW())';
        $queryCurrentMouthDone = 'MONTH(`date_end`) = MONTH(NOW())
    AND YEAR(`date_end`) = YEAR(NOW())';
        //Выборка всех соданных задач
        $allTaskCurrentMouth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
        ])
            ->andWhere($queryCurrentMouth)->asArray()->all();
        //Выборка выполненных задач
        $doneTaskCurrentMouth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            'status_id'=>TaskStatus::DONE
        ])
            ->andWhere($queryCurrentMouthDone)->asArray()->all();
        $countAll = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            ])
            ->count();
        $countDone = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            'status_id' => TaskStatus::DONE
            ])
            ->count();

        $countNew = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            'status_id' => TaskStatus::NEW
        ])
            ->count();



        //Общее кол-во трудозатрат по проектам за текущую неделю
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
                'line_chart_all' => $this->partDataPeriod($allTaskCurrentMouth),
                'line_chart_done' => $this->partDataPeriod($doneTaskCurrentMouth, 'date_end'),
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
        $year = date('Y');
        $month = date('m');
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $datesMonth=[];
        for($i=1;$i<=$num;$i++){
            $mktime=mktime(0,0,0,$month,$i,$year);
            $date=date("d",$mktime);
            $datesMonth[$i]=$date;
        }
        return $datesMonth;
    }

    private function partDataPeriod(array $queryCurrentMouth, string $dateType='date')
    {
        $days = $this->getPeriodWeek();
        $keys = array_fill_keys(array_keys($days),0);
        foreach ($queryCurrentMouth as $key=>$value){
            $dayTask =  (int)date('d', strtotime($value[$dateType]));
            if(in_array($dayTask, $days)){
                $keys[$dayTask]++;
            }
        }
        return $keys;
    }


}
