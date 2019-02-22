<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var lispa\amos\videoconference\models\Videoconf $model
*/

$this->title = Yii::t('cruds', 'Aggiorna', [
    'modelClass' => 'Videoconf',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Videoconf'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'] []= '';
?>
<div class="videoconf-update">

    <?= $this->render('_form', [
        'model' => $model,
        'model_partecipanti' => $model_partecipanti,
        'partecipanti' => $partecipanti,
    ]) ?>

</div>
