<?php

?>
<?php # la clase generada indica si se trata de un mensaje de error o de exito
        # aliasmessage-error o aliasmessage-success
?>
<p class="<?php echo $this->class ?>">
    <?php echo $this->message ?>
</p>
<a class="backlink" 
   href="<?php echo $this->return ?>"
>
    <?php echo $this->returnmessage ?>
</a>