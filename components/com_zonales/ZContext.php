<script language="javascript" type="text/javascript">
    <!--
    
    function Filters(){
        this.source = new Array();
        this.temp = "";
        this.tags = new Array();
    }
    
    function ZContext(){
        this.filters = new Filters();
        this.zTabs = new Array();
        this.zTab = "";
        this.selZone = "";
        this.efZone = "";
    }
    
    if(!zctx) {
        var zctx = new ZContext();
        
    }
    

    -->
</script>

<?php
/**
 * @version	$Id$
 * @package	Zonales
 * @copyright	Copyright (C) 2009 Mediabit. All rights reserved.
 * @license	GNU/GPL, see LICENSE.php
 *
 * Zonales is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

/**
 * Controlador
 *
 * @package Zonales
 * @since 1.5
 */
class ZContext {

    var $filters = null;
    var $zTabs = array();
    var $zTab = null;
    var $selectedZone = null;
    var $effectiveZone = null;

    function __construct() {
        $this->filters = new stdClass();
        $this->filters->source = array();
        $this->filters->temporalidad = "";
        $this->filters->tags = array();
        $this->zTabs = array();
        $this->zTab = "";
        $this->selectedZone = "";
        $this->effectiveZone = "";
    }

    function getTemporalidad() {
        return $this->filters->temporalidad;
    }

    function setTemporalidad($temporalidad) {
        $this->filters->temporalidad = $temporalidad;
    }

    function getSource() {
        return $this->filters->source;
    }

    function setSource($sources) {
        $this->filters->source = $sources;
    }    
    
    
    function getZtab() {
        return $this->zTab;
    }

    function setZtab($zTab) {
        $this->zTab = $zTab;
    }

    function getSelectedZone() {
        return $this->selectedZone;
    }

    function setSelectedZone($zone) {
        $this->selectedZone = $zone;
    }

    function getEffectiveZone() {
        return $this->effectiveZone;
    }

    function setEffectiveZone($zone) {
        $this->effectiveZone = $zone;
    }

    function getFilters() {
        return $this->filters;
    }

    function setFilters($filters) {
        $this->filters = $filters;
    }
    
    function setFilters(filters) {
        $this->filters = filters;
    }

}