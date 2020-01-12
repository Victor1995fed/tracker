<?php
namespace frontend\controllers;
use frontend\constants\Settings;
use frontend\models\Status;
use frontend\models\Task;
use Yii;
use yii\db\Exception;
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
        $queryCurrentMouth = 'MONTH(`date`) = MONTH(NOW())
AND YEAR(`date`) = YEAR(NOW())';
        $countInCurrentMonth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            ])
            ->andWhere($queryCurrentMouth)
            ->count();
        $countDoneCurrentMouth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            'status_id' => 3 //TODO:: Заменить на константы
            ])
            ->andWhere($queryCurrentMouth)
            ->count();

        $countNewCurrentMouth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
            'status_id' => 1
        ])
            ->andWhere($queryCurrentMouth)
            ->count();

        $allTaskCurrentMouth = Task::find()->where([
            'user_id'=>\Yii::$app->user->identity->id,
        ])
            ->andWhere($queryCurrentMouth)->asArray()->all();

        return
            [
                'count_done'=> $countDoneCurrentMouth,
                'count_new'=> $countNewCurrentMouth,
                'count_other' => $countInCurrentMonth - $countDoneCurrentMouth - $countNewCurrentMouth,
                'count_create_in_current_month'=>$countInCurrentMonth,
                'line_chart' => $this->partDataPeriod($allTaskCurrentMouth)
                //TODO:: И так далее...
            ];
    }

    public function actionTest()
    {
        $queryCurrentMouth = 'MONTH(`date`) = MONTH(NOW())
AND YEAR(`date`) = YEAR(NOW())';
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
//            'new'=>$keys,
            'done'=>$keys,
            'all'=>$keys,
            'week'=>$week
        ];
        $count = 0;
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
