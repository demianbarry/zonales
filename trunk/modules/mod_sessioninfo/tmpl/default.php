<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );
?>

<script language="javascript" type="text/javascript">
<!--
    function logout(){
        <?php echo ($protocol == 'Facebook Connect') ? 'FB.Connect.logout(function() {  });' : '' ?>
        window.location.href='<?php echo $logoutRoute ?>';
    }
//-->
</script>

<div class="moduletable_sessioninfo">
    <p class="greeting">
        <a href="<?php echo $profileLink ?>">
            <?php echo $greetingMessage ?>
        </a>
    </p>
    <input type="button"
           value="<?php echo $sessionCloseMessage ?>"
           name="closesession"
           class="closesession"
           onclick="logout()" />
</div>
