<?php
defined('_JEXEC') or die('Restricted access');

$user	 =& JFactory::getUser();
$idUser = $user->id;

$db = &JFactory::getDBO();
$timeInterval = $params->get('days', 0);

// alerta sobre los alias registrados en los ultimos dias
$countPending = 'select count(*) as total from #__alias a where ' .
                'a.user_id=' . $idUser . ' and a.block=1 ' .
                'and a.association_date > date_sub(curdate(),interval ' . $timeInterval . ' day)';

$db->setQuery($countPending);
$dbcount = $db->loadObject();

if ($dbcount->total != 0){
	echo '<a class=pending-notice href="index.php?option=com_alias&view=alias">' . jtEXT::_('ZONALES_ALIAS_HAS_BLOCK') . '</a>';
}

?>

