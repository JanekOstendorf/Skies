<?php
/**
 * @author    Janek Ostendorf (ozzy) <ozzy2345de@gmail.com>
 * @copyright Copyright (c) 2013 Janek Ostendorf
 * @license   http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3
 */
function smarty_function_pageLink($params, Smarty_Internal_Template $template) {

	if(\skies\util\PageUtil::getPage($params['page']) === null) {
		return '';
	}

	return \skies\util\PageUtil::getPage($params['page'])->getRelativeLink((isset($params['arguments']) ? $params['arguments'] : []));

}
