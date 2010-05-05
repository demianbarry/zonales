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
            if ($withExtraTagInfo && count($ids) > 0) {
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

    /**
     *
     * @param integer $userid
     * @param string $value
     * @return array
     */
    static function getAncestors($userid,$value) {

        // recupero la informacion sobre el valor especificado
        $db = JFactory::getDBO();
        $queryId = 'select cpv.id from #__custom_properties_values cpv where cpv.name=' . $db->Quote($value);
        $db->setQuery($queryId);
        $valueDb = $db->loadObject();

        $valueId = $valueDb->id;

        // recupero los ancestros del valor especificado
        $ancestors = array();
        $query = 'select cpv.parent_id, cpv.name, cpv.id from #__custom_properties_values cpv where cpv.id=';

        do {
            $ancestors[$value] = $id;
            if ($valueId == 0) break;
            $currentQuery = $query . $valueId;
            $db->setQuery($currentQuery);
            $parentDB = $db->loadObject();
            $valueId = $parentDB->parent_id;
            $value = $parentDB->name;
            $id = $parentDB->id;
        }while(true);

        // busco si los valores estan en las bandas del usuario especificado
        $aInfo = $engine->retrieveUserEqImpl($userid);
        $out = array();
        foreach ($aInfo as $currentInfo) {
                $bands = $currentInfo->bands;

                foreach ($bands as $currentBand) {
                    $vId = $currentBand->cp_value_id;
                    $key = array_search($vId, $ancestors);
                    $aux = (isset ($out[$key])) ? $out[$key] : 0;
                    // si el valor tiene un peso asignado en el ecualizador
                    if ($key){
                        // acumulo el peso a los anteriores (si correspondiere)
                        $out[$key] = $aux + $currentBand->peso;
                    }
                    else {
                        // si no, lo dejo como esta
                        $out[$key] = $aux;
                    }
                }
        }

        // ordeno el arreglo de salida en base a su relevancia
        // y lo devuelvo
        return rsort($out,SORT_NUMERIC);
    }
}

?>
