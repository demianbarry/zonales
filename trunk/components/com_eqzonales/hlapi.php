<?php

/*
 * In the context of information science and information retrieval,
 * relevance denotes how well a retrieved set of documents
 * (or a single document) meets the information need of the user.
 * en.wikipedia.org/wiki/Relevance_(information_retrieval)
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ROOT . DS . 'components' . DS . 'com_eqzonales' . DS . 'controllers' . DS . 'eq.php';
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
     * @param boolean return the tags names too
     * @return array of stdClass with tags ids and relevance factor
     */
    static function getTags($userid,$withExtraTagInfo) {
            $engine = new EqZonalesControllerEq();

            $aInfo = $engine->retrieveUserEqImpl($userid);

            $tags = array();
            $ids = array();
            foreach ($aInfo as $currentInfo) {
                $eq = $currentInfo->eq;
                $bands = $currentInfo->bands;

                // por cada banda
                foreach ($bands as $currentBand) {
                    $id = $currentBand->cp_value_id;

                    // si no se lo agrego previamente
                    if (!array_search($id, $ids)) {
                    // recupero los datos
                        $tagInfo = new stdClass();
                        // y los encapsulo
                        $tagInfo->id = $id;
                        $tagInfo->relevance = $currentBand->peso;

                        $tags[] = $tagInfo;
                        $ids[] = $id;
                    }

                }
            }
            if ($withExtraTagInfo) {
                $db = JFactory::getDBO();
                $query = 'select v.name, v.id, v.label from #__custom_properties_values v where v.id in (' .
                        implode(',', $ids) . ')';
                $db->setQuery($query);
                $dbTags = $db->loadObjectList();

                foreach ($dbTags as $tagData) {
                    foreach ($tags as $info) {
                        if ($tagData->id == $info->id){
                            $info->name = $tagData->name;
                            $info->label = $tagData->label;
                            break;
                        }
                    }
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
