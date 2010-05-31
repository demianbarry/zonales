<?php
defined('_JEXEC') or die('Restricted access');

$this->pagination =& $this->get('Pagination');

?>

<table class="contentpaneopen<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
    <?php
    $k = 0; $i = 0;
    foreach( $this->results as $result ) : ?>
	<tr class="<?php echo "row$k"; ?>">
		<td>
                    <table class="contentpaneopen">
                        <tbody>
                            <tr>
                                <td width="100%" class="contentheading">
					<?php echo $result->title; ?>
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
                </td>
	</tr>
        <?php
        $k = 1 - $k;
        $i++;
        endforeach; ?>
	<tr>
		<td colspan="7">
			<div align="center">
				<?php echo $this->pagination->getPagesLinks( ); ?>
			</div>
		</td>
	</tr>
</table>
