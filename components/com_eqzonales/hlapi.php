<?php

/*
 * In the context of information science and information retrieval,
 * relevance denotes how well a retrieved set of documents
 * (or a single document) meets the information need of the user.
 * en.wikipedia.org/wiki/Relevance_(information_retrieval)
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT . DS . 'controllers' . DS . 'eq.php';
jimport('joomla.user.user');

function compareInfo($one,$two) {
    if ($one->relevance == $two->relevance){
        return 0;
    }

    // miento sobre el resultado de la comparacion
    // para forzar el ordenanmiento mayor a menor
    return ($one->relevance > $two->relevance) ? -1 : 1 ;
}

class HighLevelApi {

    /**
     *
     * @param integer User ID
     * @return array of stdClass with tags ids and relevance factor
     */
    static function getTags($userid) {
            $engine = new EqZonalesControllerEq();

            $aInfo = $engine->retrieveUserEqImpl($userid);

            $tags = array();
            foreach ($aInfo as $currentInfo) {
                $eq = $currentInfo->eq;
                $bands = $currentInfo->bands;

                // por cada banda
                foreach ($bands as $currentBand) {
                    // recupero los datos
                    $tagInfo = new stdClass();
                    // y los encapsulo
                    $tagInfo->id = $currentBand->cp_value_id;
                    $tagInfo->relevance = $currentBand->peso;

                    $tags[] = $tagInfo;
                }
            }

            // ordeno el arreglo por relevancia
            if (usort($tags, 'compareInfo')){
                return $tags;
            }
            throw new Exception('The tags array could not be sorted');
    }
}

?>
