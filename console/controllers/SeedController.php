<?php
namespace console\controllers;

use common\models\elastic\Project;
use common\models\User;
use frontend\constants\Priority;
use frontend\constants\TaskStatus;
use frontend\models\Status;
use frontend\models\Task;
use yii\console\Controller;
use yii\db\Exception;

class SeedController extends Controller
{
    public  $user = [
        'id'=>1,
        'username' => 'test',
        'email' => 'test@email.com',
        'password' => '123456',
        'status' => User::STATUS_ACTIVE
    ];

    public  $statuses = [
        [
            'id'=>TaskStatus::NEW,
            'code'=>'new',
            'title'=>'Новый'
        ],
        [
            'id'=>TaskStatus::WORK,
            'code'=>'work',
            'title'=>'В работе'
        ],
        [
            'id'=>TaskStatus::DONE,
            'code'=>'done',
            'title'=>'Выполнено'
        ],
        [
            'id'=>TaskStatus::PAUSE,
            'code'=>'pause',
            'title'=>'Приостановлено'
        ],
        [
            'id'=>TaskStatus::CANCEL,
            'code'=>'cancel',
            'title'=>'Отменено'
        ],
        [
            'id'=>TaskStatus::EXPIRED,
            'code'=>'expired',
            'title'=>'Просрочен'
        ], [
            'id'=>TaskStatus::OPEN,
            'code'=>'open',
            'title'=>'Открыт'
        ],
        [
            'id'=>TaskStatus::CLOSE,
            'code'=>'close',
            'title'=>'Закрыт'
        ],

    ];

    public  $priority = [
        [
            'id'=>Priority::LOW,
            'code'=>'low',
            'title'=>'Низкий'
        ],
        [
            'id'=>Priority::MIDDLE,
            'code'=>'middle',
            'title'=>'Средний'
        ],
        [
            'id'=>Priority::HIGH,
            'code'=>'high',
            'title'=>'Высокий'
        ],
        [
            'id'=>Priority::SUPREME,
            'code'=>'supreme',
            'title'=>'Наивысший'
        ],

    ];

    public  $projects = [
        [
            'id'=>1,
            'title'=>'Пример проекта',
            'description'=>'Здесь указано описание проекта',
            'status_id'=>TaskStatus::OPEN
        ],

    ];

    public  $tasks = [
        [
            'id'=>1,
            'description'=>'Описание задачи',
            'title'=>'Пример задачи',
            'priority_id'=>Priority::MIDDLE,
            'status_id'=>TaskStatus::OPEN,
            'user_id'=>1,
            'project_id'=>1
        ],

    ];

    /**
     * @return bool
     */
    public function actionIndex()
    {
        try {
//            $this->addUser();
//            $this->addPriority();
//            $this->addStatuses();
            $this->addProject();
            $this->addTask();
        }
        catch (\Exception $e) {
            echo PHP_EOL.'Error: '.$e->getMessage().PHP_EOL;
            return false;
        }

        echo PHP_EOL."Готово!";
        return true;

    }

    protected function addUser()
    {
        $username = $this->user['username'];
        $password = $this->user['password'];
        $email = $this->user['email'];
        $status = $this->user['status'];
        try {
            $user = new User(['scenario' => User::SCENARIO_REGISTER]);
            $user->id = $this->user['id'];
            $user->username = $username;
            $user->email = $email;
            $user->status = $status;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->save();
        }
        catch (\Exception $e){
            echo $e->getMessage().PHP_EOL;
            return false;
        }
        echo 'Тестовый пользователь успешно   добавлен'.PHP_EOL.'username: '.$username.' password:'.$password;
        return true;
    }

    /**
     * @throws Exception
     */
    protected function addStatuses()
    {
        foreach ($this->statuses as $data) {
            $status = new Status();
            $status->id = $data['id'];
            $status->code = $data['code'];
            $status->title = $data['title'];
            if (!$status->save()) {
                throw new Exception('Ошибки при сохранении'.serialize($status->errors));
            }
        }
        echo PHP_EOL."Статусы добавлены";
    }

    /**
     * @throws Exception
     */
    protected function addPriority()
    {
        foreach ($this->priority as $data) {
            $priority = new \frontend\models\Priority();
            $priority->id = $data['id'];
            $priority->title = $data['title'];
            $priority->code = $data['code'];
            if (!$priority->save(false)) {
                throw new Exception('Ошибки при сохранении'.serialize($priority->errors));
            }
        }
        echo PHP_EOL."Приоритеты добавлены";
    }

    protected function addProject()
    {
        foreach ($this->projects as $data) {
            $projects = new \frontend\models\Project();
            $projects->id = $data['id'];
            $projects->title = $data['title'];
            $projects->description  = $data['description'];
            $projects->user_id = $this->user['id'];
            $projects->status_id = $data['status_id'];
            if (!$projects->save(false)) {
                throw new Exception('Ошибки при сохранении'.serialize($projects->errors));
            }
        }
        echo PHP_EOL."Пример проекта добавлен";
    }

    protected function addTask()
    {
        foreach ($this->tasks as $data) {
            $task = new Task();
            $task->id = $data['id'];
            $task->title = $data['title'];
            $task->description  = $data['description'];
            $task->user_id  = $this->user['id'];
            $task->status_id  = $data['status_id'];
            $task->priority_id  = $data['priority_id'];
            $task->project_id  = $data['project_id'];
            if (!$task->save(false)) {
                throw new Exception('Ошибки при сохранении'.serialize($task->errors));
            }
        }
        echo PHP_EOL."Пример задачи добавлен";
    }


}