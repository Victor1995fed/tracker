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
            [['id','user_id'], 'integer'],
            [['content'], 'required'],
            [['title', 'content'], 'string'],
            [['title'], 'string', 'max' => 250],

        ];
    }

    /**
     * @return array список атрибутов для этой записи
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return ['id', 'title', 'content','user_id'];
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
                    'user_id' => ['type'=>'integer']
                ]
            ],
        ];
    }

}
