<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comReservasHelper
{
	/** @var JCache */
	var $_cache = null;

	function __construct($default = array())
	{
            $this->_cache =& JFactory::getCache('com_reservas');
	}

	function verDisponibilidades($grupo_recursos, $fecha, $hora_desde, $hora_hasta) {
            
        }

        function reservaRecurso($disponibilidad) {
            
        }

        function getConfiguracionVisual() {

        }

        function getLocaciones() {

        }

        function getJavaFX() {

        }
}