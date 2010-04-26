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
        $ini_array = parse_ini_file("../configuration.ini");
        $this->cookieName = $ini_array['cookiename'];
        $this->cookieValue = $ini_array['cookievalue'];
        $this->userhttp = $ini_array['userhttp'];
        $this->passhttp = $ini_array['passhttp'];
        $this->host = $ini_array['host'];
        $this->port = $ini_array['port'];
        $this->db = $ini_array['db'];
        $this->userdb = $ini_array['userdb'];
        $this->passdb = $ini_array['passdb'];

        $this->r = new HttpRequest("http://$this->userhttp:$this->passhttp@www.zonales.com.ar:50080/", HttpRequest::METH_POST);
        $this->r->addCookies(array("$this->cookieName" => "$this->cookieValue"));
        $this->r->addPostFields(array('format' => 'raw'));
    }

    function tearDown() {

    }
    
    function testCreaNuevoEcualizador() {
        $json_msg = array("user_id" => 63, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.createeq',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $this->r->addPostFields($params);

        try {
            $resp = $this->r->send()->getBody();
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
        } catch (HttpException $ex) {

        }
    }
    
    function testFallaCreaNuevoEcualizadorUsuarioNoExistente() {
        $json_msg = array("user_id" => -1, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.createeq',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $this->r->addPostFields($params);

        try {
            $resp = $this->r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("FAIL", $json_resp->status);
            }
        } catch (HttpException $ex) {

        }
    }

    /**
     * NOTA: El usuario user_id debe existir y debe ser distinto que el usuario
     * que ha iniciado sesión. Este test debe fallar para un usuario no adminis-
     * trador.
     */
    function testFallaCreaNuevoEcualizadorOtroUsuario() {
        $json_msg = array("user_id" => 64, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.createeq',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $this->r->addPostFields($params);

        try {
            $resp = $this->r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("FAIL", $json_resp->status);
            }
        } catch (HttpException $ex) {

        }
    }

    function testFallaCreaNuevoEcualizadorUsuarioAnonimo() {
        $json_msg = array("user_id" => 0, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.createeq',                
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $this->r->addPostFields($params);

        try {
            $resp = $this->r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("FAIL", $json_resp->status);
            }
        } catch (HttpException $ex) {

        }
    }

    function testModificaEcualizador() {
        $json_msg = array("eq_id" => 1, "user_id" => 63, "nombre" => "TEST1");

        $params = array(
                'task' => 'eq.modifyeq',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $this->r->addPostFields($params);

        try {
            $resp = $this->r->send()->getBody();
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
        } catch (HttpException $ex) {

        }
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
