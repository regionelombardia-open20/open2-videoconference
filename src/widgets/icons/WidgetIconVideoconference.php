<?php

namespace lispa\amos\videoconference\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\videoconference\AmosVideoconference;
use Yii;
use yii\helpers\ArrayHelper;
use lispa\amos\comuni\AmosComuni;

class WidgetIconVideoconference extends WidgetIcon
{

    public function init()
    {
        parent::init();

        $this->setLabel(AmosVideoconference::t('amosvideoconference', 'Videoconference'));
        $this->setDescription(AmosVideoconference::t('amosvideoconference', 'Plugin per videoconferenze'));

        $this->setIcon('video-camera');
        $this->setIconFramework('dash');
        $this->setUrl(Yii::$app->urlManager->createUrl(['/videoconference/videoconf/index']));
        $this->setModuleName('videoconference');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-lightPrimary'
        ]));
    }

}
