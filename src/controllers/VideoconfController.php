<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace open20\amos\videoconference\controllers;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\notificationmanager\models\NotificationsRead;
use open20\amos\videoconference\models\base\UserProfileForm;
use open20\amos\videoconference\models\VideoconfUsersMm;
use open20\amos\videoconference\utils\EmailUtil;
use Yii;
use open20\amos\videoconference\models\Videoconf;
use open20\amos\videoconference\models\VideoconfSearch;
use open20\amos\core\controllers\CrudController;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use open20\amos\videoconference\AmosVideoconference;

/**
 * This is the class for controller "VideoconfController".
 */
class VideoconfController extends CrudController
{
    public $model_partecipanti;
    public $partecipanti;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
                [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'meet',
                                'json-videoconf-data',
                            ],
                            'roles' => ['VIDEOCONF_READ']
                        ],
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
        ]);
        return $behaviors;
    }

    /**
     * manda la mail agli utenti collegati alla videoconferenza
     */
    public function actionSendMail()
    {

        // trova la videoconferenza e gli utenti collegati
        $videoconfId = Yii::$app->request->get('id');

        $videoconference = Videoconf::findOne($videoconfId);
        if ($videoconference) {
            $collegati = $videoconference->getVideoconfUsersMms()->all();
            if (\is_array($collegati)) {
                foreach ($collegati as $u) {
                    $sent = EmailUtil::sendEmailPartecipant($videoconference, $u->user_id);
                }
            }
        }
    }

    public function init()
    {
        $this->setModelObj(new Videoconf());
        $this->setModelSearch(new VideoconfSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}'.Html::tag('p', Yii::t('amoscore', 'Table')),
                    [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /* 'list' => [
              'name' => 'list',
              'label' => Yii::t('amoscore', '{iconaLista}'.Html::tag('p',Yii::t('amoscore', 'List')), [
              'iconaLista' => AmosIcons::show('view-list')
              ]),
              'url' => '?currentView=list'
              ],
              'icon' => [
              'name' => 'icon',
              'label' => Yii::t('amoscore', '{iconaElenco}'.Html::tag('p',Yii::t('amoscore', 'Icons')), [
              'iconaElenco' => AmosIcons::show('grid')
              ]),
              'url' => '?currentView=icon'
              ],
              'map' => [
              'name' => 'map',
              'label' => Yii::t('amoscore', '{iconaMappa}'.Html::tag('p',Yii::t('amoscore', 'Map')), [
              'iconaMappa' => AmosIcons::show('map')
              ]),
              'url' => '?currentView=map'
              ],
              'calendar' => [
              'name' => 'calendar',
              'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
              //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
              'label' => Yii::t('amoscore', '{iconaCalendario}'.Html::tag('p',Yii::t('amoscore', 'Calendar')), [
              'iconaMappa' => AmosIcons::show('calendar')
              ]),
              'url' => '?currentView=calendar'
              ], */
        ]);

        parent::init();
    }

    public function actionTest()
    {
        //$videoconference = new \open20\amos\videoconference\models\Videoconf();
        $videoconference = \open20\amos\videoconference\models\Videoconf::findOne(1);
        // pr($videoconference->toArray(), 'videoconference');//exit;
        $users           = $videoconference->getVideoconfUsersMms()->all();
        // $users = $videoconference->getVideoconfUsers();

        foreach ($users as $u) {
            pr($u->toArray(), '$u relazione'); //exit;
            $userProfile = \open20\amos\admin\models\UserProfile::findOne($u->user_id);
            // pr($userProfile->toArray(), '$userProfile');//exit;
            $user        = $userProfile->getUser()->one();
            // pr($user->toArray(), '$user');//exit;
            $userEmail   = $userProfile->getUser()->one()->email;
            //  print "Email: $userEmail;<br />";
        }
        //pr($users->toArray(), '$users');exit;/****/
    }

    /**
     * Lists all Videoconf models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        Url::remember();

        //label pulsante "nuovo"
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosVideoconference::t('amosvideoconference', 'Nuova Videoconferenza'),
        ];

        $filter = ArrayHelper::merge(Yii::$app->request->getQueryParams(), [
        ]);
        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single Videoconference.
     * @param integer $id
     * @return mixed
     */
    public function actionMeet($id_room)
    {
        $record = Videoconf::findOne(['id_room_videoconference' => $id_room]);
        if (is_object($record) && $record->id) {
            $id    = $record->id;
            $model = $this->findModel($id);
        }
        return $this->render('meet', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionJsonVideoconfData($id)
    {
        $model = $this->findModel($id);

        $jitsiDomain           = Yii::$app->getModule('videoconference')->jitsiDomain ?: null;

        $loggedUser        = \Yii::$app->getUser()->identity;
        $loggedUserProfile = $loggedUser->getProfile();

        //pr($loggedUserProfile);
        $data = [
            "domain" => $jitsiDomain,
            "displayName" => $loggedUserProfile->nome." ".$loggedUserProfile->cognome,
            "roomName" => $model->id_room_videoconference,
            "titolo" => $model->title,
            "avatar" => $loggedUserProfile->getAvatarWebUrl()
        ];
        return json_encode($data);
    }

    /**
     * Displays a single Videoconf model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new Videoconf model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = "@vendor/open20/amos-core/views/layouts/form";
        $model        = new Videoconf;

        //carico i partecipanti ottenendo le variabili $this->model_partecipanti e $this->partecipanti popolate
        $this->loadPartecipanti($id = null);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //creo una transazione in modo che se non salvasse correttamente i partecipanti, non si creerebbe un record video
            $transaction = \Yii::$app->db->beginTransaction();

            $scope = $this->getScopeCommunity();
            if ($scope) {
                $model->community_id = $scope;
            }

            if ($model->save()) {

                $this->model_partecipanti->videoconf_id = $model->id;

                //aggiorno i partecipanti della conferenza con quelli in post
                $this->setPartecipanti(Yii::$app->request->post()[$this->model_partecipanti->getModelName()]['ids']);

                //salvo i partecipanti e creo una notification (se non già inviata) per inviare successivamente la mail
                $saved_partecipanti = $this->model_partecipanti->saveUser2Videoconference();

                if ($saved_partecipanti) {
                    $transaction->commit();

                    //Notifica i partecipanti ancora da avvisare
                    $notifiche = $model->getNotification2Notify();
                    $this->notifyUsers($notifiche);

                    Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item created'));
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();

                    Yii::$app->getSession()->addFlash('danger',
                        Yii::t('amoscore', 'Item not created, check partecipants'));
                    return $this->render('create',
                            [
                            'model' => $model,
                            'model_partecipanti' => $this->model_partecipanti,
                            'partecipanti' => $this->partecipanti,
                    ]);
                }
            } else {
                $transaction->rollBack();
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
                return $this->render('create',
                        [
                        'model' => $model,
                        'model_partecipanti' => $this->model_partecipanti,
                        'partecipanti' => $this->partecipanti,
                ]);
            }
        } else {
            return $this->render('create',
                    [
                    'model' => $model,
                    'model_partecipanti' => $this->model_partecipanti,
                    'partecipanti' => $this->partecipanti,
            ]);
        }
    }

    /**
     * Updates an existing Videoconf model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = "@vendor/open20/amos-core/views/layouts/form";
        $model        = $this->findModel($id);

        //carico i partecipanti ottenendo le variabili $this->model_partecipanti e $this->partecipanti popolate
        $this->loadPartecipanti($id);

        /* pr(Yii::$app->request->post(), "post"); */
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //aggiorno i partecipanti della conferenza con quelli in post
            $this->setPartecipanti(Yii::$app->request->post()[$this->model_partecipanti->getModelName()]['ids']);

            if ($model->save()) {
                //salvo i partecipanti e creo una notification (se non già inviata) per inviare successivamente la mail
                $saved_partecipanti = $this->model_partecipanti->saveUser2Videoconference();

                //Notifica i partecipanti ancora da avvisare
                $notifiche = $model->getNotification2Notify();
                $this->notifyUsers($notifiche);

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
                return $this->render('update',
                        [
                        'model' => $model,
                        'model_partecipanti' => $this->model_partecipanti,
                        'partecipanti' => $this->partecipanti,
                ]);
            }
        } else {
            $model->sheduledVideoconfCheckBox = !empty($model->begin_date_hour);
            return $this->render('update',
                    [
                    'model' => $model,
                    'model_partecipanti' => $this->model_partecipanti,
                    'partecipanti' => $this->partecipanti,
            ]);
        }
    }

    /**
     * Deletes an existing Videoconf model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
//si può sostituire il  delete() con forceDelete() in caso di SOFT DELETE attiva 
//In caso di soft delete attiva e usando la funzione delete() non sarà bloccata
//la cancellazione del record in presenza di foreign key quindi 
//il record sarà cancelleto comunque anche in presenza di tabelle collegate a questo record
//e non saranno cancellate le dipendenze e non avremo nemmeno evidenza della loro presenza
//In caso di soft delete attiva è consigliato modificare la funzione oppure utilizzare il forceDelete() che non andrà 
//mai a buon fine in caso di dipendenze presenti sul record da cancellare
            $model->delete();
            if (!empty($model->deleted_at)) {
                //cancello tutti partecipanti eventualmente presenti
                VideoconfUsersMm::deleteAll(['videoconf_id' => $id]);

                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
        }
        return $this->redirect(['index']);
    }

    public function loadPartecipanti($videoconf_id)
    {
        $this->model_partecipanti               = new UserProfileForm();
        $this->model_partecipanti->videoconf_id = $videoconf_id;
        //$this->model_partecipanti->load(\Yii::$app->request->post());
        /* if ($this->model_partecipanti->load(\Yii::$app->request->post())) {
          if ($this->model_partecipanti->validate()) {
          //TODO
          //$model->saveFavorites();
          //return $this->redirect(['index']);
          }
          } */
        //carica i partecipanti (da DB) per la conferenza settata: popola $this->ids
        $this->model_partecipanti->loadUsers();

        $this->partecipanti = UserProfileForm::getAvailableUsers();
    }

    /**
     * @param $ids
     * @param bool $videoconf_id
     * Setta gli id degli user_profile ricevuti
     */
    public function setPartecipanti($ids, $videoconf_id = false)
    {
        if (!$this->model_partecipanti) {
            $this->model_partecipanti = new UserProfileForm();
        }
        $this->model_partecipanti->ids = $ids;
    }

    public function notifyPartecipantiProgrammed($videoconf_id)
    {
        $VideoconfRecord = Videoconf::findOne(['id' => $videoconf_id]);

        if (is_object($VideoconfRecord) && $VideoconfRecord->id) {
            $a = $VideoconfRecord->getNotification2Notify(/* $VideoconfRecord->id */);
        }
    }

    /**
     * @param $notificaRecord
     * @return bool
     * dato un record notification, crea un corrispondente record NotificationRead
     */
    protected function createNotificationRead($notificaRecord)
    {
        if (is_object($notificaRecord) && $notificaRecord->id) {
            $notiRead                  = new NotificationsRead();
            $notiRead->user_id         = $notificaRecord->user_id;
            $notiRead->notification_id = $notificaRecord->id;
            //pr($notiRead->toArray(), "SALVEREI notification READ");
            $notiRead->save(false);
        }

        return false;
    }

    /**
     * @param $notifiche Array Records notification
     * Dato un array contentente i record notification da notificare: invia la mail di notifica agli utenti settati nel record in questione
     */
    protected function notifyUsers($notifiche)
    {
        foreach ($notifiche as $k_n => $notificaRecord) {
            //pr($notificaRecord->toArray(), "---- notificare");

            $model_record = $notificaRecord->class_name;
            $id_record    = $notificaRecord->content_id;
            $id_user      = $notificaRecord->user_id;

            //$userProfileModel = AmosAdmin::instance()->createModel('UserProfile');
            $userRecord = User::findOne($id_user);

            //risalgo al record videoconferenza salvato nella notifica
            $videoconfRecord = $model_record::findOne($id_record);

            //invio email
            $send = EmailUtil::sendEmailPartecipant($videoconfRecord, $userRecord);

            //se la email è stata inviata, scrivo in notificationRead in modo da settare la notifica come 'letta'
            if ($send) {
                $this->createNotificationRead($notificaRecord);
            }
        }
    }

    /**
     * Create a video conference between to users
     *
     * @param $user_profile_id_sender
     * @param $user_profile_id_receiver
     * @return null|string
     */
    public function actionCreateVideoConferenceAjax($user_profile_id_sender, $user_profile_id_receiver)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $profile                     = UserProfile::find()->andWhere(['user_id' => $user_profile_id_sender])->one();
        $videoconfList               = Videoconf::find()
                ->innerJoinWith('videoconfUsersMms')
                ->andWhere(['videoconf.created_by' => $profile->user_id])
                ->andWhere(['status' => Videoconf::STATUS_RUNNING])
                ->andWhere(['videoconf_users_mm.user_profile_id' => $user_profile_id_receiver])->all();

        $model = null;
        /** @var  $videoconf Videoconf */
        foreach ($videoconfList as $videoconf) {
            if ($videoconf->isVideoConferenceRoomActive() && count($videoconf->videoconfUsersMms) == 2) {
                $model = $videoconf;
            }
        }
        // if there is a videconference is between the two users  already active
        if (!empty($model)) {
            return $model->id_room_videoconference;
        } else {
            // create a new videoconference between two users
            $model        = new Videoconf();
            $model->title = "Videoconference ".$user_profile_id_sender."-".$user_profile_id_receiver;
            if ($model->save()) {
                $partecipante1                  = new VideoconfUsersMm();
                $partecipante1->videoconf_id    = $model->id;
                $partecipante1->user_profile_id = $user_profile_id_sender;

                $partecipante2                  = new VideoconfUsersMm();
                $partecipante2->videoconf_id    = $model->id;
                $partecipante2->user_profile_id = $user_profile_id_receiver;

                if ($partecipante1->save() && $partecipante2->save()) {
                    return $model->id_room_videoconference;
                }
            }
        }

        return null;
    }

    /**
     * @return null
     */
    public function getScopeCommunity()
    {
        $moduleCwh = Yii::$app->getModule('cwh');
        if (!is_null($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                return $scope['community'];
            }
        }
        return null;
    }
}
