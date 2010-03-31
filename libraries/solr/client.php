<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'query.php';

/**
 * Esta clase se encarga de llevar a cabo todas las oeraciones genericas
 *
 * @author kristian
 */
class SolrClient {
    var $host;

    function getHost() {
        return $this->host;
    }

    function setHost($host) {
        $this->host = $host;
    }

    /**
     *
     * @param SolrQuery $query
     * @return array resultados de la busqueda
     */
    function executeQuery($query) {
        if ($query instanceof SolrQuery) {
            $solrUrl = $this->getHost();
            $queryData = $query->getQuery();

            $file = $solrUrl . '/select?';
            $aux = array();
            foreach ($queryData as $queryType => $currentQuery) {
                $aux[] = $queryType . '=' . urlencode($currentQuery);
            }

            $queryPart = implode('&', $aux);
            $file = $file . $queryPart . '&wt=phps';

            $serializedResult = file_get_contents($file);

            $result = unserialize($serializedResult);
            return $result;
        }
        else throw new Exception('not allowed class');
    }
}
?>
