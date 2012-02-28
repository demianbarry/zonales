<script language="javascript" type="text/javascript">
<!--
//sources.push("Facebook", "Twitter");
//tags = new Array();
//tab ="portada";

-->

</script>
<div id="postsDiv">
    <label id= "titulo1"></label><label id="tituloSup"></label>
    <div>
    <label id= "tituloZone"></label>
    </div>
    <input id="verNuevos" value="" onclick="verNuevos();" type="button" style="display:none">
    <div id="newPostsContainer" style="display:none">
    </div>
    <div id="postsContainer">
    </div>
    <div>
        <input id="verMas" value="Ver más" onclick="loadMorePost();" type="button">
    </div>
</div>
<div id="mapDiv">
    <img id="ajaxLoader" src="/images/ajax_loader_bar.gif" style="display: inline"/>
    <label>Ud. está cerca de: </label><label id="cercaDe">Argentina</label>
    <div id='map_element' style="width: 96%; height: 650px; border: solid 9px #F1F2F3;"></div>
</div>