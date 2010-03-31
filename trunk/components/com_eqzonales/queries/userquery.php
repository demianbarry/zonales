<?php

jimport('solr.query');
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_eqzonales' . DS . 'models' . DS . 'eq.php';
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_eqzonales' . DS . 'models' . DS . 'banda.php';

class UserQuery implements SolrQuery {
    var $user;
    var $environment;

    function UserQuery($user){
        return $this->__construct($user);
    }

    function  __construct($user,$environment) {
        $this->setUser($user);
        $this->setEnvironment($environment);
    }

    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function getEnvironment() {
        return $this->environment;
    }

    /**
     *
     * @param array $env Arreglo de arreglos con la siguiente estructura
     * arreglo['field'] , arreglo['value'] , arreglo['type']
     */
    function setEnvironment($env) {
        if (!is_array($env)){
            throw new Exception('The variable is not an array');
        }
        $this->environment = $env;
    }

    function getQuery() {
        // recupero los datos del ecualizador asociado al usuario
        $eqModel = new EqZonalesModelEq();
        $eqModel->setWhere('e.user_id=' . $this->getUser()->id);
        $eqData = $eqModel->getData(true, true);

        // armo el query
        $auxQuery = array();
        $auxBoostQuery = array();
        $auxFilterQuery = array();
        $env = $this->getEnvironment();
        
        foreach ($env as $data) {
            // recupero los datos
            $fieldPart = (is_null($data[SolrQuery::FIELD]) || !isset ($data[SolrQuery::FIELD])) ? '' : $data[SolrQuery::FIELD] . ':';
            $part = $fieldPart . $data[SolrQuery::VALUE];

            // discrimino el tipo de query y lo guardo en el arreglo correspondiente
            if ($data[SolrQuery::TYPE] == SolrQuery::QUERY){
                $auxQuery[] = $part;
                continue;
            }

            if ($data[SolrQuery::TYPE] == SolrQuery::FILTER_QUERY){
                $auxFilterQuery[] = $part;
                continue;
            }

            if ($data[SolrQuery::TYPE] == SolrQuery::BOOST_QUERY){
                $auxBoostQuery[] = $part;
                continue;
            }
        }

        $queryBoost = $eqData->solrquery_bq;
        // si llego una query de tipo boost la adjunto al query boost interno
        if (!empty ($auxBoostQuery)){
            $queryBoost = $queryBoost . ' AND ' . implode(' AND ', $auxBoostQuery);
        }

        $queryFilter = implode(' AND ', $auxFilterQuery);
        $queryQuery = implode(' AND ', $auxQuery);

        // guardo los queries en donde corresponda
        $query = array();

        if (isset ($queryQuery)){
            $query[SolrQuery::QUERY] = $queryQuery;
        }

        if (isset ($queryBoost)){
            $query[SolrQuery::BOOST_QUERY] = $queryBoost;
        }

        if (isset ($queryFilter)){
            $query[SolrQuery::FILTER_QUERY] = $queryFilter;
        }

        return $query;
    }
}

?>
