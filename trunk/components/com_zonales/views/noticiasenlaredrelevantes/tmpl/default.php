<link rel="stylesheet" href="/media/system/css/ZoneStyle.css" type="text/css"/>
<script language="javascript" type="text/javascript" src="components/com_zonales/utils.js"></script>
<script language="javascript" type="text/javascript" src="components/com_zonales/vistas.js"></script>
<script language="javascript" type="text/javascript">
<!--
//sources.push("Facebook", "Twitter");
//tags = new Array();
zones.push(<?php echo strlen($this->zonal_id) > 0 ? "'$this->zonal_id'" : "";  ?>);
tab = "noticiasenlaredrelevantes";
window.addEvent('domready', function() {
    setInterval(function () {
        loadPost(false);
    }, 60000);
    loadPost(true);
});
-->
</script>
<label id="titulo1"></label><label id="tituloSup"></label>
<input id="verNuevos" value="" onclick="verNuevos();" type="button" style="display:none">
<div id="newPostsContainer" style="display:none">
</div>
<div id="postsContainer">
</div>
<div>
    <input value="Ver Mas" onclick="loadMorePost();" type="button">
</div>

