<?php
/**
* @version		$Id:params.php 1 2010-11-14 17:12:07Z  $
* @package		Virtualdomain
* @subpackage 	Views
* @author     	Michael Liebler {@link http://www.janguo.de}
* @copyright	Copyright (C) 2008 - 2013 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Virtualdomains is free software. This version may have been modified pursuant to the
* GNU General Public License, and as distributed it includes or is derivative
* of works licensed under the GNU General Public License or other free or open
* source software licenses. See COPYRIGHT.php for copyright notices and
* details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

 
class VirtualdomainsViewParams  extends JViewLegacy {

	public function display($tpl = null) 
	{
		$app = JFactory::getApplication('');
		if(version_compare(JVERSION, '3', 'lt')) {
			JHTML::stylesheet( 'bootstrap-forms.css', 'administrator/components/com_virtualdomains/assets/' );
		}
		
		if ($this->getLayout() == 'form') {
		
			$this->_displayForm($tpl);		
			return;
		}
		$context			= 'com_virtualdomains'.'.'.strtolower($this->getName()).'.list.';
		
		$filter_order = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', $this->get('DefaultFilter'), 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$search = $app->getUserStateFromRequest($context . 'search', 'search', '', 'string');
		$search = JString :: strtolower($search);
		
		// Get data from the model
		$items = $this->get('Data');
		$total = $this->get('Total');
		$pagination = $this->get('Pagination');
			

		// search filter
		$lists['search'] = $search;
		$items = $this->get('Data');
		
		//user ordering
		$lists['order_Dir'] =$filter_order_Dir;
		$lists['order'] =$filter_order;
				
		//pagination
		$pagination = $this->get( 'Pagination' );
		
		$this->assign('user', JFactory :: getUser());
		$this->assign('lists', $lists);	
		$this->assign('items', $items);		
		$this->assign('total', $total);
		$this->assign('pagination', $pagination);		
		parent::display();
	}
	
	/**
	 *  Displays the form
 	 * @param string $tpl   
     */
	public function _displayForm($tpl)
	{
		global  $alt_libdir;
		
		JLoader::import('joomla.form.formvalidator', $alt_libdir);		
		JHTML::stylesheet( 'fields.css', 'administrator/components/com_virtualdomains/assets/' );

		$db			= JFactory::getDBO();
		$uri 		= JFactory::getURI();
		$user 		= JFactory::getUser();
		$form		= $this->get('Form');
		
		$lists = array();

		$editor = JFactory :: getEditor();

		//get the item
		$item	=$this->get('item');
		
	     if(!version_compare(JVERSION,'3.0','lt')) {
        	$form->bind(JArrayHelper::fromObject($item));
        } else {
        	$form->bind($item);
        }      
		
		$isNew		= ($item->id < 1);			

		// Edit or Create?
		if ($isNew) {
			// initialise new record
			$item->published = 1;
		}
	
		//$lists['published'] 		= JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $item->published );
		
	 	$this->assign('form', $form);
	 
		$this->assign('lists', $lists);
		$this->assign('editor', $editor);
		$this->assign('item', $item);
		$this->assign('isNew', $isNew);
	
		parent::display($tpl);
	}
}
?>