<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */

?>

<h1>
    Überschrift h1
</h1>

<h2>Überschrift h2</h2>

<h3>Überschrift h3</h3>

<h4>Überschrift h4</h4>

<fieldset>
    <legend>Fieldset!</legend>

    <p>Dieses Formular macht rein gar nichts.</p>

    <?php
    $loginForm = new skies\form\Form();

    $loginForm->addInput('Test', 'test');
    $loginForm->addInput('Ein Passwort:', 'pw', 'password');
    $loginForm->addInput('Submit', 'submit', 'submit');

    $loginForm->printForm();
    ?>
</fieldset>

<hr />

<p class="success">
    Ich bin ein Erfolg!
</p>
<p class="notice">
    Ich bin eine Meldung!
</p>
<p class="error">
    Ich bin ein Fehler!
</p>