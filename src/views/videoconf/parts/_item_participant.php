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

use yii\helpers\Html;

$adminModuleName  = \open20\amos\admin\AmosAdmin::getModuleName();
?>
<div class="item-participant">
    <?php if (!$readOnly) { ?>
        <?php if ($participants) { ?>
            <?= \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('close rounded-icon rounded-danger text-white'), 'javascript:void(0)', ['class' => 'delete-users btn btn-xs btn-icon', 'data-key' => $model->user_id]) ?>
        <?php } else { ?>
            <?php
            $value = false;
            if (in_array($model->id, $participants_user_profile_ids)) {
                $value = true;
            } ?>
            <?= \yii\helpers\Html::checkbox('selected-users', $value, ['value' => $model->id, 'class' => 'checkbox-user']) ?>
        <?php } ?>
    <?php } ?>
    <div class="avatar-wrapper avatar-extra-text" data-key="<?= $model->id ?>">
        <div class="avatar size-xl">
            <?php echo \open20\amos\admin\widgets\UserCardWidget::widget(['model' => $model, 'avatarDimension' => 'table_small', 'avatarXS' => true]); ?>
        </div>
        <div class="extra-text">
            <h4><?= Html::a($model->cognome . ' ' . $model->nome, ["/$adminModuleName/user-profile/view", 'id' => $model->id], ['title' => 'Visualizza profilo di ' . $model->cognome . ' ' . $model->nome, 'target' => '_blank']) ?></h4>
        </div>
    </div>
</div>