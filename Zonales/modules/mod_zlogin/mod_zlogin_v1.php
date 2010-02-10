<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script type="text/javascript">
    function showElement(id) {
                document.getElementById('openid').style.display = 'none';
                document.getElementById('zonales').style.display = 'none';
		document.getElementById(id).style.display = 'block';
            }
    <!--
    Window.onDomReady(function(){
        document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
    });
    // -->
</script>


<ul>
    <?php foreach ($this->providerslist as $provider): ?>
    <li>
        <a href=<?php if ($provider->providername == 'Zonales' || $provider->providername == 'OpenID') {
            echo '"#" onClick="showElement(\''. strtolower($provider->providername) .'\')"';
        }
        else {
            $url = 'index.php?option=com_user&task=login&provider=' .
                urlencode($provider->providername) . '&' . JUtility::getToken() .'=1';
            echo '"' . $url . '"';
        }
           ?>>
            <img src="<?php echo 'images'.DS.$provider->icon_url ?>"
                 alt="<?php echo $provider->providername ?>"
                 title="Ingrese a Zonales mediante <?php echo $provider->providername ?>"
                 />
        </a>
    </li>
    <?php endforeach ?>
</ul>

