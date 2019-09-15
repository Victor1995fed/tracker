<?php

use frontend\models\Category;
use frontend\models\Priority;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>




<table class="table table-bordered" style="background-color:#eeeeee">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Проект</th>
        <th scope="col">Категория</th>
        <th scope="col">Тема</th>
        <th scope="col">Приоритет</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?=$model->id ?></td>
        <td>test</td>
        <td><?=$model->category->title ?></td>
        <td><?=$model->title ?></td>
        <td><?=$model->priority->title ?></td>
    </tr>
    <tr style="background-color:#fff; border: none"><th style="background-color:#fff; border: none" colspan="5"><?= Html::a('Просмотреть',['view?id='.$model->id]) ?></th></tr>
    <tbody>

</table>
<br>

