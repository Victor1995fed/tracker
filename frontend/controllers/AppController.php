<?php

namespace frontend\controllers;

use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\elasticsearch\ActiveDataProvider;
use yii\elasticsearch\Query;

class AppController extends AbstractApiController
{

    public $size = 10;
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

    public function actionSearch($page,$str)
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
        ])->highlight(
            [
                "pre_tags"  => "<highlight-string>",
                "post_tags" => "</highlight-string>",
                'fields'=>
                ['content'=>new \stdClass(), 'description'=>new \stdClass(),'title'=>new \stdClass()]
            ])
            ->limit($this->size)->offset($this->size * ($page - 1));


        $command = $query->createCommand();
        $rows = $command->search();
        $result = $this->processingResult($rows);
        $result['totalPage']  = $this->getTotalPage($rows);
        return $result;
    }

    private function getTotalPage($rows)
    {
        $total = $rows['hits']['total']['value'] ?? false;
        if($total === false)
            throw new HttpException(500, serialize($rows));

        if($total == 0)
            throw new HttpException(404, 'Ничего не найдено');

        $totalPage = ceil($total / $this->size);

        return $totalPage;

    }

    private function processingResult($rows)
    {
        $newResult = [];
        foreach ($rows['hits']['hits'] as $k=>$v){
            $newResult['rows'][] = [
                'id'=>$v['_id'],
                'content'=>strip_tags(current($v['highlight'])[0], '<highlight-string>'),
                'index' => $v['_index']
                ];
        }
        return $newResult;
    }


}