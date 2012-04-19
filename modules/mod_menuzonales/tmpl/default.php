<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted Access');
?>

<script type="text/javascript" defer="defer">

    function setActiveTab() {
        if(zTab.zCtx.zcGetOrder() == 'relevantes') {
            $('relevantesOrder').addClass('active');
            $('recientesOrder').removeClass('active');
            $('showTemp').set('src','/media/system/images/arrow_down1.png');
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
            $('showTemp').morph({'display':'inline'});
        });
        $('recientesOrder').addEvent('click', function(){
            zTab.zCtx.zcSetOrder('reciente', setActiveTab);
            $('showTemp').morph({'display':'none'});
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
        
        $('showTemp').addEvent('click', function(){
            if($('relevantesOrder').hasClass('active')){
                if(!$('tempoSelect')){
                    var container = new Element('span', {
                        'id':'tempoSelect',
                        'class': 'suggestions'
                    }).inject($('showTemp'),'after');
                    new Element('p', {
                        'html': 'Hoy'                    
                    }).inject(container).addClass(zTab.zCtx.zcGetTemp() == '24HOURS' ? 'selected' : '').addEvent('click', function(){
                        changeTemp(this, '24HOURS');                        
                    });
                    new Element('p', {
                        'html': 'Última semana'
                    }).inject(container).addClass(zTab.zCtx.zcGetTemp() == '7DAYS' ? 'selected' : '').addEvent('click', function(){
                        changeTemp(this, '7DAYS');                        
                    });
                    new Element('p', {
                        'html': 'Último mes'
                    }).inject(container).addClass(zTab.zCtx.zcGetTemp() == '30DAYS' ? 'selected' : '').addEvent('click', function(){
                        changeTemp(this, '30DAYS');
                    });
                    new Element('p', {
                        'html': 'Histórico'
                    }).inject(container).addClass(zTab.zCtx.zcGetTemp() == '0' ? 'selected' : '').addEvent('click', function(){
                        changeTemp(this, '0');
                    });
                    $('showTemp').set('src','/media/system/images/arrow_up1.png');
                } else {
                    $('tempoSelect').dispose();
                    $('showTemp').set('src','/media/system/images/arrow_down1.png');
                }
            }
        });        
    });
    
    function changeTemp(element, temp){
        zTab.zCtx.zcSetTemp(temp);        
        $('tempoSelect').dispose();
        $('showTemp').set('src','/media/system/images/arrow_down1.png');
    }
</script>

<div>
    <div id="mainMenu"><p><a style="display: none" id="portada_anchor">Portada</a><a id="enlared_anchor">En la red</a> | <a id="noticias_anchor">Noticas</a></p></div>
    <div id="ordenar">
        <p>Ordenar por <img src="templates/<?php echo $template; ?>/img/arrow_right.gif" /><a id="relevantesOrder">Mas relevantes</a><img id="showTemp" class="down"> | <a id="recientesOrder">Más recientes</a></p></div>
</div>
