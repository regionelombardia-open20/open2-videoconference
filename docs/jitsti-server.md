# Jitsi Server #
[Jitsi.org](https://jitsi.org/)

## Dizionario ##
- **XMPP/Jabber**: Extensible Messaging and Presence Protocol (XMPP) is an open XML technology for real-time communication (<https://xmpp.org/about/>)
Prosody: XMPP Server (<http://prosody.im/>)
- **Jicofo**: Jitsi Conference Focus, it is responsible for managing media sessions between each of the participants and the videobridge (<https://github.com/jitsi/jicofo>)
- **Jibri**: Jibri is a set of tools for recording and/or streaming a Jitsi Meet conference. It is currently very experimental. (<https://github.com/jitsi/jibri>)
- **JVB/Videobridge**: Jitsi Videobridge is an XMPP server component that allows for multiuser video communication.
- **BOSH (protocol)**: Bidirectional-streams Over Synchronous HTTP, is a transport protocol that emulates a bidirectional stream between two entities (such as a client and a server) by using multiple synchronous HTTP request/response pairs without requiring the use of polling or asynchronous chunking.
- **TURN**: Traversal Using Relays around NAT, is a protocol that assists in traversal of network address translators (NAT) or firewalls for multimedia applications. UDP and TCP

## Struttura Server ##
<https://github.com/jitsi/jitsi-meet/blob/master/doc/manual-install.md>

- Web app: jitsi-meet-web
- Web server: Jitty (default), Nginx, Apache
- Video bridge: jitsi-videobridge (aka jvb)
- Controller: jicofo
- XMPP server: prosody

## JVB ##
Jitsi-Videobridge can run behind a NAT, provided that all required ports are routed (forwarded) to the machine that it runs on.
By default these ports are (TCP/443 or TCP/4443 and UDP 10000-20000).

How to enable TURN server support in one-to-one calls in Jitsi Meet.
Per due partecipanti sarebbe più efficiente usare un P2P, per evitari casini occorre passare per un server turn (nella conf attuale ci sono quelli offerti da google)
<https://github.com/jitsi/jitsi-meet/blob/master/doc/turn.md>

**Configuration**

/etc/jitsi/videobridge/config

## Prosody ##
Prosody XMPP Server

**Configuration**<br>
/etc/prosody/prosody.cfg.lua + /etc/prosody/conf.d/*.cfg.lua

<http://prosody.im/doc/configure><br />
<https://github.com/jitsi/jitsi-meet/blob/master/doc/example-config-files/prosody.cfg.lua.example>

Autenticated and guest user: <br /><https://github.com/jitsi/jicofo#secure-domain> <br />
<http://lists.jitsi.org/pipermail/dev/2014-November/022609.html>

**jitsi-meet-tokens**<br>
<https://github.com/jitsi/lib-jitsi-meet/blob/master/doc/tokens.md><br>
Prima di procedere all'installazione occorre aggiornare Prosody dal trunk.<br>
Il token è di tipo JWT <https://jwt.io/#libraries-io><br>
Occorre poi avere un secondo dominio "guest" che non possa creare la room ma accetti interlocutori senza token.

## Meet ##
Web app in /usr/share/jitsi-meet/

**Configuration**<br>
/etc/jitsi/meet/config.js (oppure {serverName}-config.js)
vedi: <https://github.com/jitsi/jitsi-meet/blob/master/config.js>

/usr/share/jitsi-meet/interface_config.js: configurazione di cosa visualizzare o meno nella web-app <https://github.com/jitsi/jitsi-meet/blob/master/interface_config.js>

Questi due configurazioni vengono sovrascritte dalla configurazione locale di Open.
Alcune note:
//DEFAULT_REMOTE_DISPLAY_NAME: 'User',//Questo è come mi chiamo io nella mia visuale
//DEFAULT_LOCAL_DISPLAY_NAME: 'Smart.it Test',//Questo è come  chiamo chi non ha un nikname (displayName)
Se voglio cambiare il displayName usare
```
api.executeCommand('displayName', 'Nik Name');
```
Se voglio cambiare l'avatar
```
api.executeCommand('avatarUrl', 'https://server.smart/avatar/smart.png');
```
Esistono hook di evento come
```
api.addEventListener('readyToClose',function(){
	api.dispose();
	document.location = 'meeting-end.html';
});
```

/usr/share/jitsi-meet/logging_config.js: livello di logging javascript <https://github.com/jitsi/jitsi-meet/blob/master/config.js>


Possibilità di embed della webapp tramite javascript tramite le jitsi-meet api.
{serverName}/libs/external_api.min.js
```javascript
var domain = "jitsi.server";
var options = {
	roomName: "roomKey",
	configOverwrite: {
		/* vedi config.js */
	},
	interfaceConfigOverwrite: {
		/* vedi interface_config.js */
	}
}
var api = new JitsiMeetExternalAPI(domain, options);
```

## Lib ##
__[TODO]__ Approfondire:
<https://github.com/jitsi/lib-jitsi-meet/blob/master/doc/API.md>

## Link Documentazione ##
- **Jitsi Videobridge**
	<https://github.com/jitsi/jitsi-videobridge/tree/master/doc>
	Use JVB per servire Meet: <https://github.com/jitsi/jitsi-videobridge/blob/master/doc/http.md>

- **Jitsi Meet API**
	<https://github.com/jitsi/jitsi-meet/blob/master/doc/api.md>

- **Lib Jitsi Meet API**
	<https://github.com/jitsi/lib-jitsi-meet/blob/master/doc/API.md>

- **Lib Jitsi Meet JWT token**
	<https://github.com/jitsi/lib-jitsi-meet/blob/master/doc/tokens.md>
	Prosody authentication provider that verifies client connection based on JWT token. It allows to use any external form of authentication with lib-jitsi-meet.
