<?php

class TestEq extends UnitTestCase {

    var $r;

    function TestEq() {
        $this->UnitTestCase('TestEq');
    }

    function setUp() {
        $r = new HttpRequest('http://www.zonales.com.ar:50080/', HttpRequest::METH_POST);
    }

    function tearDown() {

    }

    function testCreaNuevoEcualizador() {
        $json_msg = array("user_id" => 1, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.createeq',
                'format' => 'raw',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $r = new HttpRequest('http://user:pass@www.zonales.com.ar:50080/', HttpRequest::METH_POST);
        $r->addCookies(array('name' => 'value'));
        $r->addPostFields($params);

        try {
            $resp = $r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("SUCCESS", $json_resp->status);
            }

            $con = mysql_connect("host:puerto","usuario","password");
            if (!$con) {
                die('No se pudo conectar: ' . mysql_error());
            }
            mysql_select_db("db", $con);
            $result = mysql_query("SELECT count(*) AS c FROM jos_eqzonales_eq t WHERE t.nombre = 'TEST1'");
            $this->assetEqual(1, $result['c']);
            mysql_close($con);
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
