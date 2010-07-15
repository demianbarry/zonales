<?php
/**
 * @version		$Id: ckeditor.php 1.2 28-09-2009 Danijar
 * @package		CKEditor
 * @copyright	Copyright (C) 2009 CMSSpace. http://www.cmsspace.com
 * @license		GNU/GPL. http://www.gnu.org/licenses/gpl.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.event.plugin');
class plgEditorCkeditor extends JPlugin {
	function plgEditorCkeditor(& $subject, $config) {
		parent::__construct($subject, $config);
	}
	function onInit()
	{
		$doc = & JFactory::getDocument();
		$doc->addStyleDeclaration("table.admintable {width: 100%;}");
  		$result = '<script type="text/javascript" src="'.JURI::root().'plugins/editors/ckeditor/ckeditor.js"></script>';
  		return $result;
	}
	function onGetContent( $editor ) {
		return " CKEDITOR.instances.$editor.getData(); ";
	}
	function onSetContent( $editor, $html ) {
		//return " oFCKeditor.InsertHtml = '" .  htmlentities($html) . "'";
	}
	function onSave( $editor ) {}
	
	function onDisplay( $name, $content, $width, $height, $col, $row, $buttons = true )
	{
		JHTML::_('behavior.modal', 'a.modal-button');
		if (is_numeric( $width )) 	{		$width .= 'px';		}
		if (is_numeric( $height )) 	{		$height .= 'px';	}

		$html = '
		<textarea name="'.$name.'" id="'.$name.'" cols="'.$col.'" rows="'.$row.'" style="width:'.$width.'; height:'.$height.'">' .$content.   '</textarea>';
		
		$user = &JFactory::getUser();
		
		$fr = '';
		if(!strpos(JPATH_BASE,'administrator'))	$fr = '_ft';
		
  		$html .= "<script type='text/javascript'>
		var ".$name." = CKEDITOR.replace( '".$name."',
		{
			skin : '".$this->params->get( 'skin', 'kama' )."',
			language : '".$this->params->get( 'language', 'en' )."',
			uiColor: '".$this->params->get( 'Color', '#6B6868' )."',
			enterMode		: ".$this->params->get( 'entermode','1').",
			shiftEnterMode	: ".$this->params->get( 'shiftentermode','2').",
			";
  		if($this->params->get( 'toolbar'.$fr,'Default') != 'Default')
  			$html .= "toolbar :[".$this->params->get($this->params->get( 'toolbar'.$fr,'Default')."_ToolBar","[ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ]").'],';
  			
  		
  		if($this->params->get( 'ckfinder','1') == 1)
  		{
  			
	  		$gid = $user->get('gid');
	  		$access = $this->params->get( 'usergroup_access',array('25'));
	  		if(is_array($access))
	  			$access_true = array_search($gid,$access);
	  		else 
	  		{
	  			if($gid == $access)
					$access_true = true;
				else 
					$access_true = false;
	  		}
	  		if($access_true || $this->params->get( 'ckfinder','0') != 0)
	  		{
	  			$this->dbInsert();
		  		$html .= "
					filebrowserBrowseUrl : '".JURI::root()."plugins/editors/ckfinder/ckfinder.html',
					filebrowserImageBrowseUrl : '".JURI::root()."plugins/editors/ckfinder/ckfinder.html?Type=Images',
					filebrowserFlashBrowseUrl : '".JURI::root()."plugins/editors/ckfinder/ckfinder.html?Type=Flash',
					filebrowserUploadUrl : '".JURI::root()."plugins/editors/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
					filebrowserImageUploadUrl : '".JURI::root()."plugins/editors/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
					filebrowserFlashUploadUrl : '".JURI::root()."plugins/editors/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'";
	  		}
  		}
		$html .= "});
		</script>";
		$html .= $this->_displayButtons($name, $buttons);
		return $html;
	}
	function onGetInsertMethod($name)
	{
		$doc = & JFactory::getDocument();
		$url = str_replace('administrator/', '', JURI::base() );
		$js= "function jInsertEditorText( text,editor ) {
				text = text.replace( /<img src=\"/, '<img src=\"".$url."' );
				CKEDITOR.instances.".$name.".insertHtml( text );
		}";
		$doc->addScriptDeclaration($js);

		return true;
	}
	function _displayButtons($name, $buttons)
	{
		JHTML::_('behavior.modal', 'a.modal-button');
		$args['name'] = $name;
		$args['event'] = 'onGetInsertMethod';
		$return = '';
		$results[] = $this->update($args);
		foreach ($results as $result) {
			if (is_string($result) && trim($result)) {
				$return .= $result;
			}
		}

		if(!empty($buttons))
		{
			$results = $this->_subject->getButtons($name, $buttons);
			$return .= "\n<div id=\"editor-xtd-buttons\">\n";
			foreach ($results as $button)
			{
				if ( $button->get('name') ) 
				{
					$modal		= ($button->get('modal')) ? 'class="modal-button"' : null;
					$href		= ($button->get('link')) ? 'href="'.$button->get('link').'"' : null;
					$onclick	= ($button->get('onclick')) ? 'onclick="'.$button->get('onclick').'"' : null;
					$return .= "<div class=\"button2-left\"><div class=\"".$button->get('name')."\"><a ".$modal." title=\"".$button->get('text')."\" ".$href." ".$onclick." rel=\"".$button->get('options')."\">".$button->get('text')."</a></div></div>\n";
				}
			}
			$return .= "</div>\n";
		}
		
		return $return;
	}
	function dbInsert()
	{
		$db =& JFactory::getDBO();
		$ip = md5($_SERVER['REMOTE_ADDR']);
		$db->setQuery('SELECT session_id FROM #__session WHERE session_id ="' .$ip .'"');
		$ip_recorded = $db->loadResult();
		if(!isset($ip_recorded)) 
			$query = 'insert into #__session(username,time,session_id,gid) values(\'' . $ip_recorded . '\',\'' . (time() + 7200) . '\',\'' . $ip  . '\',0)';
		else 
			$query = 'update #__session set time = \'' . (time() + 7200) . '\',username = \'' . $ip_recorded . '\' ' .'where session_id =\'' .$ip .'\''; 
		$db->setQuery( $query);
		$db->query();
	}
}