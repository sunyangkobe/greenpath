<?php
/*---------------------------------------------------------------
# Package - Helix Framework  
# Helix Version 1.5.0
# ---------------------------------------------------------------
# Author - JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2011 JoomShaper.com. All Rights Reserved.
# license - PHP files are licensed under  GNU/GPL V2
# license - CSS  - JS - IMAGE files  are Copyrighted material 
# Websites: http://www.joomshaper.com - http://www.joomxpert.com
-----------------------------------------------------------------*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

jimport( 'joomla.event.plugin' );

class  plgSystemHelix extends JPlugin
{
	function onContentPrepareForm($form, $data)
	{
		if ($form->getName()=='com_menus.item')
		{
			$doc = JFactory::getDocument();
			JForm::addFormPath(JPATH_PLUGINS.DS.'system'.DS.'helix'.DS.'elements');
			$form->loadFile('params', false);
			
			//Load js
			$plg_path = JURI::root().'/plugins/system/helix/elements/menuscript.js';
			$doc->addScript($plg_path);
		}
	}	

}
?>