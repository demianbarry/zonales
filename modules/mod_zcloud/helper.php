<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT . DS . 'components' . DS . 'com_eqzonales' . DS . 'hlapi.php';

class modCloudHelper {
    const RELEVANCES = 'relevances';
    const LABELS = 'labels';

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

        // para evitar errores de tabla no encontrada
        $path = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS .
                'com_eqzonales' . DS . 'tables';
        JTable::addIncludePath($path);

        // recupera los tags del usuario en orden mayor relevancia a menor
        $user = JFactory::getUser();
        $tagsRaw = HighLevelApi::getTags($user->id,true);

        // averigua donde comienzan los tags no deseados
        $length = count($tagsRaw);
        foreach ($tagsRaw as $pos => $currentTag) {
            if ($currentTag->relevance < $minRelevance){
                $length = $pos;
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
        $relevances = array();
        $labels = array();

        foreach ($tags as $tag) {
            $relevances[$tag->name] = $tag->relevance;
            $labels[$tag->name] = $tag->label;
        }

        $outTags[modCloudHelper::RELEVANCES] = $relevances;
        $outTags[modCloudHelper::LABELS] = $labels;

        return $outTags;
    }

    static function showHtmlTag($tag,$tagLabel,$size) {
        echo '<a href="' . JRoute::_('index.php?searchword='.htmlentities( $tag ).'&amp;ordering=&amp;searchphrase=all&amp;option=com_search' ) . '" style="padding: 0px 4px; font-size: ' . $size . 'em">' . htmlentities( $tagLabel ) . '</a>';
    }
}

?>
