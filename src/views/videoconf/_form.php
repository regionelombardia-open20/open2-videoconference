<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-videoconference
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\forms\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use open20\amos\core\forms\Tabs;
use open20\amos\core\forms\CloseSaveButtonWidget;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use open20\amos\videoconference\models\Videoconf;
use open20\amos\videoconference\AmosVideoconference;

/**
 * @var yii\web\View $this
 * @var open20\amos\videoconference\models\Videoconf $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<?php
$js = <<<JS
    $(".schedule-videoconference").click(function(){
        if($(this).is(':checked')) {
           $('#date-hour-container').show();
        }
        else {
             $('#date-hour-container').hide();
             $('#_date_begin-disp').val('');
             $('#_date_begin').val('');
             $('#_date_end').val('');
             $('#_date_end-disp').val('');
        }
    });
JS;

$this->registerJs($js);

?>
<div class="videoconf-form col-xs-12 nop">

    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'videoconf_' . ((isset($fid)) ? $fid : 0),
            'data-fid' => (isset($fid)) ? $fid : 0,
            'data-field' => ((isset($dataField)) ? $dataField : ''),
            'data-entity' => ((isset($dataEntity)) ? $dataEntity : ''),
            'class' => ((isset($class)) ? $class : ''),
            'enctype' => 'multipart/form-data' // important
        ]
    ]);
    ?>

    <!--   DETTAGLIO  -->
    <div class="row m-b-15">
        <div class="col-xs-12">
            <?php
            if (!$model->isNewRecord) {
                echo Html::a(\open20\amos\core\icons\AmosIcons::show('accounts') .AmosVideoconference::t('amosvideoconference','Gestione partecipanti'),
                    ['/videoconference/videoconf/participants','id' => $model->id, 'redirectUrl' => '/videoconference/videoconf/update?id='.$model->id],
                    [
                        'class' => 'btn btn-primary pull-right',
                        'title' => AmosVideoconference::t('amosvideoconference','Gestione partecipanti'),
                        'data-confirm' =>  AmosVideoconference::t('amosvideoconference','Stai per lasciare la pagina, verifica prima di aver salvato i tuoi dati. Vuoi procedere?')
                    ]);
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?php if(empty($model->title)) $model->title = \open20\amos\videoconference\AmosVideoconference::t('amosvideoconference', 'Videocall #') . date('YmdHis'); ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php if($model->isNewRecord || $model->status == Videoconf::STATUS_FUTURE) {?>
    <div class="row">
        <?php if(!$model->sheduledVideoconfCheckBox) {
            $display = 'display:none;';
        }else {
            $model->sheduledVideoconfCheckBox= 1;
            $display = '';
        }?>
        <div class="col-lg-4 col-sm-12">
            <?= $form->field($model, 'sheduledVideoconfCheckBox')->checkbox(['class' => 'schedule-videoconference'])->label(\open20\amos\videoconference\AmosVideoconference::t('amosvideoconference', 'Programma videoconferenza'));?>
        </div>
    </div>
    <div id = "date-hour-container" class="row" style="<?= $display ?>">
        <div class="col-lg-4 col-sm-12">
            <?= $form->field($model, 'begin_date_hour')->widget(DateControl::className(), [
//                    'displayFormat' => 'php:d-M-Y H:i:s',
                'type' => DateControl::FORMAT_DATETIME,
                 'options' => [
                        'id' => '_date_begin'
                 ]
            ]) ?>
        </div>

        <div class="col-lg-4 col-sm-12">
            <?= $form->field($model, 'end_date_hour')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATETIME,
                'options' => [
                    'id' => '_date_end'
                ]
            ]) ?>
        </div>

        <div class="col-lg-4 col-sm-12">
            <?= $form->field($model, 'notification_before_conference')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'description')->widget(\open20\amos\core\forms\TextEditorWidget::className(), [
                'options' => ['placeholder' => AmosVideoconference::t('amosvideoconference', 'Inserisci...')],
                'clientOptions' => [
                    'lang' => substr(Yii::$app->language, 0, 2)
                ]
            ]);
            ?>
        </div>
    </div>

    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>


