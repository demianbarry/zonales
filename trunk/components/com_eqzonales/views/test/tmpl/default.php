<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<form action="index.php" method="post" name="setZonalComForm" id="setZonalComForm">
    <input class="inputbox" type="text" name="task" id="task" size="50" maxlength="200" />
    <textarea class="inputbox" cols="60" rows="30" name="params" id="params"></textarea>

    <input type="hidden" name="option" value="com_eqzonales" />
    <?php echo JHTML::_('form.token'); ?>
</form>