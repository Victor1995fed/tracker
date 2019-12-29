<?php

namespace common\models\elastic;



/**
 * ContactForm is the model behind the contact form.
 */
class Note extends ElasticMain
{

    // index
    public static function index()
    {
        return 'note';
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
            [['title', 'content'], 'string'],
            [['title'], 'string', 'max' => 250],
            [['date_create'], 'date', 'format' => 'yyyy-MM-dd']

        ];
    }

    /**
     * @return array список атрибутов для этой записи
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return ['id', 'title', 'content', 'date_create'];
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
                    'content'    => ['type' => 'text'],
                    'date_create' => ['type' => 'date']
                ]
            ],
        ];
    }

}
