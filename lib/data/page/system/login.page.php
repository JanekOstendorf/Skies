<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

/* @var $page \skies\system\page\FilePage */

use skies\form\Form;

?>

	<h1><?=$page->getTitle()?></h1>

<?php

// Login, user is guest
if(\Skies::$user->isGuest()) {

	?>

	<p>
		<?=\Skies::$language->get('system.page.login.introduction', true)?>
	</p>

	<fieldset class="float-left" style="width: 45%;">

		<legend><?=\Skies::$language->get('system.page.login.login')?></legend>

		<?php

		$page->store['loginForm']->printForm();

		?>

	</fieldset>
	<fieldset class="float-right" style="width: 45%;">

		<legend><?=\Skies::$language->get('system.page.login.sign-up')?></legend>

		<?php

		$page->store['signUpForm']->printForm();

		?>

	</fieldset>
	<br class="clear" />

<?php

}
// User is logged in
else {
	?>

	<p>
		<?=\Skies::$language->get('system.page.login.welcome-title', ['userName' => \Skies::$user->getName()])?>
	</p>
	<?php

	$page->store['logoutForm']->printForm();


}

?>
