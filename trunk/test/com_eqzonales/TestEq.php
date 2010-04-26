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

        // Agrega a la base de datos los usuarios TEST1 y TEST2 -- necesarios para las pruebas
        $this->createTestUsers();
    }

    function tearDown() {

    }

    function createTestUsers() {
        $con = mysql_connect("$host:$port", $userdb, $passdb);
        if (!$con) {
            die('No se pudo conectar: ' . mysql_error());
        }
        mysql_select_db($db, $con);
        if (!$db_selected) {
            die ('No se puede conectar a la base de datos : ' . mysql_error());
        }
        $query = mysql_query(
                "INSERT INTO `jos_users` (`id`, `name`, `username`, `email`, `email2`, `password`, `usertype`, `block`, `sendEmail`, `gid`, `registerDate`, `lastvisitDate`, `activation`, `params`, `birthdate`, `sex`) VALUES ".
                "(11, 'TEST2', 'TEST2', 'franpaez+test2@gmail.com', '', '8fb749b6f11b1e81c82f41facf53353f:bRItDryGITOsVMldmjPJO2SxYLuv4275', 'Registered', 0, 0, 18, '2010-04-26 19:13:57', '0000-00-00 00:00:00', '', 'admin_language=\nlanguage=\neditor=\nhelpsite=\ntimezone=-3\n\n', '0000-00-00', 'M'),".
                "(10, 'TEST1', 'TEST1', 'franpaez+test1@gmail.com', '', '62e1e53aec0cfbbf3f180dcbf97f420f:mWdi8zU5fUrmJgFfaSf8PVL8gPsmWVT7', 'Registered', 0, 0, 18, '2010-04-26 19:12:02', '2010-04-26 23:22:43', '', 'admin_language=\nlanguage=\neditor=\nhelpsite=\ntimezone=-3\n\n', '0000-00-00', 'M') ".
                "ON DUPLICATE KEY UPDATE name=name"
        );
        mysql_close($con);
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

    function testFallaModificaEcualizadorIdIncorrecto() {
        $json_msg = array("eq_id" => 0, "nombre" => "TEST1");

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
                $this->assertEqual("FAIL", $json_resp->status);
            }
        } catch (HttpException $ex) {

        }
    }

    function testRecuperaEcualizador() {
        $json_msg = array("eq_id" => 1);

        $params = array(
                'task' => 'eq.retrieveeq',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $this->r->addPostFields($params);

        try {
            $resp = $this->r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("SUCCESS", $json_resp->status);
            }
        } catch (HttpException $ex) {

        }
    }

    function testFallaRecuperaEcualizadorIdIncorrecto() {
        $json_msg = array("eq_id" => 0);

        $params = array(
                'task' => 'eq.retrieveeq',
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

    function testRecuperaEcualizadorUsuario() {
        $json_msg = array("user_id" => 63);

        $params = array(
                'task' => 'eq.retrieveUserEq',
                'option' => 'com_eqzonales',
                'params' => json_encode($json_msg)
        );

        $this->r->addPostFields($params);

        try {
            $resp = $this->r->send()->getBody();
            $this->assertNotNull($resp);
            $json_resp = json_decode($resp);
            if ($this->assertNotNull($json_resp)) {
                $this->assertEqual("SUCCESS", $json_resp->status);
            }
        } catch (HttpException $ex) {

        }
    }

    function testFallaRecuperaEcualizadorUsuarioOtroUsuario() {
        $json_msg = array("user_id" => 64);

        $params = array(
                'task' => 'eq.retrieveUserEq',
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

    function testFallaRecuperaEcualizadorUsuarioNoExistente() {
        $json_msg = array("user_id" => -99);

        $params = array(
                'task' => 'eq.retrieveUserEq',
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

    function testRecuperaEcualizadorUsuarioAnonimo() {
        $json_msg = array("user_id" => 0);

        $params = array(
                'task' => 'eq.retrieveUserEq',
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
}
