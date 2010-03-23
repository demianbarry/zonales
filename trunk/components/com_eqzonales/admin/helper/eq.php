<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class comEqZonalesEqHelper
{

    /**
     * Genera un nuevo ecualizador para el usuario indicado.
     * |
     * @param int $user_id id usuario
     * @param String $eq_name nombre del ecualizador
     */
    function createEq ($user_id, $eq_name) {

        // TODO: Chequea que el no exista un ecualizador con el mismo nombre
        
        // Crea nueva instancia del ecualizador
        $eqData = array(
                'nombre' => $eq_name,
                'user_id' => $user_id
                );

        // Agrega bandas por defecto del ecualizador
        
    }

}