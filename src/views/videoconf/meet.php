<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use lispa\amos\videoconference\assets\VideoconferenceAsset;

/**
* @var yii\web\View $this
* @var lispa\amos\videoconference\models\Videoconf $model
*/


VideoconferenceAsset::register($this);

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Videoconferenza'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="videoconf-view col-xs-12">
    <span id="videoconf-id" style="display:none"><?=$model->id?></span>
  <div id="meet" style="height: 600px;"></div>

    <div class="btnViewContainer pull-right">
        <?= Html::a(Yii::t('amoscore', 'Chiudi'), ['/videoconference/videoconf/index'], ['class' => 'btn btn-secondary','id' => "meeting-end"]); ?>    </div>

</div>
