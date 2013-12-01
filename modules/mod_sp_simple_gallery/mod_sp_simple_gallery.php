<?php
/**
* @title		Simple image gallery module
* @version		2.3
* @website		http://www.joomshaper.com
* @copyright	Copyright (C) 2010 JoomShaper.com. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$document 			= JFactory::getDocument();
$uniqid				= $module->id;
$usefade			= $params->get('usefade');
$normal_opacity		= $params->get('normal_opacity');
$hover_opacity		= $params->get('hover_opacity');
$style				= $params->get('style', "border:1px solid #DDD; margin:0 5px 10px 5px; padding:5px; background:#fff;");
$styles 			= 'img.sp_simple_gallery {' . $style . '}'; 

$document->addStyleSheet(JURI::base() . 'modules/mod_sp_simple_gallery/scripts/slimbox.css');
$document->addScript(JURI::base() . 'modules/mod_sp_simple_gallery/scripts/slimbox.js');
$document->addStyleDeclaration( $styles );

require_once (dirname(__FILE__).DS.'helper.php');
$list = modSPGalleryHelper::getimgList($params);
require(JModuleHelper::getLayoutPath('mod_sp_simple_gallery'));
?>
