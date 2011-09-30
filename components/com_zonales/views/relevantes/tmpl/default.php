<script src="/media/system/js/relevantes.js" language="javascript" type="text/javascript"/>
<script language="javascript" type="text/javascript">
    <!--


    window.addEvent('domready', function() {
        setInterval(function () {
            loadPost();
        }, 10000);
        loadPost();
    });
    //-->
</script>
<table>
    <tbody id="chkFilter">
        <tr>
            <td>
                <input type="checkbox" id="chkFacebook" checked="true" value="Facebook" onclick="filtrar(this.value, this.checked);">
            </td>
            <td>
						Facebook
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" id="chkTwitter" checked="true" value="Twitter" onclick="filtrar(this.value, this.checked);">
            </td>
            <td>
						Twitter
            </td>
        </tr>
        <tr>
    <select id="tempoSelect" class="tempoclass" onchange="$('postsContainer').empty(); loadPost();">
        <option value="24HOURS">Hoy</option>
        <option value="7DAYS">Ultima Semana</option>
        <option value="30DAYS">Ultimo Mes</option>
        <option value="0">Historico</option>
    </select>
</tr>
</tbody>
</table>
<div id="postsContainer">
</div>
<div>
    <input value="Ver Mas" onclick="loadMorePost();" type="button">
</div>