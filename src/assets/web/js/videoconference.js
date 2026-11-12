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

var videoconfId = $("#videoconf-id").text();
var meetingEndUrl = $("#meeting-end").attr("href");

$.ajax({
    type: "get",
    url: "/videoconference/videoconf/json-videoconf-data",
    data: "id=" + videoconfId,
    dataType: "json",
    success: function (response, status) {
        showVideoconference(response);
    },

});

function showVideoconference(data) {
    var domain = data.domain+"?lang=it";
    var options = {
        roomName: data.roomName,
        width: '100%',
        height: '100%',
        parentNode: document.querySelector('#meet'),
        configOverwrite: {},
        interfaceConfigOverwrite: {
            filmStripOnly: false,
            VERTICAL_FILMSTRIP: false,
            FILM_STRIP_MAX_HEIGHT: 200,
            MOBILE_APP_PROMO: false,
            TOOLBAR_BUTTONS: [
                // main toolbar
                'microphone', 'camera', 'desktop', 'invite', 'fullscreen', 'fodeviceselection', 'hangup',
                // extended toolbar
                'contacts', 'chat', 'recording', 'etherpad', 'sharedvideo', 'settings', 'raisehand', 'videoquality', 'filmstrip'],
            /**
             * Main Toolbar Buttons
             * All of them should be in TOOLBAR_BUTTONS
             */
            MAIN_TOOLBAR_BUTTONS: ['microphone', 'camera', 'desktop', 'fullscreen', 'fodeviceselection', 'hangup']
        }
    }
    var api = new JitsiMeetExternalAPI(domain, options);
    api.executeCommand('avatarUrl', data.avatar);
    api.executeCommand('displayName', data.displayName);
    api.addEventListener('readyToClose', function () {
        api.dispose();
        document.location = meetingEndUrl;
    });
}

