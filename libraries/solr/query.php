<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author kristian
 */
interface SolrQuery {
    /**
     * @return String retorna la consulta a realizar a solr
     */
    function getQuery();
}
?>
