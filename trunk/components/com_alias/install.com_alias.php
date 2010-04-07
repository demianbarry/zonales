<?php

/*
 * Instala los iconos
 */
function com_install() {
    $file = new JFile();
    $dest = JPATH_ROOT . DS . 'images' . DS . 'login' . DS;
    $baseSrc = '.' . DS . 'login';

    $icons = scandir($baseSrc);

    foreach ($icons as $currentIcon) {
        $src = $baseSrc . DS . $currentIcon;
        $file->copy($src, $dest);
    }

    return "Component successfully installed.";
}

?>
