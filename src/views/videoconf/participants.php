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

use open20\amos\core\forms\ActiveForm;
use yii\helpers\Html;
use open20\amos\videoconference\AmosVideoconference;

$this->title = AmosVideoconference::t('amosvideoconference', 'Seleziona partecipanti per il meeting - {title}',['title' => $model->title]);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;?>

<div class="dove-evento my-4">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->render('parts/_form_webmeeting_participant', [
        'form' => $form,
        'model' => $model,
        'modelSearch' => $modelSearch,
        'dataProvider' => $dataProvider,
        'participants_user_profile_ids' => $participants_user_profile_ids,
        'countInvitationUsers' => $countInvitationUsers,
        'dataProviderInvitationUsers' => $dataProviderInvitationUsers,
    ]) ?>

    <div class="buttons">
        <?php
        $urlBack = '/videoconference/videconf/index';
        if(!empty($redirectUrl)){
            $urlBack = $redirectUrl;
        }
        ?>
        <?php echo Html::a(\Yii::t('amosevents', 'Indietro'),$urlBack, ['class' => 'btn btn-secondary']) ?>

        <?php echo Html::submitButton(\Yii::t('amosevents', 'Salva le modifiche'), ['class' => 'btn btn-primary ml-auto pull-right']) ?>
    </div>
    <?php $form = ActiveForm::end(); ?>
</div>