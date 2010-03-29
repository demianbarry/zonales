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
     * arreglo['field'] , arreglo['value'] , arreglo['boost']
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

//        // recupero los datos de las bandas asociados al ecualizador
//        $bandaModel = new EqZonalesModelBanda();
//        $bandaModel->setWhere('e.eq_id=' . $eqData->id);
//        $customQuery = $bandaModel->_buildQuery(true);
//        $bandaModel->getDBO()->setQuery($customQuery);
//        $bandas = $bandaModel->getDBO()->loadObjectList();
//
        // armo el query
        $aux = array();
        foreach ($this->getEnvironment() as $data) {
            // si no esta especificado el field se usa el default
            $fieldPart = (is_null($data[SolrQuery::FIELD])) ? '' : $data[SolrQuery::FIELD] . ':';

            $boostPart = (is_null($data[SolrQuery::BOOST])) ? '' : '^' . $data[SolrQuery::BOOST];
            $aux[] = $fieldPart . $data[SolrQuery::VALUE] . $boostPart;
        }

        $query = $eqData->solrquery_bq . ' AND ' . implode(' AND ', $aux);

        return $query;
    }
}

?>
