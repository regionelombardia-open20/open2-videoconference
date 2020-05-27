<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;

/**
* @var yii\web\View $this
* @var open20\amos\videoconference\models\Videoconf $model
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
