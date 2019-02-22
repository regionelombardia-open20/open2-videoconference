<?php

use lispa\amos\core\helpers\Html;

/**
* @var yii\web\View $this
* @var lispa\amos\videoconference\models\Videoconf $model
*/

$this->title = Yii::t('cruds', 'Crea', [
    'modelClass' => 'Videoconf',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Videoconf'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videoconf-create">
    <?= $this->render('_form', [
        'model' => $model,
        'model_partecipanti' => $model_partecipanti,
        'partecipanti' => $partecipanti,
    ]) ?>

</div>
