
<script language="javascript" type="text/javascript">
<!--



    var urlSolr = '/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=20&qt=zonalesContent&sort=max(modified,created)+desc&wt=json&explainOther=&hl.fl='+(lastIndexTime ? '&fq=indexTime:['+getSolrDate(lastIndexTime + 10800001)+'+TO+*]' : '')+ "&q=source:(Zonales)"+<?php echo strlen($this->zonal_id) > 0 ? "'+AND+zone:$this->zonal_id'" : "''"; ?>;

    var urlSolrLoadMore = '/solr/select?indent=on&version=2.2&start=0&fl=*%2Cscore&rows=20&qt=zonalesContent&sort=max(modified,created)+desc&wt=json&explainOther=&hl.fl=&fq=indexTime:[*+TO+'+reduceMilli(firstIndexTime)+']' + "&q=source:(Zonales)"+<?php echo strlen($this->zonal_id) > 0 ? "'+AND+zone:$this->zonal_id'" : "''"; ?>;
-->
</script>
<script language="javascript" type="text/javascript" src="components/com_zonales/vistas.js"></script>
<label>Ud. esta viendo Noticias de la red: </label><label id="tituloSup"></label>
<input id="verNuevos" value="" onclick="verNuevos();" type="button" style="display:none">
<div id="newPostsContainer" style="display:none">
</div>
<div id="postsContainer">
</div>
<div>
    <input value="Ver Mas" onclick="loadMorePost();" type="button">
</div>