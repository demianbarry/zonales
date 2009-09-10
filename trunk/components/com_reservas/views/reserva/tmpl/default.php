<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die ( 'Restricted Access' );

// incluye javascript para objeto flash
JHTML::script('dtfx.js');
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
<script>
    javafx(
    {
        archive: "<?php echo $this->template; ?>/javafx/<?php echo $this->javafxfile; ?>",
        draggable: <?php echo $this->draggable; ?>,
        width: <?php echo $this->width; ?>,
        height: <?php echo $this->height; ?>,
        code: "<?php echo $this->code; ?>",
        name: "<?php echo $this->name; ?>",
        id: "<?php echo $this->code; ?>"
    }
);
</script>