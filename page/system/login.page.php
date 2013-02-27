<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

/* @var $page \skies\system\page\FilePage */

use skies\form\Form;

?>

<?php

// Login, user is guest
if(\Skies::$user->isGuest()) {

	// Registration
	if(isset($_GET['_1']) && $_GET['_1'] == 'register') {
		?>

	<h1 xmlns="http://www.w3.org/1999/html"><?=\Skies::$language->get('system.page.login.sign-up')?></h1>


	<?php

		if(isset($_GET['_2'])) {

			// Check token
			$query = 'SELECT * FROM `'.TBL_PRE.'user-data` INNER JOIN `user-fields` ON `dataFieldID` = `fieldID` INNER JOIN `user` ON `dataUserID` = `userID` WHERE `fieldName` = \'regToken\' AND `dataValue` = \''.escape($_GET['_2']).'\'';

			$result = \Skies::$db->query($query);

			if($result->num_rows == 1) {

				$data = $result->fetch_array(MYSQLI_ASSOC);

				?>

			<p>
				Hallo, <?=$data['userName']?>! Hier kannst du ein Passwort für deine weitere Benutzung von Der LAN-Website festlegen.<br />
				Deinen Benutzernamen kannst du nicht ändern. Er ist so aufgebaut:
				<span class="tt">Vorname Nachname</span>. In deinem Fall also:
				<span class="tt"><?=$data['userName']?></span>.
			</p>

			<fieldset>
				<legend>Passwort</legend>

				<form method="post" target="">

					<input type="hidden" name="token" value="<?=$_GET['_2']?>" />

					<table>

						<tr>
							<td>
								Passwort (zweimal):
							</td>
							<td>
								<input type="password" name="password1" />
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="password" name="password2" />
							</td>
						</tr>

					</table>

					<input type="submit" name="sign_up" value="Passwort setzen" />

				</form>

			</fieldset>


			<?php

			}
			else {

				?>

			<div class="error">
				Dieser Registrierungs-Token ist ungültig.
			</div>

			<?php
			}

		}
		else {

			?>

		<div class="error">
			Du hast keinen Registrierungs-Token angegeben!
		</div>

		<?php

		}


	}
	else {


		?>
	<h1><?=$page->getTitle()?></h1>

	<p>
		Willkommen auf der Login-Seite von Der LAN! Hier kannst du dich anmelden, um Infos zum nächsten LAN einzusehen, zuzusagen und andere Dinge festzulegen.<br />
		Dein Benutzername ist dein voller Name (<span class="tt">Vorname Nachname</span>), dein Passwort hast du selbst festgelegt.
	</p>

	<fieldset>

		<legend><?=\Skies::$language->get('system.page.login.login')?></legend>

		<?php

		$page->store['loginForm']->printForm();

		?>

	</fieldset>

	<p>
		Solltest du dein Passwort nicht mehr wissen, benutze den Link aus deiner Einladungs-E-Mail, um es zu ändern.
	</p>

	<?php
	}

}
// User is logged in
else {

	?>
<h1><?=$page->getTitle()?></h1>

<p>
	<?=\Skies::$language->get('system.page.login.welcome-title', ['userName' => \Skies::$user->getName()])?>
</p>
<p>
	Diese E-Mail Adresse benutzen wir:
	<span class="tt"><?=\skies\util\StringUtil::encodeHTML(\Skies::$user->getMail())?></span>
</p>


<h3>Passwort ändern</h3>

<fieldset>
	<legend>Passwort ändern</legend>
	<?php
	$page->store['changePassword']->printForm(4);
	?>
</fieldset>

<?php

}

?>
