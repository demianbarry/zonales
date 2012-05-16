<?php

/* * ************** Librerías ************** */
include_once "fbmain.php";
include_once "FormTools.php";
include_once "arrayToXML.php";
include_once "stemmer-es/stemm_es.php";

/* * ************** Configuraciones ************** */
//$config['baseurl'] = "http://200.69.225.53:30080/facebook-test/index.php";

log_action("REQUEST: ");

//Límite de post a recuperar por defecto
define("_DEFAULT_LIMIT_", 20);
define("_DEFAULT_FORMAT_", "json");
define("_DEFAULT_MIN_ACTIONS_", 0);
define("_COMMENTS_OFFSET_", 50);


/* * ************** Extracción de parámetros ************** */
$usersStr = FormTools::getParameter('users');
$limit = FormTools::getParameter('limit') === false ? _DEFAULT_LIMIT_ : FormTools::getParameter('limit');
$since = FormTools::getParameter('since');
$format = FormTools::getParameter('format') === false ? _DEFAULT_FORMAT_ : strtolower(FormTools::getParameter('format'));
$zone = FormTools::getParameter('zone');
$keywordsStr = FormTools::getParameter('keywords');
$tagsStr = FormTools::getParameter('tags');
$commentersStr = FormTools::getParameter('commenters');
$minActions = FormTools::getParameter('minactions') === false ? _DEFAULT_MIN_ACTIONS_ : FormTools::getParameter('minactions');
$userName = FormTools::getParameter('getId');
$getCommenters = FormTools::getParameter('getCommenters');
$getComments = FormTools::getParameter('getComments') === false ? false : true;
$ThirdPartyPosts = FormTools::getParameter('getThirdPartyPosts') === false ? false : true;


/* * ************** Procesamiento de parámetros ************** */
//Formato de respuesta
switch ($format) {
    case "json":
        header('Content-Type: text/javascript; charset=utf-8');
        break;
    case "xml":
        header("Content-Type: application/xml; charset=utf-8");
        break;
    default:
        header("Content-Type: text/plain; charset=utf-8");
}

$since = substr($since, 0, strlen($since) - 3);

$users = false;
$usersLat = false;
$usersLon = false;

//Procesamiento de usuarios
if ($usersStr) {
    $users = array();
    $extendedStrings = array();

    $tok = strtok($usersStr, ";");


    if (strpos($tok, "[") > 0) {
        $user = substr($tok, 0, strpos($tok, "["));
        $extendedString = substr($tok, strpos($tok, "[") + 1, strpos($tok, "]") - strpos($tok, "[") - 1);
    } else {
        $user = $tok;
        $extendedString = "";
    }

    $users[] = $user;
    $extendedStrings[] = $extendedString != "" ? $extendedString : "";

    while ($tok !== false) {
        $tok = strtok(",");

        if ($tok !== false) {
            if (strpos($tok, "[") > 0) {
                $user = substr($tok, 0, strpos($tok, "["));
                $extendedString = substr($tok, strpos($tok, "[") + 1, strpos($tok, "]") - strpos($tok, "[") - 1);
                /* $lat = substr($tok, strpos($tok, "[") + 1, strpos($tok, ";") - strpos($tok, "[") - 1);
                  $lon = substr($tok, strpos($tok, ";") + 1, strpos($tok, "]") - strpos($tok, ";") - 1); */
            } else {
                $user = $tok;
                $extendedString = "";
                /* $lat = "";
                  $lon = ""; */
            }

            $users[] = $user;
            $extendedStrings[] = $extendedString != "" ? $extendedString : "";
            /* $usersLat[] = $lat != "" ? $lat : "";
              $usersLon[] = $lon != "" ? $lon : ""; */
        }
    }
}


//Procesamiento de keywords
if ($keywordsStr) {
    $keywords = array();
    $ktok = strtok($keywordsStr, ",");
    $keywords[] = $ktok;

    while ($ktok !== false) {
        $ktok = strtok(",");
        if ($ktok !== false) {
            $keywords[] = $ktok;
        }
    }
} else {
    $keywords = null;
}

