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
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']); ?>
    </p>
</div>


<?php
echo ListView::widget([
    'dataProvider' => $listDataProvider,
    'itemView' => '_list',
    'layout' => "{items}",
]);

?>


