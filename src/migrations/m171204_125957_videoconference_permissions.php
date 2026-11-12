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

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m171204_125957_videoconference_permissions
 */
class m171204_125957_videoconference_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc 
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'AMMINISTRATORE_VIDEOCONF',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Ruolo di Amministratore del plugin di Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'JoinOwnVideoconference',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Videoconf',
                'ruleName' => \open20\amos\videoconference\rules\JoinOwnVideoconference::className(),
                'parent' => ['ADMIN', 'BASIC_USER', 'AMMINISTRATORE_VIDEOCONF']
            ],
            [
                'name' => 'UpdateOwnVideoconference',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Videoconf',
                'ruleName' => \open20\amos\videoconference\rules\UpdateOwnVideoconference::className(),
                'parent' => ['ADMIN', 'BASIC_USER', 'AMMINISTRATORE_VIDEOCONF']
            ],
            [
                'name' => 'VIDEOCONF_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di creazione di una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF']
            ],
            [
                'name' => 'VIDEOCONF_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di partecipare ad una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF', 'JoinOwnVideoconference']
            ],
            [
                'name' => 'VIDEOCONF_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di modificare una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF', 'UpdateOwnVideoconference']
            ],
            [
                'name' => 'VIDEOCONF_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di cancellare una Videoconferenza',
                'ruleName' => null,
                'parent' => ['ADMIN', 'AMMINISTRATORE_VIDEOCONF', 'UpdateOwnVideoconference']
            ],
        ];
    }
}