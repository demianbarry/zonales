<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProviderModelProvider
 *
 * @author g2p
 */
class ProviderModelProvider extends JModel{

    function getProviderList($where = null) {
        $query = 'select * from #__providers p ';

        if ($where != null){
            $query = $query . 'where ';
            foreach ($where as $field => $value) {
                $query = $query . ' ' . $field . '=' . $value . 'and';
            }
        }

        $query = $query . 'order by p.order ASC';
        $db = $this->getDBO();
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}
?>
