<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;
/**
 * Layout variables
 * ---------------------
 *
 * @var  string   $tableId                 The id of the table
 * @var  string   $formId                  The id of the form
 * @var  string   $saveOrderingUrl         Save the ordering URL?
 * @var  string   $sortDir                 The direction of the order (asc/desc)
 * @var  string   $nestedList              Is it nested list?
 * @var  string   $proceedSaveOrderButton  Is there a button to initiate the ordering?
 */

extract($displayData);

// Depends on jQuery UI
JHtml::_('jquery.ui', array('core', 'sortable'));

//Modification:
//The ordering numbers of the price rule list need to be updated after each reordering.
//So we load a slighly modified sortablelist js file.
if($displayData['tableId'] == 'priceruleList') {
  $doc = JFactory::getDocument();
  $doc->addScript(JURI::base().'components/com_odyssey/js/sortablelist-refresh.js');
}
else { //Load the regular sortablelist js file.
  JHtml::_('script', 'jui/sortablelist.js', false, true);
}

JHtml::_('stylesheet', 'jui/sortablelist.css', false, true, false);

// Attach sortable to document
JFactory::getDocument()->addScriptDeclaration(
	"
		jQuery(document).ready(function ($){
			var sortableList = new $.JSortableList('#"
	. $tableId . " tbody','" . $formId . "','" . $sortDir . "' , '" . $saveOrderingUrl . "','','" . $nestedList . "');
		});
	"
);

if ($proceedSaveOrderButton)
{
	JFactory::getDocument()->addScriptDeclaration(
		"
		jQuery(document).ready(function ($){
			var saveOrderButton = $('.saveorder');
			saveOrderButton.css({'opacity':'0.2', 'cursor':'default'}).attr('onclick','return false;');
			var oldOrderingValue = '';
			$('.text-area-order').focus(function ()
			{
				oldOrderingValue = $(this).attr('value');
			})
			.keyup(function (){
				var newOrderingValue = $(this).attr('value');
				if (oldOrderingValue != newOrderingValue)
				{
					saveOrderButton.css({'opacity':'1', 'cursor':'pointer'}).removeAttr('onclick')
				}
			});
		});
		"
	);
}