<?php

jimport('g2p-utils.filesystem');

/*
 * Instala las bibliotecas necesarias para interactuar con los
 * proveedores de autenticacion
 */
function plg_install() {
    $dest = JPATH_LIBRARIES;
    $baseSrc = 'libraries';

    $baseSrc = '.' . DS . 'libraries';

    $libraries = scandir($baseSrc);

    foreach ($libraries as $currentLibDir) {
        $src = $baseSrc . DS . $currentLibDir;
        G2PFileSystemUtils::copy($src, $dest);
    }

    return "Component successfully installed.";
}

?>
