<?php
/**
 * @var $model \open20\webmeeting\models\WebMeetingModel
 */
use open20\webmeeting\WebMeetingModule;

$nParticipants = count($participants);
$names = [];
foreach ($participants as $profile){
    $names []= $profile->nomeCognome;
}
$implodedNames = '';
if(!empty($names)){
    $implodedNames = implode(', ',$names);
}
?>
<p>
    <strong><?=WebMeetingModule::_t("Descrizione").": " ?></strong><?=$model->description?>
<!--    <br><strong>--><?php //echo WebMeetingModule::_t("Partecipanti")?><!----><?php //echo "($nParticipants): " ?><!--</strong>--><?php //echo $implodedNames?>
<!--    <br><strong>--><?php //echo WebMeetingModule::_t("Notifica prima dell'inizio").": " ?><!--</strong>--><?php //echo $model->reminder_time?>
</p>
