<?php
defined('_JEXEC') or die ('Restricted Access');
class TOOLBAR_aardvertiser {
	function _NEW() {
		JToolBarHelper::title(JText::_('Edit Advert'), 'generic.png');
		JToolBarHelper::save();
		JToolBarHelper::cancel();
	}
	function _DEFAULT() {
		JToolBarHelper::title(JText::_('Aardvertiser'), 'generic.png');
		JToolBarHelper::addNew();
		JToolBarHelper::editList();
		JToolBarHelper::deleteList();
		JToolBarHelper::custom('prune', 'archive.png', 'archive.png', 'Prune', false, false);
		JToolBarHelper::custom('payment', 'config.png', 'config.png', 'Payment Config', false, false);
		JToolBarHelper::custom('conf', 'config.png', 'config.png', 'Config', false, false);
	}
	function _CONF() {
		JToolBarHelper::title(JText::_('Configuration'), 'generic.png');
		JToolBarHelper::custom('showcss', 'css.png', 'css.png', 'Edit Css', false, false);
		JToolBarHelper::custom('email', 'config.png', 'config.png', 'Edit Email Settings', false, false);
		JToolBarHelper::save('saveconf');
		JToolBarHelper::cancel();
	}
	function _CSS() {
		JToolBarHelper::title(JText::_('Edit CSS'), 'generic.png');
		JToolBarHelper::save('savecss');
		JToolBarHelper::cancel('conf');
	}
	function _EMAIL() {
		JToolBarHelper::title(JText::_('Email Settings'), 'generic.png');
		JToolBarHelper::save('savemail');
		JToolBarHelper::cancel('conf');
	}
}
class TOOLBAR_aardvertiser_categories {
	function _NEW() {
		JToolBarHelper::title(JText::_('New Category'), 'generic.png');
		JToolBarHelper::save('savecategory');
		JToolBarHelper::cancel('categories');
	}
	function _DEFAULT() {
		JToolBarHelper::title(JText::_('Categories'), 'generic.png');
		JToolBarHelper::addNew('addcategory');
		JToolBarHelper::editList('editcategory');
		JToolBarHelper::deleteList('Are you sure you want to remove this category?', 'removecategory');
	}
}
?>