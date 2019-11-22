<?php

namespace frontend\models;

use app\models\File;
use frontend\constants\HistoryAction;
use frontend\constants\Settings;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * ContactForm is the model behind the contact form.
 */
class Task extends ActiveRecord
{

    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['user_id'], 'required'],
            [['title', 'description'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['category_id'], 'integer'    ],
            [['priority_id'], 'integer', 'max' => 4],
            [['priority_id'], 'required'],
            [['project_id'], 'integer'],
            [['date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['date_start'], 'date', 'format' => Settings::DATE_FORMAT_MODEL],
            [['date_end'], 'date', 'format' => Settings::DATE_FORMAT_MODEL],
            [['status_id'], 'integer', 'max' => 10],
            [['readiness'], 'integer'],
            [['parent_id'], 'integer'],
            [['spending'], 'double', 'max'=>100, 'min'=>0.0],
            [['user_id'], 'integer']
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'priority_id' => 'Приоритет',
            'date' => 'Дата',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
            'project_id' => 'Проект',
            'parent_id' => 'Id родительской записи',
            'spending' => 'Трудозатраты',
            'readiness' => 'Прогресс',
            'status_id' => 'Статус'
        ];


    }

    public function getCategory(){
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getPriority(){
        return $this->hasOne(Priority::class, ['id' => 'priority_id']);
    }
    public function getStatus(){
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    public function getProject(){
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    public function getFile(){
        return $this->hasMany(File::class, ['id' => 'file_id'])
            ->viaTable('task_file', ['task_id' => 'id']);
    }

    public function getComment(){
//        TODO:: Переделать сортировку по date_create
        return $this->hasMany(Comment::class, ['id' => 'comment_id'])->orderBy(['id' => SORT_DESC])
            ->viaTable('task_comment', ['task_id' => 'id']);
    }

    public function getHistory(){
        return $this->hasMany(History::class, ['id' => 'history_id'])->orderBy(['history.date'=>SORT_DESC])
            ->viaTable('task_history', ['task_id' => 'id']);
    }

    public function changedFieldsToString($fieldsChanged)
    {
        $comment = '';
        $priority = $this->setKeyId(Priority::find()->asArray()->all());
        $status = $this->setKeyId(Status::find()->asArray()->all());
        $attributeLabel = $this->attributeLabels();
        foreach ($fieldsChanged as $key => $value){
            //TODO:: Добавить проверки
            //TODO:: Добавить отдельную проверку для трудозатрат
            switch ($value['attribute']){
                case 'priority_id':
                    $valueOld = $priority[$value['old']];
                    $valueNew = $priority[$value['new']];
                    break;
                case 'status_id':
                    $valueOld = $status[$value['old']];
                    $valueNew = $status[$value['new']];
                    break;

                case 'project_id':
                    $valueOld =  Project::findOne($value['old'])['title'];
                    $valueNew =  Project::findOne($value['new'])['title'];
                    break;
                default:
                    $valueOld = $value['old'];
                    $valueNew = $value['new'];
                    break;
            }
            $comment .= '<b>'.HistoryAction::CHANGE_FIELD.'</b> <u>'.(isset($attributeLabel[$value['attribute']]) ? $attributeLabel[$value['attribute']] : $value['attribute']).'</u>  <code>'.$valueOld.'</code> &rArr; <code>'.$valueNew.'</code><br />.';
        }
        return $comment;
    }

    protected function setKeyId($list)
    {
        $result = [];
        foreach ($list as $k=>$v){
            $result[$v['id']] = $v['title'];
        }
        return $result;
    }
}
