<?php
namespace lispa\amos\videoconference\utils;

use lispa\amos\core\helpers\Html;
use lispa\amos\videoconference\AmosVideoconference;
use lispa\amos\videoconference\models\Videoconf;
use yii\helpers\Console;
use yii\helpers\Url;
use lispa\amos\core\utilities\Email;


class EmailUtil
{
    /**
     * manda la mail agli utenti collegati alla videoconferenza
     *
     * @param $videoconference Videoconf
     * @param $user
     * @return bool
     */
    public static function sendEmailPartecipant($videoconference, $user)
    {
        $from = \Yii::$app->params['supportEmail'];
        $to = [$user->email];
        $subject = 'Invito videoconferenza';
        $url_videoconf = $videoconference->getLinkToVideoconf();
        $body []=  "<br>";

        if($videoconference->status == Videoconf::STATUS_FUTURE) {
            $body []=  AmosVideoconference::t('amosvideoconference', "Sei stato invitato ad una videoconferenza programmata per il ")
                . "<strong>"
                . \Yii::$app->formatter->asDate($videoconference->begin_date_hour)
                . " alle "
                . \Yii::$app->formatter->asTime($videoconference->begin_date_hour, 'H:mm')
                ."</strong>";
            $body []=  "<a href='". $url_videoconf ."'>" . AmosVideoconference::t('amosvideoconference', 'Link di accesso alle tue videoconferenze') . "</a>";
            $body []= AmosVideoconference::t('amosvideoconference', 'Se il link non funziona copia e incolla il seguente in una finestra del tuo browser di navigazione');
        }else {
            $body []= AmosVideoconference::t('amosvideoconference', "Ti inviamo il link per accedere alla videoconferenza");
            $body []= "<a href='". $url_videoconf ."'>" . AmosVideoconference::t('amosvideoconference', 'Link di accesso alla videoconferenza') . "</a>";
            $body []= AmosVideoconference::t('amosvideoconference', 'Se il link non funziona copia e incolla il seguente in una finestra del tuo browser di navigazione');
            $body []= "<a href='". $url_videoconf ."'>" .$url_videoconf. "</a>";
        }

        $params = [
            'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
            'category' => "amosvideoconference",
            'subject' => $subject,
            'profile' => $user->userProfile
//            'videoconference' => $videoconference->toArray(),
//            'url_videoconf' => $url_videoconf
        ];
        $template = '@vendor/lispa/amos-videoconference/src/mail/generic/generic-html';
        $templateSubject = '@vendor/lispa/amos-videoconference/src/mail/generic/subject-html';

        $sent = self::sendEmail($to, $from, $subject, $params, $template, $templateSubject, $user);
        return $sent;
    }

    /**
     * manda la mail dipropmemoria agli utenti collegati alla videoconferenza
     *
     * @param $videoconference Videoconf
     * @param $user
     * @return bool
     */
    public static function sendNotification($videoconference, $user){
        $url = Url::to(['/videoconference/videoconf/index'], true);
        $from = \Yii::$app->params['supportEmail'];
        $to = [$user->email];
        $subject = 'Promemoria inzio conferenza';
        $body []= AmosVideoconference::t("amosvideoconference", "Le ricordiamo che sarà possibile accedere alla video conferenza alle ")
            . "<strong>"
            . \Yii::$app->formatter->asDate($videoconference->begin_date_hour)
            . " alle "
            . \Yii::$app->formatter->asTime($videoconference->begin_date_hour, 'H:mm')
            ."</strong>";
        $body []= AmosVideoconference::t('amosvideoconference', "Ti inviamo il link per accedere alla videoconferenza");
        $body []= AmosVideoconference::t('amosvideoconference', "<a href='". $url ."'>".$url ."</a>");

        $params = [
            'userProfile' => $user->userProfile->toArray(),
            'body' => $body,
            'category' => "amosvideoconference",
            'subject' => $subject,
            'profile' => $user->userProfile
        ];
        $template = '@vendor/lispa/amos-videoconference/src/mail/generic/generic-html';
        $templateSubject = '@vendor/lispa/amos-videoconference/src/mail/generic/subject-html';

        $sent = self::sendEmail($to, $from, $subject, $params, $template, $templateSubject, $user);
        return $sent;
    }

    /**
     * 
     * @param type $to
     * @param type $from
     * @param type $subject
     * @param type $params
     * @param type $template
     * @param type $templateSubject
     * @param type $user
     */
    public static function sendEmail($to, $from, $subject, $params, $template, $templateSubject, $user){
       
        try {
            $subject = Email::renderMailPartial($templateSubject,$params, $user->id);
            $message = Email::renderMailPartial($template, $params, $user->id);
            $email   = new Email();
            $email->sendMail($from, $to, $subject, $message);
                
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
            return false;
        }
        return true;
    }


}