<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SimpleXMLExtended
 *
 * @author nacho
 */
class SimpleXMLExtended extends SimpleXMLElement {

    public function addCDATA($cData) {
        $node = dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cData));
    }

}
?>

