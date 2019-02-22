<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\videoconference
 * @category   CategoryName
 */

namespace lispa\amos\videoconference;

use lispa\amos\core\module\AmosModule;
use lispa\amos\core\module\ModuleInterface;

use Yii;

/**
 * Class AmosVideoconference
 * @package lispa\amos\videoconference
 */
class AmosVideoconference extends AmosModule implements ModuleInterface
{
    public static $CONFIG_FOLDER = 'config';
    
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    
    public $name = 'Videoconference';
    
    public $controllerNamespace = 'lispa\amos\videoconference\controllers';
    
    public $config = [];

    /** @var int used to send a reminder X minute before the start of the videoconference */
    public $minuteReminder = 60;

    /**
     *  If is false you can invite to videoconference users that aren't in your user network
     * @var bool
     */
    public $onlyNetworkUsers = true;


    /**
     * @inheritdoc
     */
    public static function getModuleName()
    {
        return "videoconference";
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        \Yii::setAlias('@lispa/amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
        // initialize the module with the configuration loaded from config.php
         $config = require(__DIR__ . DIRECTORY_SEPARATOR . self::$CONFIG_FOLDER . DIRECTORY_SEPARATOR . 'config.php');
         Yii::configure($this,$config );
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [            
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return [            
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [            
        ];
    }
        
}
