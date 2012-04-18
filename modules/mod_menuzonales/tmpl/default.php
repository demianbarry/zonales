<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
?>

<script type="text/javascript" defer="defer">
    function setActiveTab() {
        if(zTab.zCtx.zcGetOrder() == 'relevantes') {
            $('relevantesOrder').addClass('active');
            $('recientesOrder').removeClass('active');
        } else {
            $('recientesOrder').addClass('active');
            $('relevantesOrder').removeClass('active');
        }   
        if(zTab.zCtx.zcGetZTabs().indexOf('enlared') != -1) {
            $('enlared_anchor').addClass('active');            
        } else {
            $('enlared_anchor').removeClass('active');
        }
        if(zTab.zCtx.zcGetZTabs().indexOf('noticias') != -1) {
            $('noticias_anchor').addClass('active');
        } else {
            $('noticias_anchor').removeClass('active');
        }
    }
    
    window.addEvent('domready', function(){
        $('relevantesOrder').addEvent('click', function(){
            zTab.zCtx.zcSetOrder('relevantes', setActiveTab);
        });
        $('recientesOrder').addEvent('click', function(){
            zTab.zCtx.zcSetOrder('reciente', setActiveTab);
            setActiveTab();
        });        
        $('enlared_anchor').addEvent('click', function(){
            if($('enlared_anchor').hasClass('active'))
                zTab.zCtx.zcRemoveTab('enlared');            
            else 
                zTab.zCtx.zcAddTab('enlared');
            setActiveTab();
        });
        $('noticias_anchor').addEvent('click', function(){
            if($('noticias_anchor').hasClass('active'))
                zTab.zCtx.zcRemoveTab('noticias');
            else 
                zTab.zCtx.zcAddTab('noticias');
            setActiveTab();
        });        
    });
</script>

<div>
    <div id="mainMenu"><p><a style="display: none" id="portada_anchor">Portada</a><a id="enlared_anchor">En la red</a> | <a id="noticias_anchor">Noticas</a></p></div>
    <div id="ordenar"><p>Ordenar por <img src="templates/<?php echo $template; ?>/img/arrow_right.gif" /> <a id="relevantesOrder">Más relevantes</a> | <a id="recientesOrder">Más recientes</a></p></div>
</div>
