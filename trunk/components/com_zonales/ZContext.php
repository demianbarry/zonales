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

   public function __construct() {
        $this->filters = new stdClass();
        $this->filters->source = array();
        $this->filters->temporalidad = "";
        $this->filters->tags = array();
        $this->zTabs = array();
        $this->zTab = "";
        $this->selectedZone = "";
        $this->effectiveZone = "";
    }

   public function getTemporalidad() {
        return $this->filters->temporalidad;
    }

   public function setTemporalidad($temporalidad) {
        $this->filters->temporalidad = $temporalidad;
    }

   public function getSource() {
        return $this->filters->source;
    }

   public function setSource($sources) {
        $this->filters->source = $sources;
    }

   public function getTags() {
        return $this->filters->tags;
    }

   public function setTags($tags) {
        $this->filters->tags = $tags;
    }

   public function getZtab() {
        return $this->zTab;
    }

   public function setZtab($zTab) {
        $this->zTab = $zTab;
    }

   public function getSelectedZone() {
        return $this->selectedZone;
    }

   public function setSelectedZone($zone) {
        $this->selectedZone = $zone;
    }

   public function getEffectiveZone() {
        return $this->effectiveZone;
    }

   public function setEffectiveZone($zone) {
        $this->effectiveZone = $zone;
    }

   public function getFilters() {
        return $this->filters;
    }

   public function setFilters($filters) {
        $this->filters = $filters;
    }

    

}