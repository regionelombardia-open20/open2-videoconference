<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace open20\amos\videoconference\models\base;

use open20\amos\notificationmanager\models\Notification;
use Yii;
use open20\amos\videoconference\AmosVideoconference;

/**
 * This is the base-model class for table "videoconf".
 *
 * @property integer $id
 * @property string $id_room_videoconference
 * @property string $status
 * @property string $title
 * @property string $description
 * @property string $begin_date_hour
 * @property string $end_date_hour
 * @property integer $notification_before_conference
 * @property integer $reminder_sent
 * @property integer $community_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\videoconference\models\VideoconfUsersMm[] $videoconfUsersMms
 */
class Videoconf extends \open20\amos\core\record\Record
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'videoconf';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['begin_date_hour', 'end_date_hour', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['created_by', 'updated_by', 'deleted_by', 'notification_before_conference', 'reminder_sent', 'community_id'], 'integer'],
            [['id_room_videoconference', 'status', 'title'], 'string', 'max' => 255],
            [['begin_date_hour'], 'checkBeginDateHour'],
            [['end_date_hour'], 'checkEndDateHour']
        ];
    }

    /**
     *
     */
    public function checkBeginDateHour()
    {
        $today = new \DateTime();
        if(!empty($this->begin_date_hour)) {
            $beginVideoConference = new \DateTime($this->begin_date_hour);
            if($beginVideoConference <= $today) {
                $this->addError('begin_date_hour', AmosVideoconference::t('amosvideoconference', '#checkbegin_date_hour'));
            }
        }
    }
    
    /**
     * 
     */
    public function checkEndDateHour()
    {
        if(!empty($this->begin_date_hour)) 
        {
            $beginVideoConference = new \DateTime($this->begin_date_hour);
            if(!empty($this->end_date_hour)) 
            {
                $endVideoConference = new \DateTime($this->end_date_hour);
                if($beginVideoConference > $endVideoConference) 
                {
                    $this->addError('end_date_hour', AmosVideoconference::t('amosvideoconference', '#checkend_date_hour'));
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosVideoconference::t('amosvideoconference', 'ID'),
            'id_room_videoconference' => AmosVideoconference::t('amosvideoconference', 'Id stanza Jitsi'),
            'status' => AmosVideoconference::t('amosvideoconference', 'Stato'),
            'title' => AmosVideoconference::t('amosvideoconference', 'Titolo'),
            'description' => AmosVideoconference::t('amosvideoconference', 'Descrizione'),
            'begin_date_hour' => AmosVideoconference::t('amosvideoconference', 'Data e ora di inizio'),
            'end_date_hour' => AmosVideoconference::t('amosvideoconference', 'Data e ora di fine'),
            'notification_before_conference' => AmosVideoconference::t('amosvideoconference', 'Notifica prima dell\'inizio (minuti)'),
            'reminder_sent' => AmosVideoconference::t('amosvideoconference', 'Promemoria inviato'),
            'created_at' => AmosVideoconference::t('amosvideoconference', 'Creato il'),
            'updated_at' => AmosVideoconference::t('amosvideoconference', 'Aggiornato il'),
            'deleted_at' => AmosVideoconference::t('amosvideoconference', 'Cancellato il'),
            'created_by' => AmosVideoconference::t('amosvideoconference', 'Creato da'),
            'updated_by' => AmosVideoconference::t('amosvideoconference', 'Aggiornato da'),
            'deleted_by' => AmosVideoconference::t('amosvideoconference', 'Cancellato da'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideoconfUsersMms()
    {
        return $this->hasMany(\open20\amos\videoconference\models\VideoconfUsersMm::className(), ['videoconf_id' => 'id']);
    }


}