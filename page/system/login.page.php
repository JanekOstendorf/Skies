<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

use skies\utils\Form;

?>

<h1><?=\Skies::$page->getTitle()?></h1>

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

    $loginForm = new skies\utils\Form();

    $loginForm->addInput('username', \Skies::$language->get('system.page.login.username'));
    $loginForm->addInput('password', \Skies::$language->get('system.page.login.password'), 'password');
    $loginForm->addInput('login', \Skies::$language->get('system.page.login.login'), 'submit');

    $loginForm->printForm();

?>

</fieldset>
<fieldset class="float-right" style="width: 45%;">

    <legend><?=\Skies::$language->get('system.page.login.sign-up')?></legend>

    <?php

    $loginForm = new skies\utils\Form();

    $loginForm->addInput('username_sign-up', \Skies::$language->get('system.page.login.username'));
    $loginForm->addInput('password1', \Skies::$language->get('system.page.login.password-twice'), 'password');
    $loginForm->addInput('password2', '', 'password');
    $loginForm->addInput('sign-up', \Skies::$language->get('system.page.login.sign-up'), 'submit');

    $loginForm->printForm();

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

    $logoutForm = new skies\utils\Form();

    $logoutForm->addInput('logout', \Skies::$language->get('system.page.login.logout'), 'submit');

    $logoutForm->printForm();


}

?>