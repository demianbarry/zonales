<!--


-->

</script>
<!-- form -->
<div class="moduletable_formEq">    <!--//en este div.-->
    <h1 id="title_eq"><?php echo "Filtros "; ?></h1>
    <div id="mod_eq_main_div" class="moduletable_formEq_bodyDiv">
        <p><?php echo "Filtro usado para filtar
las fuentes de informacion mostradas"; ?></p>

        <div class="splitter"></div>

      
       
        <!--
            if ($pestana == 'enlared' || $pestana == 'relevantes') {
                $enLaRed = "inline";
                $noticiasEnLaRed = "none";
                if ($pestana == 'enlared') {
                    $tempoDiv = "none";
                } else {
                    $tempoDiv = "inline";
                }
            }

            if ($pestana == 'noticiasenlared' || $pestana == 'noticiasenlaredrelevantes') {
                $enLaRed = "none";
                $noticiasEnLaRed = "inline";
                if ($pestana == 'noticiasenlared') {
                    $tempoDiv = "none";
                } else {
                    $tempoDiv = "inline";
                }
            }
            if ($pestana == 'zonales') {
                $enLaRed = "none";
                $noticiasEnLaRed = "none";
                $tempoDiv = "none";
            }
        ?>-->
            <div id="filtersDiv">
                <p class="titulos">FUENTES</p>
                <table id="enLaRed" style="display: <?php echo $enLaRed ?>">

            </table>
            <table id="noticiasEnLaRed" style="display: <?php echo $noticiasEnLaRed ?>">

            </table>
            </div>
        
            
            <div id="tempoDiv" style="display: <?php echo $tempoDiv ?>">
            <div class="splitter"></div>
                <p class="titulos">TEMPORALIDAD</p>
                <table id="temporalidad" style="display: <?php echo $temporalidad ?>">
                <tr>
                    <td>
                        <select id="tempoSelect" class="tempoclass">
                            <option value="24HOURS">Hoy</option>
                            <option value="7DAYS">Ultima Semana</option>
                            <option value="30DAYS">Ultimo Mes</option>
                            <option value="0">Historico</option>
                        </select>
                    </td>
                </tr>
            </table>
            </div>

            <div class="splitter"></div>
        <div id="filterTags">
            <p class="titulos">TAGS</p>
            <table id="tagsFilterTable">

            </table>
        </div>

    </div>

</div>
<!-- end #moduletable_formVecinos -->
<!-- form -->
