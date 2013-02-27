<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */


if(\Skies::$lan->isLocal()) {
	?>

<hr />
<p style="text-align: center; font-size: 2em;">
	Noch <span class="Countdown" id="CountdownEnd"></span> Zeit zum Zocken.<br />
	<span style="font-size: 0.5em;" id="CountdownEndEnde">(<?=strftime('%d. %B %Y %R', \Skies::$lan->getEndTime())?> Uhr)</span>
</p>
<div class="float-left" style="width: 69%;">
	<h3>WLAN</h3>

	<p>
		Wir haben ein schönes kleines WLAN-Netz eingerichtet, damit man eben ein WLAN-Netz hat.<br />
		Über Apples Remote App für iPod Touch und iPhone kann man sich Musik wünschen, einfach ausprobieren.
	</p>

	<p>
		<strong>WLAN-Schlüssel: </strong><tt>Sirura61</tt>
	</p>

	<h3>Orga-Team</h3>
	<ul>
		<li>Markus Muhle (Verwaltung, rechtlicher Ansprechpartner) &lt;<a href="mailto:markus@der-lan.de">markus@der-lan.de</a>&gt;</li>
		<li>Janek Ostendorf (Knabbereien, Musik, Administration) &lt;<a href="mailto:janek@der-lan.de">janek@der-lan.de</a>&gt;</li>
		<li>Arne Schütte (Getränke) &lt;<a href="mailto:arne@der-lan.de">arne@der-lan.de</a>&gt;</li>
		<li>Florian Thie (Infrastruktur, Verwaltung) &lt;<a href="mailto:florian@der-lan.de">florian@der-lan.de</a>&gt;</li>
		<li>Tobias Thie (Mahlzeit) &lt;<a href="mailto:tobias@der-lan.de">tobias@der-lan.de</a>&gt;</li>
		<li>Jan-Philipp Wels (Turniere) &lt;<a href="mailto:jan-philipp@der-lan.de">jan-philipp@der-lan.de</a>&gt;</li>
	</ul>
</div>
<div class="float-right" style="width: 29%;">
	<fieldset>
		<legend>Gameserver Status</legend>

		<?php
		include(ROOT_DIR.'/server_status.php');
		?>

	</fieldset>
</div>
<br class="clear" />

<script type="text/javascript">
	var CountdownJahr = <?=getdate(\Skies::$lan->getEndTime())['year']?>;
	var CountdownMonat = <?=getdate(\Skies::$lan->getEndTime())['mon']?>;
	var CountdownTag = <?=getdate(\Skies::$lan->getEndTime())['mday']?>;
	var CountdownStunde = <?=getdate(\Skies::$lan->getEndTime())['hours']?>;
	var CountdownMinute = <?=getdate(\Skies::$lan->getEndTime())['minutes']?>;
	var CountdownSekunde = 0;

	function CountdownAnzeigen() {

		var Jetzt = new Date();
		var Countdown = new Date(CountdownJahr, CountdownMonat - 1, CountdownTag, CountdownStunde, CountdownMinute, CountdownSekunde);
		var MillisekundenBisCountdown = Countdown.getTime() - Jetzt.getTime();
		var Rest = Math.floor(MillisekundenBisCountdown / 1000);
		var $countdownText = "";

		if (Rest >= 31536000) {
			var Jahre = Math.floor(Rest / 31536000);
			Rest = Rest - Jahre * 31536000;

			if (Jahre > 1 || Jahre == 0) {
				$countdownText += Jahre + " Jahre ";
			}
			else if (Jahre == 1) {
				$countdownText += Jahre + " Jahr ";
			}
		}
		if (Rest >= 86400) {
			var Tage = Math.floor(Rest / 86400);
			Rest = Rest - Tage * 86400;

			if (Tage > 1 || Tage == 0) {
				$countdownText += Tage + " Tage ";
			}
			else if (Tage == 1) {
				$countdownText += Tage + " Tag ";
			}
		}
		if (Rest >= 3600) {
			var Stunden = Math.floor(Rest / 3600);
			Rest = Rest - Stunden * 3600;

			if (Stunden > 1 || Stunden == 0) {
				$countdownText += Stunden + " Stunden ";
			}
			else if (Stunden == 1) {
				$countdownText += Stunden + " Stunde ";
			}
		}
		if (Rest >= 60) {
			var Minuten = Math.floor(Rest / 60);
			Rest = Rest - Minuten * 60;

			if (Minuten > 1 || Minuten == 0) {
				$countdownText += Minuten + " Minuten ";
			}
			else if (Minuten == 1) {
				$countdownText += Minuten + " Minute ";
			}
		}

		if (Rest > 1 || Rest == 0) {
			$countdownText += Rest + " Sekunden ";
		}
		else if (Rest == 1) {
			$countdownText += Rest + " Sekunde ";
		}
		document.getElementById('CountdownEnd').innerHTML = $countdownText;
		window.setTimeout("CountdownAnzeigen()", 1000);
	}
	window.setTimeout("CountdownAnzeigen()", 1000);
</script>

<?php
} // getLocal
else {
	if(\Skies::$user->isGuest()) {

		if(\Skies::$lan->getEndTime() < NOW) {

			$time = 'Termin wird bald bekannt gegeben.';

		}
		elseif(\Skies::$lan->getTime() < NOW) {

			$time = 'Jetzt';

		}
		else
			$time = strftime('%d. %B %Y', \Skies::$lan->getTime());

		?>


	<h1>Der <?=\Skies::$lan->getPrefix()?>-LAN</h1>

	<p>
		Willkommen auf der Website von <strong>Der <?=\Skies::$lan->getPrefix()?>-LAN</strong>. Wenn du eine E-Mail zur Anmeldung erhalten hast, benutze bitte den darin enthaltenen Link, um dich anzumelden.
	</p>

	<blockquote style="text-align: center; font-size: 3.5em; margin-top: 20px; margin-bottom: 20px;">
		$(10^{(i+1)*3} \mid i = <?=\Skies::$lan->getCount()?>)$ - LAN
	</blockquote>

	<p>
		Nächster LAN: <strong><?=$time?></strong>
	</p>

	<p>
		Wenn du noch keine E-Mail hast, bitte sprech jemanden aus dem Organisations-Team an, damit wir dir eine Einladung senden können.<br />
		Im Organisations-Team sind:
	</p>
	<ul>
		<li>Markus Muhle</li>
		<li>Janek Ostendorf</li>
		<li>Arne Schütte</li>
		<li>Florian Thie</li>
		<li>Tobias Thie</li>
		<li>Jan-Philipp Wels</li>
	</ul>

	<blockquote style="width: 70%; font-size: 1.5em; text-align: center; margin: 0 auto; margin-top: 50px;">
		&#187; <a href="<?=SUBDIR?>/login">Anmeldung</a>
	</blockquote>

	<?php
	}
	else {
		?>

	<hr />

	<blockquote style="text-align: center; font-size: 2em;">
		Noch <span class="Countdown" id="Countdown"></span> bis zum LAN.<br />
		<span style="font-size: 0.5em;" id="CountdownEnde">(<?=strftime('%d. %B %Y %R', \Skies::$lan->getTime())?> Uhr)</span>
	</blockquote>

	<p style="text-align: center; font-size: 2em;">
		Aktuell <?=\Skies::$lan->getAttendantCount() == 1 ? 'ist' : 'sind'?>
		<strong><?=\Skies::$lan->getAttendantCount()?> / <?=\Skies::$lan->getAttendantMaximum()?></strong> Plätzen belegt.

	</p>

	<h1>Der <?=\Skies::$lan->getPrefix()?>-LAN</h1>
	<p>
		Es ist wieder soweit: Große LAN-Party in der Ökonomie! Spielregeln sind wie immer: Anfragen beim Orga-Team, auf dieser Seite anmelden, zum LAN kommen und zocken!
	</p>
	<p>
		Dieses Mal haben wir auch wieder Turniere am Start, aber mit 6er-Teams. Mehr Infos unter <a href="<?=SUBDIR?>/infos">Infos</a>.
	</p>

	<h3>Orga-Team</h3>
	<ul>
		<li>Markus Muhle (Verwaltung, rechtlicher Ansprechpartner) &lt;<a href="mailto:markus@der-lan.de">markus@der-lan.de</a>&gt;</li>
		<li>Janek Ostendorf (Knabbereien, Musik, Administration) &lt;<a href="mailto:janek@der-lan.de">janek@der-lan.de</a>&gt;</li>
		<li>Arne Schütte (Getränke) &lt;<a href="mailto:arne@der-lan.de">arne@der-lan.de</a>&gt;</li>
		<li>Florian Thie (Infrastruktur, Verwaltung) &lt;<a href="mailto:florian@der-lan.de">florian@der-lan.de</a>&gt;</li>
		<li>Tobias Thie (Mahlzeit) &lt;<a href="mailto:tobias@der-lan.de">tobias@der-lan.de</a>&gt;</li>
		<li>Jan-Philipp Wels (Turniere) &lt;<a href="mailto:jan-philipp@der-lan.de">jan-philipp@der-lan.de</a>&gt;</li>
	</ul>

	<hr />
	<?php
		\Skies::$lan->printUserStatus();
	}
	?>

	<div id="facebookWrapper">
		<div id="facebook">
			<a href="http://www.facebook.com/de.der.lan">
				<img src="<?=SUBDIR?>/images/fazzeteil.png" /> Der LAN auf<br />Facebook<br class="clear" />
			</a>
		</div>
	</div>

<script type="text/javascript">
	var CountdownJahr = <?=getdate(\Skies::$lan->getTime())['year']?>;
	var CountdownMonat = <?=getdate(\Skies::$lan->getTime())['mon']?>;
	var CountdownTag = <?=getdate(\Skies::$lan->getTime())['mday']?>;
	var CountdownStunde = <?=getdate(\Skies::$lan->getTime())['hours']?>;
	var CountdownMinute = <?=getdate(\Skies::$lan->getTime())['minutes']?>;
	var CountdownSekunde = 0;

	function CountdownAnzeigen() {

		var Jetzt = new Date();
		var Countdown = new Date(CountdownJahr, CountdownMonat - 1, CountdownTag, CountdownStunde, CountdownMinute, CountdownSekunde);
		var MillisekundenBisCountdown = Countdown.getTime() - Jetzt.getTime();
		var Rest = Math.floor(MillisekundenBisCountdown / 1000);
		var $countdownText = "";

		if (Rest >= 31536000) {
			var Jahre = Math.floor(Rest / 31536000);
			Rest = Rest - Jahre * 31536000;

			if (Jahre > 1 || Jahre == 0) {
				$countdownText += Jahre + " Jahre ";
			}
			else if (Jahre == 1) {
				$countdownText += Jahre + " Jahr ";
			}
		}
		if (Rest >= 86400) {
			var Tage = Math.floor(Rest / 86400);
			Rest = Rest - Tage * 86400;

			if (Tage > 1 || Tage == 0) {
				$countdownText += Tage + " Tage ";
			}
			else if (Tage == 1) {
				$countdownText += Tage + " Tag ";
			}
		}
		if (Rest >= 3600) {
			var Stunden = Math.floor(Rest / 3600);
			Rest = Rest - Stunden * 3600;

			if (Stunden > 1 || Stunden == 0) {
				$countdownText += Stunden + " Stunden ";
			}
			else if (Stunden == 1) {
				$countdownText += Stunden + " Stunde ";
			}
		}
		if (Rest >= 60) {
			var Minuten = Math.floor(Rest / 60);
			Rest = Rest - Minuten * 60;

			if (Minuten > 1 || Minuten == 0) {
				$countdownText += Minuten + " Minuten ";
			}
			else if (Minuten == 1) {
				$countdownText += Minuten + " Minute ";
			}
		}

		if (Rest > 1 || Rest == 0) {
			$countdownText += Rest + " Sekunden ";
		}
		else if (Rest == 1) {
			$countdownText += Rest + " Sekunde ";
		}
		document.getElementById('Countdown').innerHTML = $countdownText;
		window.setTimeout("CountdownAnzeigen()", 1000);
	}
	window.setTimeout("CountdownAnzeigen()", 1000);
</script>
<?php
} // else getLocal
?>
