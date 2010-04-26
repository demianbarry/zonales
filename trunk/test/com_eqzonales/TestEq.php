<?php

class TestEq extends UnitTestCase {

    var $r;

    function setUp() {
        $r = new HttpRequest('http://www.zonales.com.ar:50080/blabla', HttpRequest::METH_POST);
    }

    function tearDown() {
        
    }

    function testCreaNuevoEcualizador() {        
        $json_msg = array("user_id" => 1, "nombre_eq" => "TEST1");

        $params = array(
            'task' => 'eq.createeq',
            'params' => json_encode($json_msg)
            );

        $r->addPostFields($params);

        try {
            $resp = json_decode($r->send()->getBody());
            assertTrue($resp);
        } catch (HttpException $ex) {

        }
    }

    function estFallaCreaNuevoEcualizador() {
        $json_msg = array("user_id" => 1, "nombre_eq" => "TEST1");

        $params = array(
            'task' => 'eq.createeq',
            'params' => json_encode($json_msg)
            );

        $r->addPostFields($params);

        try {
            $resp = json_decode($r->send()->getBody());
            assertFalse($resp);
        } catch (HttpException $ex) {

        }
    }

    function testModificaEcualizador() {

    }

    function testFallaModificaEcualizador() {

    }

    function testRecuperaEcualizador() {

    }

    function testFallaRecuperaEcualizador() {

    }

    function testRecuperaEcualizadorUsuario() {

    }

    function testFallaRecuperaEcualizadorUsuario() {
        
    }

}
