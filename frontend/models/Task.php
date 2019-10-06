<?php

namespace frontend\models;

use app\models\File;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
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
            [['title', 'description'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['category_id'], 'integer'    ],
            [['priority_id'], 'integer', 'max' => 4],
            [['priority_id'], 'required'],
            [['project_id'], 'integer'],
            [['date'], 'date', 'format' => 'Y-m-d'],
            [['date_start'], 'date', 'format' => 'Y-m-d'],
            [['date_end'], 'date', 'format' => 'Y-m-d'],
            [['status_id'], 'integer', 'max' => 10],
            [['readiness'], 'integer'],
            [['parent_id'], 'integer'],
            [['spending'], 'double', 'max'=>100, 'min'=>0.0],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Тема',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'priority_id' => 'Приоритет',
            'date' => 'Дата',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
            'project_id' => 'Проект',
            'parent_id' => 'Id родительской записи',
            'spending' => 'Трудозатраты'
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
}
