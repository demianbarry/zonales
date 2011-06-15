<?php

include_once "fbmain.php";
include_once "FormTools.php";
include_once "arrayToXML.php";
$config['baseurl'] = "http://200.69.225.53:30080/facebook-test/index.php";

$usersStr = FormTools::getParameter('users');
$limit = FormTools::getParameter('limit') === false ? 50 : FormTools::getParameter('limit');
$since = FormTools::getParameter('since');
$format = FormTools::getParameter('format') === false ? "json" : strtolower(FormTools::getParameter('format'));
$zone = FormTools::getParameter('zone');

switch ($format) {
    case "json":
        header('Content-Type: application/x-javascript; charset=utf-8');
        break;
    case "xml":
        header("Content-Type:application/xml; charset=utf-8");
        break;
    default:
        header("Content-Type:text/plain; charset=utf-8");
}

$users = array();
$tok = strtok($usersStr, ",");
$users[] = $tok;

while ($tok !== false) {
    $tok = strtok(",");
    if ($tok !== false) {
        $users[] = $tok;
    }
}


/* Definición de usuarios de interes, por ahora Duros unicamente de Puerto Madryn
  $users = array("222136235762", "199219160505", "365539206306", "122487684489920", "369753984314", "144258372308382", "177481952271908", "90091503815");
  $filename = "since.txt";

  $handle = fopen($filename, 'r');
  $since = fgets($handle);
  $max = $since;
  fclose($handle);
 */

try {
    $posts = array();
    foreach ($users as $user) {

        $api = '/' . $user . '/feed?limit=' . $limit;
        if ($since) {
            $api = $api . '&since=' . $since;
        }

        $feeds = $facebook->api($api);

        foreach ($feeds['data'] as $feed) {
            $post = array();
            $post['source'] = 'Facebook';
            $post['id'] = $feed['id'];
            if (isset($feed['from'])) {
                $post['fromUser'] = $feed['from'];
                $post['fromUser']['url'] = "http://www.facebook.com/profile.php?id=" . $feed['from']['id'];
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
            $post['created'] = $feed['created_time'];
            $post['modified'] = $feed['updated_time'];
            $post['relevance'] = ($feed['comments']['count'] * 3) + $feed['likes']['count'];
            if ($zone) {
                $post['tags'] = array();
                $post['tags'][] = $zone;
            }


            $modified = strtotime($post['modified']);
            if ($modified > $max) {
                $max = $modified;
            }

            $post['verbatim'] = indent(json_encode($post));
            $posts[] = $post;
        }
    }

    /* $ret = array();
      $ret['posts'] = $posts;

      $handle = fopen($filename, 'w');
      fwrite($handle, $max);
      fclose($handle); */
    switch ($format) {
        case "json":
            echo indent(json_encode($posts));
            break;
        case "xml":
            $xml = new ArrayToXML();
            $xmlElement = new SimpleXMLElement($xml->toXml($posts, "posts"));
            $xmlElement->addAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
            $xmlElement->addAttribute("xsi:noNamespaceSchemaLocation", "http://200.69.225.53:30082/XZone.xsd");
            echo $xmlElement->asXML();
            //echo $xml->toXml($posts, "posts");
            break;
        default:
            print_r($posts);
    }
} catch (Exception $o) {
    showArrays($o);
}
?>
