<?php

namespace common\models\elastic;

/**
 * ContactForm is the model behind the contact form.
 */
class Project extends ElasticMain
{


    // index
    public static function index()
    {
        return 'project';
    }

    // type
    public static function type()
    {
        return 'data';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','user_id'], 'integer'],
            [['title','user_id'], 'required'],
            [['title', 'description'], 'string'],
            [['title'], 'string', 'max' => 250],

        ];
    }

    /**
     * @return array список атрибутов для этой записи
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return ['id', 'title', 'description','user_id'];
    }

    /**
     * @return array Сопоставление для этой модели
     */
    public static function mapping()
    {
        return [
            static::type() => [
                'properties' => [
                    'id' => ['type'=>'long'],
                    'title' => ['type' => 'string'],
                    'description' => ['type' => 'text'],
                    'user_id' => ['type' => 'integer'],
                ]
            ],
        ];
    }

}
