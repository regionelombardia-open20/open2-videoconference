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
use open20\amos\core\views\DataProviderView;
use open20\amos\core\utilities\ViewUtility;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\videoconference\AmosVideoconference;

use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\VideoconfSearch $model
 */
$actionColumn = '{join}{view}{update}{participants}{delete}';

$this->title = AmosVideoconference::t('amosvideoconference', 'Videoconferenza');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="videoconf-index">
    <?php // echo $this->render('_search', ['model' => $model]);  ?>

    <p>
        <?php /* echo Html::a(AmosVideoconference::t('amosvideoconference', 'Nuova videoconferenza istantanea', [
          'modelClass' => 'Videoconf',
          ]), ['create'], ['class' => 'btn btn-amministration-primary'])
         */ ?>
    </p>

    <?php
    //pr(Yii::$app->formatter);
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'currentView' => $currentView,
        'gridView' => [
            //'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
            'columns' => [
                [
                    'class' => 'kartik\grid\ExpandRowColumn',
                    'expandAllTitle' => 'Sotto progetti',
                    'allowBatchToggle' => false,
                    'header' => '',
                    'headerOptions' => [
                        'style' => 'white-space: nowrap;'
                    ],
                    'collapseIcon' => AmosIcons::show('chevron-down'),
                    'expandIcon' => AmosIcons::show('chevron-right'),
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function ($model, $key, $index, $column) {
                        return \kartik\grid\GridView::ROW_COLLAPSED;
                    },
                    'detailUrl' => \yii\helpers\Url::to(['/videoconference/videoconf-ajax/more-info'])
                ],
                //['class' => 'yii\grid\SerialColumn'],
                'title',
                'description:html',
                [
                    'label' => AmosVideoconference::t('amosvideoconference', 'Num. Partecipanti'),
                    'value' => function ($model){
                        return count($model->videoconfUsersMms);
                    },
                ],
                /*  [
                  'attribute' => 'begin_date_hour',
                  'format' => 'datetime'
                  ],
                  [
                  'attribute' => 'end_date_hour',
                  'format' => 'datetime'
                  ], */
                //'status',
                [
                    'attribute' => 'begin_date_hour',
                    'format' => 'html',
                    'value' => function ($model) {
                        if ($model->begin_date_hour) {
                            return Yii::$app->formatter->asDatetime($model->begin_date_hour, ViewUtility::formatDateTime());
                        }
                        
                        return '';
                    }
                ],
                [
                    'attribute' => 'end_date_hour',
                    'format' => 'html',
                    'value' => function ($model) {
                        if ($model->end_date_hour) {
                            return Yii::$app->formatter->asDatetime($model->end_date_hour, ViewUtility::formatDateTime());
                        }
                        
                        return '';
                    }
                ],
               //  ['attribute'=>'created_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'updated_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            ['attribute'=>'deleted_at','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']], 
//            'created_by', 
//            'updated_by', 
//            'deleted_by', 
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                    'template' => $actionColumn,
                    'buttons' => [
                        'join' => function ($url, $model) {
                            if ($model->isVideoConferenceRoomActive()) {
                                $action = '/videoconference/videoconf/meet?id_room=' . $model->id_room_videoconference;
                                $options = [
                                    'title' => AmosVideoconference::t('amosvideoconference', 'Entra nella videoconferenza'),
                                    'class' => 'bk-btnMore',
                                ];
                                return Html::a(AmosIcons::show('videocam', ['class' => 'btn btn-tool-secondary']), $action, $options);
                            }
                        },
                        'participants' => function ($url, $model) {
                            if ((\Yii::$app->user->can('VIDEOCONF_UPDATE',['model'=> $model]))) {
                                return Html::a(AmosIcons::show('accounts-add'), ['/videoconference/videoconf/participants', 'id' => $model->id],   [
                                    'class' => 'btn btn-tools-secondary',
                                    'title' => AmosVideoconference::t('amosvideoconference',"Partecipanti"),
                                    'data-pjax' => '0'
                                ]);
                            }
                        },
                    ]
                ],
            ],
        ],
            /* 'listView' => [
              'itemView' => '_item'
              'masonry' => FALSE,

              // Se masonry settato a TRUE decommentare e settare i parametri seguenti
              // nel CSS settare i seguenti parametri necessari al funzionamento tipo
              // .grid-sizer, .grid-item {width: 50&;}
              // Per i dettagli recarsi sul sito http://masonry.desandro.com

              //'masonrySelector' => '.grid',
              //'masonryOptions' => [
              //    'itemSelector' => '.grid-item',
              //    'columnWidth' => '.grid-sizer',
              //    'percentPosition' => 'true',
              //    'gutter' => '20'
              //]
              ],
              'iconView' => [
              'itemView' => '_icon'
              ],
              'mapView' => [
              'itemView' => '_map',
              'markerConfig' => [
              'lat' => 'domicilio_lat',
              'lng' => 'domicilio_lon',
              'icon' => 'iconaMarker',
              ]
              ],
              'calendarView' => [
              'itemView' => '_calendar',
              'clientOptions' => [
              //'lang'=> 'de'
              ],
              'eventConfig' => [
              //'title' => 'titoloEvento',
              //'start' => 'data_inizio',
              //'end' => 'data_fine',
              //'color' => 'coloreEvento',
              //'url' => 'urlEvento'
              ],
              'array' => false,//se ci sono più eventi legati al singolo record
              //'getEventi' => 'getEvents'//funzione da abilitare e implementare nel model per creare un array di eventi legati al record
              ] */
    ]);
    ?>

</div>
