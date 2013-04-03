<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.lang.php
 * Type:     function
 * Name:     lang
 * Purpose:  replace language strings
 * -------------------------------------------------------------
 */
function smarty_modifier_lang($node, $userVars = []) {
	return \Skies::getLanguage()->get($node, $userVars);
}
