<?php
/**
* @version	$Id$
* @package	Zonales
* @subpackage	Eq
* @copyright	Copyright (C) 2005 - 2008. Mediabit. All rights reserved.
* @license	GNU/GPL, see LICENSE.php
*
* Zonales is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class comEqZonalesHelper
{

    function getEqJsonResponse($status, $msg)
    {
        $response = array('status' => $status, 'msg' => $msg);
        return json_encode($response);
    }

}