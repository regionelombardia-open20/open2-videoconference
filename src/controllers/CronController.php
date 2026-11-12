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

namespace open20\amos\videoconference\controllers;
//namespace console\controllers;


use open20\amos\admin\models\UserProfile;
use open20\amos\videoconference\AmosVideoconference;
use open20\amos\videoconference\models\Videoconf;
use open20\amos\videoconference\models\VideoconfUsersMm;
use open20\amos\videoconference\utils\EmailUtil;
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