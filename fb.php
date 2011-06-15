<?php
include_once "fbmain.php";
$config['baseurl'] = "http://200.69.225.53:30080/facebook-test/index.php";

//if user is logged in and session is valid.
if ($fbme) {

}


//Retriving movies those are user like using graph api
try {
    $user = $_GET('users');
    $api = '/'.$user.'/feed';
    $feeds = $facebook->api($api);
} catch (Exception $o) {
    d($o);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>PHP SDK & Graph API base FBConnect Tutorial | Thinkdiff.net</title>
    </head>
    <body>
        <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?= $fbconfig['appid'] ?>', status: true, cookie: true, xfbml: true});
 
                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
 
            function login(){
                document.location.href = "<?= $config['baseurl'] ?>";
            }
            function logout(){
                document.location.href = "<?= $config['baseurl'] ?>";
            }
        </script>
    <style type="text/css">
        .box{
            margin: 5px;
            border: 1px solid #60729b;
            padding: 5px;
            width: 500px;
            height: 200px;
            overflow:auto;
            background-color: #e6ebf8;
        }
    </style>

<?php showArrays($feeds); ?>

</body>
</html>