//Procesamiento de tags
if ($tagsStr) {
    $tags = array();
    $ttok = strtok($tagsStr, ",");
    $tags[] = $ttok;

    while ($ttok !== false) {
        $ttok = strtok(",");
        if ($ttok !== false) {
            $tags[] = $ttok;
        }
    }
}

//Procesamiento de commenters
if ($commentersStr) {
    $commenters = array();
    $ctok = strtok($commentersStr, ",");
    $commenters[] = $ctok;

    while ($ctok !== false) {
        $ctok = strtok(",");
        if ($ctok !== false) {
            $commenters[] = $ctok;
        }
    }
} else {
    $commenters = null;
}

/*
  $filename = "since.txt";

  $handle = fopen($filename, 'r');
  $since = fgets($handle);
  fclose($handle);
  $max = $since;
 *
 */
//$min = $since * 2;  //Ver como arreglo esto después
//$stop = false;
//$loops = 1;

/* * ************** Extracción ************** */
try {
    if ($userName) {
        $api = '/' . $userName;
        $userData = $facebook->api($api);
        echo $userData['id'];
        return;
    }

    $posts = array();
    $retCommenters = array();
    $retCommentersInc = array();
    $retCommentersFinal = array();

    //Si se especifican usuarios
    if ($usersStr) {
        //Traigo el los post del muro de cada uno de los usuarios utilizando la API Graph de Facebook
        foreach ($users as $key => $user) {

            /* Llamado a la API */

            /* Obtengo datos del usuario */
//            $api = '/' . $user;
//            $userData = $facebook->api($api);

            $api = '/' . $user . '/feed?limit=' . $limit;

            if ($since) {
                $api = $api . '&since=' . $since; //($loops == 1 ? $since : ($min > $since ? $min : $since));
            }

            //$api = $api . '&access_token=' . $facebook->getAccessToken();

            $feeds = $facebook->api($api);
            /* Fin llamado a la API */

            //Por cada uno de los feed recuperados del muro del usuario, chequeo si son posteados por él mismo.
            //El resto de los posts, chequeo contra el valor de commenters: si se especificó "all" incluyo todos los posts, sino solo lo de los commenters especificados
            //Además chequeo los keywords en caso de que se especifiquen.
            //var_dump($feeds);
            //exit(0);
            foreach ($feeds['data'] as $feed) {
                $validPost = true;
                if ($getCommenters) {
                    if ($feed['from']['id'] != $user) {
                        if (!array_key_exists($feed['from']['id'], $retCommentersInc)) {
                            $addCommenter = array();
                            $addCommenter['id'] = $feed['from']['id'];
                            $addCommenter['name'] = $feed['from']['name'];
                            $addCommenter['url'] = "http://www.facebook.com/profile.php?id=" . $feed['from']['id'];
                            $retCommenters[] = $addCommenter;
                            $retCommentersInc[$feed['from']['id']] = 1;
                        } else {
                            $retCommentersInc[$feed['from']['id']] = $retCommentersInc[$feed['from']['id']] + 1;
                        }
                    }
                }
                if (!$ThirdPartyPosts) {
                    if ($feed['from']['id'] != $user) {
                        $validPost = false;
                    }
                }
                
                if ($commenters != null) {
                    $isCommenter = false;
                    foreach ($commenters as $commenter) {
                        if ($feed['from']['id'] == $commenter) {
                            $isCommenter = true;
                        }
                    }
                    $validPost = $isCommenter;
                }
                 
                if ($validPost) {
                    if (checkActions($feed, $minActions)) {
                        if (checkKeywords($feed, $keywords)) {
                            $posts[] = processFeed($feed, $zone, $tags, $usersLat[$key], $usersLon[$key], $extendedStrings[$key]);
                            if ($getComments) {
                                $comments = processComments($feed);
                                foreach ($comments as $comment) {
                                    $validComment = true;
                                    
                                    if ($commenters != null) {
                                        $validComment = false;
                                        foreach ($commenters as $commenter) {
                                            if ($comment['fromUser']['id'] == $commenter) {
                                                $validComment = true;
                                            }
                                        }
                                    }
                                    
                                    if ($validComment)
                                        $posts[] = $comment;
                                }
                            }
                        }
                    }
                }
            }
        }
        //Si no se especifican usuarios, busco por keywords utilizano la API e Facebook
    } else {
        $searchList = getSearchList($keywords);

        //Si existen keywords a buscar
        if (!empty($searchList)) {

            /* Llamado a la API */
            $api = '/search?q=';

            foreach ($searchList as $keyword) {
                $api .= $keyword . '%20';
            }

            $api .= '&type=post&init=srp';

            if ($limit) {
                $api .= '&limit=' . $limit;
            }

            if ($since) {
                $api .= '&since=' . $since; //($loops == 1 ? $since : ($min > $since ? $min : $since));
            }

            //$api = $api . '&access_token=' . $facebook->getAccessToken();

            $feeds = $facebook->api($api);
            /* Fin Llamado a la API */

            //Proceso cada post recuperado
            foreach ($feeds['data'] as $feed) {
                if (checkActions($feed, $minActions)) {
                    //if (checkKeywords($feed, $keywords)) {
                    if ($getCommenters) {
                        if ($feed['from']['id'] != $user) {
                            if (!array_key_exists($feed['from']['id'], $retCommentersInc)) {
                                $addCommenter = array();
                                $addCommenter['id'] = $feed['from']['id'];
                                $addCommenter['name'] = $feed['from']['name'];
                                $addCommenter['url'] = "http://www.facebook.com/profile.php?id=" . $feed['from']['id'];
                                $retCommenters[] = $addCommenter;
                                $retCommentersInc[$feed['from']['id']] = 1;
                            } else {
                                $retCommentersInc[$feed['from']['id']] = $retCommentersInc[$feed['from']['id']] + 1;
                            }
                        }
                    }
                    $posts[] = processFeed($feed, $zone, $tags);
                    //}
                }
            }
        }
    }

    foreach ($retCommenters as $retCommenter) {
        $addCommenter = array();
        foreach ($retCommenter as $key => $value) {
            $addCommenter[$key] = $value;
        }
        $addCommenter['cant'] = $retCommentersInc[$retCommenter['id']];
        $retCommentersFinal[] = $addCommenter;
    }

    /*
      $handle = fopen($filename, 'w');
      fwrite($handle, $max);
      fclose($handle);
     *
     */
} catch (FacebookApiException $ex) {
    header('Content-Type: text/javascript; charset=utf-8');
    $result = $ex->getResult();
    echo '{"cod": 401, "msg": "' . $result['error']['message'] . '"}';
    return;
} catch (Exception $ex) {
    header('Content-Type: text/javascript; charset=utf-8');
    echo '{"cod": 400, "msg": "' . $result['error']['message'] . '"}';
    return;
}

