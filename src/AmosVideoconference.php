<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\videoconference
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

namespace open20\amos\videoconference;

use open20\amos\core\interfaces\BreadcrumbInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;

use Yii;

/**
 * Class AmosVideoconference
 * @package open20\amos\videoconference
 */
class AmosVideoconference extends AmosModule implements ModuleInterface, BreadcrumbInterface
{
    public static $CONFIG_FOLDER = 'config';
    
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';
    
    public $name = 'Videoconference';
    
    public $controllerNamespace = 'open20\amos\videoconference\controllers';
    
    public $config = [];

    /** @var int used to send a reminder X minute before the start of the videoconference */
    public $minuteReminder = 60;

    /**
     *  If is false you can invite to videoconference users that aren't in your user network
     * @var bool
     */
    public $onlyNetworkUsers = true;

    //Url without http:// of the jitsi installation
    public $jitsiDomain = 'meet.test.demotestwip.it';


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
        
        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/controllers/', __DIR__ . '/controllers/');
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

    /**
     * @return array
     */
    public function getIndexActions()
    {
        return [
            'videoconf/index',
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'videoconf' => '/videoconference/videoconf/index'
        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'videoconf' => '/videoconference/videoconf/index',
        ];
    }

    /**
     * @return array
     */
    public function getControllerNames()
    {
        $names = [
            'videoconf' => self::t('amosnews', "Videconferenza"),
        ];

        return $names;
    }
        
}
