<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_easyquickcontact
 *
 * @copyright   Copyright (C) 2012 - 2020 JoomBoost, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die;

jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {

	protected $type = 'asset';
	

	public function renderField($options = array()) {
		
		$doc = JFactory::getDocument();
		
		$doc->addStyleSheet(JUri::root()."modules/mod_easyquickcontact/css/modStyler.css");
		$doc->addScript(JUri::root()."modules/mod_easyquickcontact/js/modStyler.js");
		
		return;
	}
}