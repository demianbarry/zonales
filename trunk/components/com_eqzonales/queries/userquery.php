<?php

jimport('solr.query');
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_eqzonales' . DS . 'models' . DS . 'eq.php';
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_eqzonales' . DS . 'models' . DS . 'banda.php';

class UserQuery implements SolrQuery {
    var $user;

    function UserQuery($user){
        return $this->__construct($user);
    }

    function  __construct($user) {
        $this->setUser($user);
    }

    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }

    function getQuery() {
        // recupero los datos del ecualizador asociado al usuario
        $eqModel = new EqZonalesModelEq();
        $eqModel->setWhere('e.user_id=' . $this->getUser()->id);
        $eqData = $eqModel->getData(true, true);

        $query = $eqData->solrquery_bq;

//        // recupero los datos de las bandas asociados al ecualizador
//        $bandaModel = new EqZonalesModelBanda();
//        $bandaModel->setWhere('e.eq_id=' . $eqData->id);
//        $customQuery = $bandaModel->_buildQuery(true);
//        $bandaModel->getDBO()->setQuery($customQuery);
//        $bandas = $bandaModel->getDBO()->loadObjectList();
//
//        // armo el query
//        $query = '';
//        foreach ($bandas as $bandaActual) {
//            $query = $query . $bandaActual->valor . '^' . $bandaActual->peso . ' AND ';
//        }

        return $query;
    }
}

?>
