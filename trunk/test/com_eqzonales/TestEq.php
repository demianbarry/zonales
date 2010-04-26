<?php

class TestEq extends UnitTestCase {

    var $r;
    var $cookieName = 'cookieName';
    var $cookieValue = 'cookieValue';
    var $userhttp = 'userName';
    var $passhttp = 'password';
    var $host = 'host';
    var $port = 1234;
    var $db = 'db';
    var $userdb = 'userdb';
    var $passdb = 'passdb';

    function TestEq() {
        $this->UnitTestCase('TestEq');
    }

    function setUp() {
        $this->r = new HttpRequest('http://www.zonales.com.ar:50080/', HttpRequest::METH_POST);
        $ini_array = parse_ini_file("conf.ini");
        $this->userhttp = $ini_array['userhttp'];
        $this->passhttp = $ini_array['passhttp'];
        $this->host = $ini_array['host'];
        $this->port = $ini_array['port'];
        $this->db = $ini_array['db'];
        $this->userdb = $ini_array['userdb'];
        $this->passdb = $ini_array['passdb'];
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

        $r = new HttpRequest("http://$userhttp:$passhttp@www.zonales.com.ar:50080/", HttpRequest::METH_POST);
        $r->addCookies(array("$cookieName" => "$cookieValue"));
        $r->addPostFields($params);

        try {
            $resp = $r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                if ($this->assertEqual("SUCCESS", $json_resp->status)) {

                    $con = mysql_connect("$host:$port", $userdb, $passdb);
                    if (!$con) {
                        die('No se pudo conectar: ' . mysql_error());
                    }
                    mysql_select_db($db, $con);
                    if (!$db_selected) {
                        die ('No se puede conectar a la base de datos : ' . mysql_error());
                    }
                    $query = mysql_query("SELECT count(*) AS c FROM jos_eqzonales_eq t WHERE t.nombre = 'TEST1'");
                    if (!$query) {
                        die('Consulta inválida: ' . mysql_error());
                    }
                    $result = mysql_fetch_array($query);
                    $this->assertEqual(1, intval($result[0]));
                    mysql_close($con);
                }
            }
        } catch (HttpException $ex) { }
    }

    function testFallaCreaNuevoEcualizadorUsuarioIncorrecto() {
        $json_msg = array("user_id" => -1, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.createeq',
                'format' => 'raw',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $r = new HttpRequest("http://$userhttp:$passhttp@www.zonales.com.ar:50080/", HttpRequest::METH_POST);
        $r->addCookies(array("$cookieName" => "$cookieValue"));
        $r->addPostFields($params);

        try {
            $resp = $r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("FAILURE", $json_resp->status);
            }
        } catch (HttpException $ex) { }
    }

    function testFallaCreaNuevoEcualizadorUsuarioGuest() {
        $json_msg = array("user_id" => 0, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.createeq',
                'format' => 'raw',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $r = new HttpRequest("http://$userhttp:$passhttp@www.zonales.com.ar:50080/", HttpRequest::METH_POST);
        $r->addPostFields($params);

        try {
            $resp = $r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("FAILURE", $json_resp->status);
            }
        } catch (HttpException $ex) { }
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
