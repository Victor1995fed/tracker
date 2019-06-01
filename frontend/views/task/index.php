<?php
use yii\helpers\Html;
use frontend\models\Task;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
$this->title = 'Ваши задачи';

?>
<div class="site-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить задачу', ['create'], ['class' => 'btn btn-success']); ?>
        <?= Html::a('Добавить категорию', ['category/create'], ['class' => 'btn btn-success']); ?>
        <?= Html::a('Добавить проект', ['project/create'], ['class' => 'btn btn-success']); ?>
    </p>
</div>


<?php
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_list',
    'pager' => [ // постраничная разбивка
        'firstPageLabel' => 'Первая', // ссылка на первую страницу
        'lastPageLabel' => 'Последняя', // ссылка на последнюю странцу
        'nextPageLabel' => 'Следующая', // ссылка на следующую странцу
        'prevPageLabel' => 'Предыдущая', // ссылка на предыдущую странцу
        'maxButtonCount' => 5, // количество отображаемых страниц
    ],
    // выводим постраничную навигацию вначале и в конце списка, общее количесвто элементов и количестов элементов показанных на странице и сам список
    'summary' => 'Показано {count} из {totalCount}', // шаблон для summary
    'summaryOptions' => [  // опции для раздела количество элементов
        'tag' => 'span', // заключаем summary в тег span
        'class' => 'summary' // добавлем класс summary
    ],
]);

?>


