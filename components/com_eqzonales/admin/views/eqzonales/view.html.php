<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class EqZonalesViewEqZonales extends ZonalesViewBaseView
{
	function display($tpl = null)
	{
		global $option, $mainframe;

		// Titulo
		$document = & JFactory::getDocument();
		$document->setTitle(JText::_( 'Administrar Ecualizadores' ));

		parent::display($tpl);
	}
}
