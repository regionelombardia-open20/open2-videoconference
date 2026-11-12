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
use yii\widgets\DetailView;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use open20\amos\videoconference\models\Videoconf;

/**
 * @var yii\web\View $this
 * @var open20\amos\videoconference\models\Videoconf $model
 */
$this->title = $model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('cruds', 'Videoconferenza'), 'url' => ['index']];
$this->params['breadcrumbs'] []= '';
?>
<div class="videoconf-view col-xs-12">


    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description:html',
            [
                'label' => 'Num. Partecipanti',
                'value' => function ($model) {
                    return count($model->videoconfUsersMms);
                }
            ],
            [
                'attribute' => 'begin_date_hour',
                'format' => [
                    'date',
                    (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'php:d-m-Y H:i'
                ],
                'visible' => ($model->status == Videoconf::STATUS_FUTURE),
            ],
            [
                'attribute' => 'end_date_hour',
                'format' => ['date', (isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A'],
                'visible' => ($model->status == Videoconf::STATUS_FUTURE),
            ],
            [
                'attribute' => 'notification_before_conference',
                'visible' => ($model->status == Videoconf::STATUS_FUTURE),
            ],
          
            [
                'label' => \Yii::t('app', 'Utenti partecipanti'),
                'format' => 'html',
                'value' => function($model) {
                            $participants = "";
                            foreach ($model->videoconfUsersMms as $user) {
                                $participants .= $user->userProfile . "<br>";
                            }
                            return $participants;
                        }
            ],
        ],
    ])
    ?>

    <div class="btnViewContainer pull-right">
<?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?>    </div>

</div>