/* * ****************** Respuesta **************** */
if ($getCommenters) {
    switch ($format) {
        case "json":
            echo '{"commenters":' . indent(json_encode($retCommentersFinal)) . "}";
            break;
        case "xml":
            $xml = new ArrayToXML();
            $xmlElement = new SimpleXMLElement($xml->toXml($retCommentersFinal, "commenters"));
            echo $xmlElement->asXML();
            break;
        //Si se especifica otro formato, por el momento simplemente imprimo el array como está...
        default:
            print_r($retCommentersFinal);
    }
} else {
    switch ($format) {
        //Convierto el array de posts en JSON
        case "json":
            log_action("RESPONSE: " . '{"post":' . indent(json_encode($posts)) . "}");
            echo '{"post":' . indent(json_encode($posts)) . "}";
            break;
        //Convierto el array de posts en XML, utilizando la librería
        case "xml":
            $xml = new ArrayToXML();
            $xmlElement = new SimpleXMLElement($xml->toXml($posts, "posts"));
            $xmlElement->addAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
            $xmlElement->addAttribute("xsi:noNamespaceSchemaLocation", "http://200.69.225.53:30082/XZone.xsd");
            echo $xmlElement->asXML();
            //echo $xml->toXml($posts, "posts");
            break;
        //Si se especifica otro formato, por el momento simplemente imprimo el array como está...
        default:
            print_r($posts);
    }
}

