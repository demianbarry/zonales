<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );

// incluye javascript para objeto flash
JHTML::script('dtfx.js');
JHTML::script('mootools.js');
?>

<!--<div id="map_content">
Este DIV es reemplazado por el mapa flash utilizando el js SWFObject<br/>
Suele utilizarse este espacio para:
<ul>
<li>Avisar que aquí se cargará un mapa por ejemplo</li>
<li>Ofrecer una forma de instalar Flash Player</li>
<li>Ofrecer otra forma de elegir un zonal (por ejemplo, link a una página con un select)</li>
</ul>
</div>-->

<script type="text/javascript">
    function verReserva(mesa) {
        window.addEvent('domready', function() {
            var app = document.getElementById("JavaFXJavaScript");
            /*
            var req = new Request.HTML({url:'transport.php?action=' +
                    escape('http://localhost:8080/pruebasJava/Main')+
                    '&method=post'+"&mesa="+mesa,
                onSuccess: function(html) {
                    app.script.showMessage(html.item(0).innerHTML);
                },
                onFailure: function() {
                    app.script.showMessage("Falló el requerimiento.");
                }
            });

            req.send();*/
            app.script.showMessage("Falló el requerimiento.");
        });
    }
</script>
<script>
    javafx(
    {
        archive: "templates/<?php echo $this->template; ?>/javafx/<?php echo $this->javafxfile; ?>",
        draggable: <?php echo $this->draggable; ?>,
        width: <?php echo $this->width; ?>,
        height: <?php echo $this->height; ?>,
        code: "<?php echo $this->code; ?>",
        name: "<?php echo $this->name; ?>",
        id: "<?php echo $this->id; ?>"
    }
);
</script>