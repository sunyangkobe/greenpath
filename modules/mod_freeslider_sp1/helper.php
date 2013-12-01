<?php
/*
# ------------------------------------------------------------------------
# Free Slide SP1 - Slideshow module for Joomla 1.6
# ------------------------------------------------------------------------
# Copyright (C) 2010 JoomShaper.com. All Rights Reserved.
# @license - GNU/GPL, see LICENSE.php,
# Author: JoomShaper.com
# Websites:  http://www.joomshaper.com -  http://www.joomxpert.com
# ------------------------------------------------------------------------
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_SITE.'/components/com_content/helpers/route.php';

jimport('joomla.application.component.model');

JModel::addIncludePath(JPATH_SITE.'/components/com_content/models');

abstract class modFSSP1Helper
{	

	static function getList($params){
		
		$app			= JFactory::getApplication();
		$db				= JFactory::getDbo();
		
		$titleas		= $params->get('titleas');
		$desclimitas	= $params->get('desclimitas');
		$titlelimit		= (int) $params->get('titlelimit');
		$desclimit		= (int) $params->get('desclimit');

		// Get an instance of the generic articles model
		$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$appParams = JFactory::getApplication()->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		
		$model->setState('list.limit', (int) $params->get('count', 5));
		
		$model->setState('filter.published', 1);

		// User filter
		$userId = JFactory::getUser()->get('id');
		switch ($params->get('user_id'))
		{
			case 'by_me':
				$model->setState('filter.author_id', (int) $userId);
				break;
			case 'not_me':
				$model->setState('filter.author_id', $userId);
				$model->setState('filter.author_id.include', false);
				break;

			case '0':
				break;

			default:
				$model->setState('filter.author_id', (int) $params->get('user_id'));
				break;
		}		

		//  Featured switch
		switch ($params->get('show_featured'))
		{
			case '1':
				$model->setState('filter.featured', 'only');
				break;
			case '0':
				$model->setState('filter.featured', 'hide');
				break;
			default:
				$model->setState('filter.featured', 'show');
				break;
		}
		
		$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.title_alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
			' a.modified, a.modified_by,a.publish_up, a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
			' a.hits, a.featured,' .
			' LENGTH(a.fulltext) AS readmore');
			
		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$model->setState('filter.category_id', $params->get('catid', array()));

		// Filter by language
		$model->setState('filter.language',$app->getLanguageFilter());

		// Set ordering
		$model->setState('list.ordering', $params->get('ordering', 'a.ordering'));
		$model->setState('list.direction', $params->get('ordering_direction', 'ASC'));

		//	Retrieve Content
		$items = $model->getItems();
		
		//	Retrieve Content
		$items = $model->getItems();
		
		foreach ($items as &$item) {
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid.':'.$item->category_alias;
			
			$item->created 		= $item->created;
			$item->title 		= modFSSP1Helper::cText(htmlspecialchars($item->title),$titlelimit,$titleas);
			$item->image		= modFSSP1Helper::getImage($item->introtext);			
			$item->introtext 	= modFSSP1Helper::cText(JHtml::_('content.prepare', $item->introtext),$desclimit,$desclimitas);	
			$item->link 		= JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
		}	
		
		return $items;
		
	}	
	
	function cText($text, $limit, $limitas) {
		
		switch ($limitas) {
			case 0 :
				$text = JFilterOutput::cleanText($text);
				$text = explode(' ',$text);
				$sep = (count($text)>$limit) ? '...' : '';
				$text=implode(' ', array_slice($text,0,$limit)) . $sep;
				break;
			case 1 :
				$text = JFilterOutput::cleanText($text);
				$sep  = (strlen($text)>$limit) ? '...' : '';
				$text =utf8_substr($text,0,$limit) . $sep;
				break;
			case 2 :
				$allowed_tags = '<b><i><a><small><h1><h2><h3><h4><h5><h6><sup><sub><em><strong><u><br>';
				$text = strip_tags( $text, $allowed_tags );
				$text = $text;
				break;
			default :
				$text = JFilterOutput::cleanText($text);
				$text = explode(' ',$text);
				$sep = (count($text)>$limit) ? '...' : '';
				$text=implode(' ', array_slice($text,0,$limit)) . $sep;
				break;
		}		
		
		return $text;
	}
	
	function getImage($text) {

		preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $text, $matches);
		
		//If no image found
		if (!isset($matches[1])) { 
			$matches[1]='modules/mod_freeslider_sp1/assets/images/no-image.jpg';
		}

		if (!file_exists($matches[1])) {
			$matches[1]='modules/mod_freeslider_sp1/assets/images/no-image.jpg';
		}	
		 
		return $matches[1];
	}	

}
 