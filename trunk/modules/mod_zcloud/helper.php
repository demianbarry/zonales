<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT . DS . 'components' . DS . 'com_eqzonales' . DS . 'hlapi.php';

class modCloudHelper {

    /**
     *
     * @return array arreglo[tagname]=importancia
     */
    static function getTags($amount,$minRelevance) {
        // TODO invocar la api del ecualizador para obtener los tags ecualizados
        // descartar los tags que no cumplan con el "interes" minimo
        // en caso de que la cantidad de tags superen la cantidad maxima especificada
        // descartar los tags de menor importancia (que superen el minimo deseado)
        // hasta que se cumpla con la restriccion de cantidad
        // devolver los tags

        // recupera los tags del usuario en orden mayor relevancia a menor
        $user = JFactory::getUser();
        $tagsRaw = HighLevelApi::getTags($user->id);

        // averigua donde comienzan los tags no deseados
        $length = count($tagsRaw);
        foreach ($tagsRaw as $key => $currentTag) {
            if ($currentTag->relevance < $minRelevance){
                $length = $key;
                break;
            }
        }

        // corta el arreglo para que solo queden los tags mas relevantes
        $onlyRelevants = array_slice($tagsRaw, 0, $length);

        // si la cantidad de tags resultantes supera la cantidad solicitada
        if (count($onlyRelevants) > $amount){
            // corta el arreglo para que se ajuste a la cantidad solicitada
            $tags = array_slice($onlyRelevants, 0, $amount);
        }
        else {
            $tags = $onlyRelevants;
        }

        /*
         * convierte el formato de los datos a los esperados por el invocador
         */
        $outTags = array();
        $in = array();
        foreach ($tags as $currentTag) {
            $in[] = $currentTag->id;
        }

        $db = JFactory::getDBO();
        $select = 'select v.name, v.id from #__custom_properties_values v ' .
                    'where v.id in (' . implode(',', $in) . ')';
        $db->setQuery($select);
        $dbTags = $db->loadObjectList();

        foreach ($tags as $current) {
            foreach ($dbTags as $pos => $currDb) {
                if ($currDb->id == $current->id){
                    $currentTag = $pos;
                    break;
                }
            }
            $tagname = $dbTags[$currentTag]->name;
            $outTags[$tagname] = $current->relevance;
        }

        return $outTags;
    }

    static function showHtmlTag($tag,$size) {
        echo '<a href="' . JRoute::_('index.php?searchword='.htmlentities( $tag ).'&amp;ordering=&amp;searchphrase=all&amp;option=com_search' ) . '" style="padding: 0px 4px; font-size: ' . $size . 'em">' . htmlentities( $tag ) . '</a>';
    }
}

?>
