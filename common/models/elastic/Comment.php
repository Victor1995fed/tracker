<?php

namespace common\models\elastic;



/**
 * ContactForm is the model behind the contact form.
 */
class Comment extends ElasticMain
{

    // index
    public static function index()
    {
        return 'comment';
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
            [['content'], 'string'],
        ];
    }

    /**
     * @return array список атрибутов для этой записи
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return ['id',  'content'];
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
                    'content'    => ['type' => 'text'],
                ]
            ],
        ];
    }

}
