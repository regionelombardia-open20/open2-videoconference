<?php
namespace lispa\amos\videoconference\controllers;
//namespace console\controllers;


use lispa\amos\admin\models\UserProfile;
use lispa\amos\videoconference\AmosVideoconference;
use lispa\amos\videoconference\models\Videoconf;
use lispa\amos\videoconference\models\VideoconfUsersMm;
use lispa\amos\videoconference\utils\EmailUtil;
use yii\console\Controller;
use yii\helpers\Console;
use Yii;

class CronController extends Controller
{

    public function actionStart_video_conference(){
        $programmedConferences = Videoconf::find()->andWhere(['status' => Videoconf::STATUS_FUTURE])->all();

        Console::stdout("*** CRON START VIDEO CONFERENCE ***" . "\n");
        $count = 0;
        /** @var  $videoConference Videoconf */
        foreach ($programmedConferences as $videoConference) {
            if($videoConference->isVideoConferenceActivable() && empty($videoConference->id_room_videoconference)) {
                $videoConference->setIdRoomVideoconference();
                if($videoConference->save(false)) {
                    Console::stdout("videoconferences started: ID:" . print_r($videoConference->id, true) . " title: " . print_r($videoConference->title, true) . "\n");
                    $count++;
                }
            }
        }

        Console::stdout("n. videoconferences started: " . print_r($count,true) . "\n");
        Console::stdout("*** END CRON ***" . "\n");
    }


    /**
     *
     */
    public function actionSend_email_reminder()
    {
        $module = \Yii::$app->getModule(AmosVideoconference::getModuleName());
        // find scheduled videoconferences which haven't been sent a reminder
        $programmedConferences = Videoconf::find()
            ->andWhere(['status' => Videoconf::STATUS_FUTURE])
            ->andWhere(['reminder_sent' => 0])->all();
        $now = new \DateTime();

        Console::stdout("*** CRON SEND EMAIL REMINDER VIDEOCONFERENCE ***" . "\n");
        $count = 0;
        /** @var  $videoconference Videoconf */
        foreach ($programmedConferences as $videoconference) {
            $notification_before_conference = !empty($videoconference->notification_before_conference) ? $videoconference->notification_before_conference : $module->minuteReminder;
            $intervalReminder =  new \DateInterval('PT' . trim($notification_before_conference) . 'M');
            $dateHourReminder = new \DateTime($videoconference->begin_date_hour);
            $dateHourReminder->sub($intervalReminder);

            if($videoconference->status == Videoconf::STATUS_FUTURE) {
                if($now >= $dateHourReminder) {
                    $collegati = $videoconference->getVideoconfUsersMms()->all();
                    /** @var  $u VideoconfUsersMm */
                    foreach ((array)$collegati as $u) {
                        $profile = UserProfile::findOne([$u->user_profile_id]);
                        $sent = EmailUtil::sendNotification($videoconference, $profile->user);
                        if($sent) {
                            Console::stdout( print_r($videoconference->id, true) . " - reminder sent to: user_profile_id:" . print_r($profile->id, true) . " email: " . print_r($profile->user->email, true) . "\n");
                            $count++;
                        }
                    }
                    $videoconference->reminder_sent = 1;
                    $videoconference->save(false);
                }
            }
        }

        Console::stdout("n. Email sent: " . print_r($count,true) . "\n");
        Console::stdout("*** END CRON ***" . "\n");
    }



}