/* * ****************** Procesamiento de feeds **************** */

function processFeed($feed, $zone = null, $tags = null, $lat = null, $lon = null, $extendedString = null) {
    global $format, $facebook, $getComments;  //$max

    $post = array();
    $post['docType'] = 'post';
    $post['source'] = 'Facebook';
    $post['id'] = $feed['id'];
    if (isset($feed['from'])) {
        $post['fromUser'] = $feed['from'];
        $post['fromUser']['url'] = "http://www.facebook.com/profile.php?id=" . $feed['from']['id'];
        if ($lat != null) {
            $post['fromUser']['latitude'] = $lat;
        }
        if ($lon != null) {
            $post['fromUser']['longitude'] = $lon;
        }
    }

    if ($extendedString != null) {
        $post['extendedString'] = $extendedString;
    }

    if (isset($feed['to'])) {
        $post['toUsers'] = array();
        foreach ($feed['to']['data'] as $to) {
            $tos = array();
            $tos['name'] = $to['name'];
            $tos['category'] = $to['category'];
            $tos['id'] = $to['id'];
            $tos['url'] = "http://www.facebook.com/profile.php?id=" . $to['id'];
            $post['toUsers'][] = $tos;
        }
    }
    if (isset($feed['name'])) {
        $post['title'] = $feed['name'];
    } else {
        $str = substr($feed['message'], 0, 50);
        $pos = strripos($str, " ");
        $str = substr($str, 0, $pos) . "...";
        $post['title'] = $str;
    }
    if (isset($feed['message'])) {
        $post['text'] = $feed['message'];
    } else {
        $post['text'] = $feed['name'];
    }
    $post['links'] = array();
    switch ($feed['type']) {
        case 'photo':
        case 'picture':
            $link = array();
            $link['type'] = "picture";
            $link['url'] = $feed['picture'];
            $post['links'][] = $link;
        case 'video':
        case 'link':
            $link = array();
            $link['type'] = $feed['type'];
            $link['url'] = $feed['link'];
            $post['links'][] = $link;
            break;
    }
    if (isset($feed['from'])) {
        $link = array();
        $link['type'] = "avatar";
        $link['url'] = 'https://graph.facebook.com/' . $feed['from']['id'] . '/picture';
        $post['links'][] = $link;
    }
    $post['actions'] = array();
    if (isset($feed['likes'])) {
        $action = array();
        $action['type'] = "like";
        $action['cant'] = $feed['likes']['count'];
        $post['actions'][] = $action;
    }
    if (isset($feed['comments'])) {
        $action = array();
        $action['type'] = "comment";
        $action['cant'] = $feed['comments']['count'];
        $post['actions'][] = $action;
    }

    $post['created'] = strtotime($feed['created_time']) . "000";
    $post['modified'] = strtotime($feed['updated_time']) . "000";
    $post['relevance'] = ($feed['comments']['count'] * 3) + $feed['likes']['count'];

    if ($zone) {
        $zoneObj = array();
        $zoneObj['id'] = null;
        $zoneObj['name'] = null;
        $zoneObj['type'] = null;
        $zoneObj['extendedString'] = $zone;
        $post['zone'] = $zoneObj;
    }

    if ($tags) {
        foreach ($tags as $tag) {
            $post['tags'][] = $tag;
        }
    }   

    if ($format == "xml") {
        $post['verbatim'] = indent(json_encode($post));
    }
    return $post;
}

