<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

function mod_install()
{
     $js_library_path = JPATH_ROOT.DS.'media'.DS.'system'.DS.'js'.DS;
     $target_file = 'webtoolkit.js';
     JFile::copy($target_file, $js_library_path);
}

?>