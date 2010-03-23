<?php

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model' );

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
     * Lista de planes de tipo parental asociados al plan actual.
     *
     * @param int id del plan en la base de datos. si se omite se usa el id de la instancia.
     * @return array arreglo de objetos de datos plan
     */
    function getUserEqs($user_id = 0)
    {
        $dbo = $this->getDBO();

        $where = ' WHERE '. $dbo->nameQuote('p.id') .' IN ('.
                ' SELECT '. $dbo->nameQuote('ptp.plan_id_dst').
                ' FROM '. $dbo->nameQuote('#__demo_plans2plans') .' ptp'.
                ' WHERE '. $dbo->nameQuote('ptp.plan_id_src') .' = ' . $plan_id .
                ' ) AND '. $dbo->nameQuote('p.type') .' = ' . $dbo->quote('parental');

        return $this->_getList($this->_getQuery() . $where);
    }
}