<?php

namespace frontend\controllers;

use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\elasticsearch\ActiveDataProvider;
use yii\elasticsearch\Query;

class AppController extends AbstractApiController
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
                'search'  => ['get'],
            ],
        ];

        return $behaviors;
    }

    public function actionSearch($str)
    {
        $query = new Query;
        $query->query([
            'bool'=>[
                'must' => [
                    'multi_match' => [
                        'query'=>$str,
                        'fields'=>
                            [
                                'content',
                                'description',
                                'title'
                            ],
                        'type'=>'best_fields'
                    ]
                ],
                'filter'=>
                [
                    'term'=>[
                        'user_id'=>\Yii::$app->user->identity->id
                    ]
                ]

            ]
        ])->limit(10);


        $command = $query->createCommand();
        $rows = $command->search();
        return $rows;
    }
}