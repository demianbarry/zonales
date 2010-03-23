<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class modCloudHelper {

    /**
     *
     * @return array arreglo[tagname]=importancia
     */
    static function getTags($amount,$minPriority) {
        // TODO invocar la api del ecualizador para obtener los tags ecualizados
        // descartar los tags que no cumplan con el "interes" minimo
        // en caso de que la cantidad de tags superen la cantidad maxima especificada
        // descartar los tags de menor importancia (que superen el minimo deseado)
        // hasta que se cumpla con la restriccion de cantidad
        // devolver los tags

        $db = JFactory::getDBO();
        $db->setQuery( 'SELECT `metakey` FROM #__content WHERE `metakey` <> "" AND `state` = 1' );
        // build tag array
        $tags = array();
        if( $res = $db->loadObjectList() ) {
            foreach( $res as $key ) {
                $k = explode( ',', $key->metakey );
                foreach( $k as $tag ) {
                    $tags[trim($tag)] += 1;
                }
            }
        }

        return $tags;
    }

    static function showHtmlTag($tag,$size) {
        echo '<a href="' . JRoute::_('index.php?searchword='.htmlentities( $tag ).'&amp;ordering=&amp;searchphrase=all&amp;option=com_search' ) . '" style="padding: 0px 4px; font-size: ' . $size . 'em">' . htmlentities( $tag ) . '</a>';
    }
}

?>
