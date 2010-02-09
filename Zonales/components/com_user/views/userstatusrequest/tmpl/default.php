<p>No se ha encontrado el identificador con el que se ha autenticado.</p>
<p>Por favor diganos cual es su situación</p>
<p></p>
<a href="<?php echo 'index.php?option=com_user&view=login&externalid='.urlencode($this->externalid). '&providerid=' . $this->providerid.'&' . JUtility::getToken() .'=1'?>">Ya soy usuario de Zonales</a>
<p></p>
<a href="<?php echo 'index.php?option=com_user&view=register&externalid='.urlencode($this->externalid).'&providerid='.$this->providerid.'&' . JUtility::getToken() .'=1'?>">No soy usuario. Deseo registrarme</a>