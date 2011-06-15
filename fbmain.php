<?php

$fbconfig['appid'] = "195377683831047";
$fbconfig['api'] = "9278da5e1ad4636708295b026c14d8ce";
$fbconfig['secret'] = "1ad66f1d79477de016069d7ebc408e13";

try {
    include_once "facebook.php";
} catch (Exception $o) {
    showArrays($o);
}
// Create our Application instance.
$facebook = new Facebook(array(
            'appId' => $fbconfig['appid'],
            'secret' => $fbconfig['secret'],
            'cookie' => true,
        ));

// We may or may not have this data based on a $_GET or $_COOKIE based session.
// If we get a session here, it means we found a correctly signed session using
// the Application Secret only Facebook and the Application know. We dont know
// if it is still valid until we make an API call using the session. A session
// can become invalid if it has already expired (should not be getting the
// session back in this case) or if the user logged out of Facebook.
$session = $facebook->getSession();

$fbme = null;
// Session based graph API call.
if ($session) {
    try {
        $uid = $facebook->getUser();
        $fbme = $facebook->api('/me');
    } catch (FacebookApiException $e) {
        showArrays($e);
    }
}

function showArrays($d) {
    echo '<pre>';
    print_r($d);
    echo '</pre>';
}

/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 *
 * @return string Indented version of the original JSON string.
 */
function indent($json) {

    $result = '';
    $pos = 0;
    $strLen = strlen($json);
    $indentStr = '  ';
    $newLine = "\n";
    $prevChar = '';
    $outOfQuotes = true;

    for ($i = 0; $i <= $strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

            // If this character is the end of an element,
            // output a new line and indent the next line.
        } else if (($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}

?>
