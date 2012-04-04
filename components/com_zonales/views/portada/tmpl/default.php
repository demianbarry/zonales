<script language="javascript" type="text/javascript">
    <!--
    //sources.push("Facebook", "Twitter");
    //tags = new Array();
    //tab ="portada";
    
    -->
    if(zcGetTab() == '')
       zcSetTab('enlared');

</script>
<div id="postsDiv">
    <div>
        <label id= "tituloZone1"></label>
    </div>
    <div>
        <label id= "tituloZone2"></label>
    </div>
    <label id= "titulo1"></label><label id="tituloSup"></label>
    <div>
        <label id= "tituloFiltro"></label>
        <label id= "filtrosAct"></label>
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
