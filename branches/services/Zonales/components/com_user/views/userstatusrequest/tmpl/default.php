<p><?php echo $this->aliasNotFoundMessage ?></p>
<p><?php echo $this->requestMessage ?></p>
<p></p>
<a href="<?php echo 'index.php?option=com_user&view=zlogin&externalid='.urlencode($this->externalid). '&providerid=' . $this->providerid.'&' . JUtility::getToken() .'=1'?>"><?php echo $this->userMessage ?></a>
<p></p>
<a href="<?php echo 'index.php?option=com_user&view=register&force=1&externalid='.urlencode($this->externalid).'&providerid='.$this->providerid.'&' . JUtility::getToken() .'=1'?>"><?php echo $this->notUserMessage ?></a>