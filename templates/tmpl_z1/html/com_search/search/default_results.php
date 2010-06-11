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
					<a href="<?php echo $result->href; ?>">
                                            <?php echo $result->title; ?>
                                        </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="contentpaneopen">
                        <tbody>
                            <tr>
                                <td class="createdate" valign="top" colspan="2"> <?php echo $result->created; ?> </td>
                            </tr>
                            <tr>
                                <td valign="top" colspan="2">
                                    <p> <?php echo $result->introtext; ?> </p>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" colspan="2">
                                    <p> <?php echo $result->tags; ?> </p>
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
