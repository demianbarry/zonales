<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_zonales' . DS . 'models' . DS . 'basemodel.php';

class EqZonalesModelEq extends ZonalesModelBaseModel {


    function _getQuery() {
        $dbo =& JFactory::getDBO();

        $query =
                'SELECT '
                .$dbo->nameQuote('e.id') .','. $dbo->nameQuote('e.nombre') .','
                .$dbo->nameQuote('e.descripcion') .','. $dbo->nameQuote('e.observaciones') .','
                .$dbo->nameQuote('e.user_id') .','. $dbo->nameQuote('e.solrquery_bq')
                .' FROM ' . $dbo->nameQuote('#__eqzonales_eq') . ' e';

        return $query;
    }

    /**
     * Query para obtener un registro particular desde la base de datos
     * Clases que extienda este BaseModel deben reimplementar este metodo.
     * 
     * @param <type> $customQuery
     * @return <type>
     */
    function _buildQuery($customQuery = false) {
        $query = $this->_getQuery();

        if (!$customQuery) {
            $this->setWhere('e.id = ' . $this->_id );
        }
        return $query . $this->getWhereClause();
    }

    /**
     * Query para obtener múltiples registros desde la base de datos.
     * Clases que extienda este BaseModel deben reimplementar este metodo.
     * 
     * @return <type>
     */
    function _buildAllQuery() {
        $query = $this->_getQuery();
        return $query . $this->getWhereClause() . $this->getOrderByClause();
    }

    function afterCheck(&$row) {
        return true;
    }

    /**
     *
     * @param integer el id del usuario
     * @return array lista de ecualizadores del usuario
     */
    function getUserEqs($user_id = 0)
    {

        $this->setWhere('e.user_id = ' . $user_id);
        $eqs = $this->getAll(true);

        return $eqs;
    }
}