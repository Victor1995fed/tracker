<?php

namespace common\models\elastic;



/**
 * ContactForm is the model behind the contact form.
 */
class Task extends ElasticMain
{

    // index
    public static function index()
    {
        return 'task';
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
            [['id'], 'integer'],
            [['title'], 'required'],
            [['title', 'description'], 'string'],
            [['title'], 'string', 'max' => 250]

        ];
    }

    /**
     * @return array список атрибутов для этой записи
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return ['id', 'title', 'description'];
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
                    'description'    => ['type' => 'text']
                ]
            ],
        ];
    }

}
