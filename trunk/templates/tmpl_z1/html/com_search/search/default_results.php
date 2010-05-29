<?php defined('_JEXEC') or die('Restricted access'); ?>

<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
	<tr>
		<td>
		<?php
		foreach( $this->results as $result ) : ?>
                    <table class="contentpaneopen">
                        <tbody>
                            <tr>
                                <td width="100%" class="contentheading">
					<?php echo $this->escape($result->title); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="contentpaneopen">
                        <tbody>
                            <tr>
                                <td width="70%" valign="top" colspan="2">
                                    <span class="samll"><?php echo JText::_('Section').': '.$result->section; ?> </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="createdate" valign="top" colspan="2"> <?php echo JText::_('Created').': '.$result->created; ?> </td>
                            </tr>
                            <tr>
                                <td valign="top" colspan="2">
                                    <p> <?php echo $result->text; ?> </p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" colspan="2">
                                    <a class="readon" href="<?php echo $result->href; ?>"> <?php echo JText::_('Read More'); ?> </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span class="article_separator"> &nbsp; </span>
                <?php endforeach; ?>
                </td>
	</tr>
	<tr>
		<td colspan="3">
			<div align="center">
				<?php echo $this->pagination->getPagesLinks( ); ?>
			</div>
		</td>
	</tr>
</table>


 <!--
			<fieldset>
				<div>
					<span class="small<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
						<?php echo $this->pagination->limitstart + $result->count.'. ';?>
					</span>
					<?php if ( $result->href ) :
						if ($result->browsernav == 1 ) : ?>
							<a href="<?php echo JRoute::_($result->href); ?>" target="_blank">
						<?php else : ?>
							<a href="<?php echo JRoute::_($result->href); ?>">
						<?php endif;

						echo $this->escape($result->title);

						if ( $result->href ) : ?>
							</a>
						<?php endif;
						if ( $result->section ) : ?>
							<br />
							<span class="small<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
								(<?php echo $this->escape($result->section); ?>)
							</span>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div>
					<?php echo $result->text; ?>
				</div>
				<?php
					if ( $this->params->get( 'show_date' )) : ?>
				<div class="small<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
					<?php echo $result->created; ?>
				</div>
				<?php endif; ?>
			</fieldset>
                    -->