function processComments($feed) {
    global $format, $facebook;
    
    $posts = array();
    
    if ($feed['comments']['count'] > 0) {
        $limit = _COMMENTS_OFFSET_;
        $offset = 0;
        
        while ($offset <= $feed['comments']['count']) {
            $api = '/' . $feed['id'] . '/comments?limit=' . $limit . '&offset=' . $offset;
            $fbComments = $facebook->api($api);
            foreach ($fbComments['data'] as $fbComment) {
                $post = array();
                $post['docType'] = 'comment';
                $post['sourcePost'] = $feed['id'];
                $post['source'] = 'Facebook';
                $post['id'] = $fbComment['id'];
                $post['text'] = $fbComment['message'];
                $post['created'] = strtotime($fbComment['created_time']) . "000";
                $post['fromUser'] = $fbComment['from'];
                $post['fromUser']['url'] = "http://www.facebook.com/profile.php?id=" . $fbComment['from']['id'];
                $link = array();
                $link['type'] = "avatar";
                $link['url'] = 'https://graph.facebook.com/' . $fbComment['from']['id'] . '/picture';
                $post['links'][] = $link;
                if ($format == "xml") {
                    $post['verbatim'] = indent(json_encode($post));
                }
                $posts[] = $post;
            }
            $offset += $limit;
        }        
    }
    
    return $posts;
}

/* * ****************** Chequeo de keywords **************** */

function checkKeywords($feed, $keywords) {

    if ($keywords != null) {
    
        $searchList = getSearchList($keywords);
        $blackList = getBlackList($keywords);

        //Unifico en un solo string el título y el texto del post (Por el momento, podrían agregarse otros campos)
        $text = $feed['name'] . ' ' . $feed['message'];

        //Divido el string en palabras, utilizando como separadores los blancos (espacios, tabs, etc.), comas, puntos y puntos y coma (Puede que haya que agregar otros símbolos a la expresión regular)
        foreach (preg_split("/[\s,.;]+/", $text) as $word) {
            //Seteo un array de strings utilizando como índice la raiz de la palabra
            $string[stemm_es::stemm(strtolower($word))] = 1;
        }

        //Si no existen palabras en ninguna de las dos listas, no hay que filtran, por lo tanto retorno true
        if (empty($searchList) && empty($blackList)) {
            return true;
        }

        //Si existen palabras en la lista negra, pero no en la otra, solo filtro los post que contengan esas palabras
        if (empty($searchList) && !empty($blackList)) {
            //Chequeo si existen en el índice los keywords buscados (la raiz en realidad) y en ese caso retorno false para omitir el post
            foreach ($blackList as $keyword) {
                if (isset($string[stemm_es::stemm(strtolower($keyword))])) {
                    return false;
                }
            }
            return true;
        }

        //Si existen palabras en la lista de términos a buscar, solo retorno los post que contengan esas palabras, y de ellos chequeo que no tengan palabras de la lista negra.
        foreach ($searchList as $keyword) {
            if (!empty($blackList)) {
                foreach ($blackList as $blackKeyword) {
                    if (isset($string[stemm_es::stemm(strtolower($blackKeyword))])) {
                        return false;
                    }
                }
            }
            if (isset($string[stemm_es::stemm(strtolower($keyword))])) {
                return true;
            }
            return false;
        }
    } else {
        return true;
    }
}

function getSearchList($keywords) {
    $searchList = array();

    if ($keywords != null) {
        //Divido los keywords en searchList y blackList
        foreach ($keywords as $keyword) {
            if (substr($keyword, 0, 1) != "!") {
                $searchList[] = $keyword;
            }
        }
    }
    
    return $searchList;
}

function getBlackList($keywords) {
    $blackList = array();

    if ($keywords != null) {
        //Divido los keywords en searchList y blackList
        foreach ($keywords as $keyword) {
            if (substr($keyword, 0, 1) == "!") {
                $blackList[] = substr($keyword, 1);
            }
        }
    }
    
    return $blackList;
}

function checkActions($feed, $minActions) {
    $actions = 0;

    if (isset($feed['likes'])) {
        $actions += $feed['likes']['count'];
    }
    if (isset($feed['comments'])) {
        $actions += $feed['comments']['count'];
    }

    if ($minActions <= $actions) {
        return true;
    } else {
        return false;
    }
}

function log_action($msg) {
    $today = date("d.m.Y");
    $filename = "logs/$today.txt";
    $fd = fopen($filename, "a");
    $str = "[" . date("d/m/Y h:i:s", mktime()) . "] " . $msg;
    fwrite($fd, $str . PHP_EOL);
    fclose($fd);
}

/*
  header("Content-Type:text/plain; charset=utf-8");
  echo "Entre acá!!! " . $keyword;
  exit(0);
 */
?>
