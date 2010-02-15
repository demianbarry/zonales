function deleteComment( commentID ){
		
		if( !commentID )commentID=0;
		
		var url   = 'index.php';
		var query = 
		 "option=com_jwf"
		+"&controller=field"
		+"&task=invoke&"
		+"&"+jtoken+"=1"
		+"&workflowID=" + workflowID
		+"&stationID=" + stationID
		+"&itemID=" + itemID
		+"&stepID=" + stepID
		+"&method=delete"
		+"&fieldType=comments"
		+"&data[commentID]=" + commentID;

		ajaxRequest = new Request({
				url: url,
				method: 'get', 
				onSuccess: onCommentDeleted
		});

		displayLoading(true);
		ajaxRequest.send(query);

}

function postComment( commentID, text ){

		if( !commentID )commentID=-1;

		var url   = 'index.php';
		var query = 
		 "option=com_jwf"
		+"&controller=field"
		+"&task=invoke&"
		+"&"+jtoken+"=1"
		+"&workflowID=" + workflowID
		+"&stationID=" + stationID
		+"&itemID=" + itemID
		+"&stepID=" + stepID
		+"&method=save"
		+"&fieldType=comments"
		+"&data[text]=" + text
		+"&data[commentID]=" + commentID;

		ajaxRequest = new Request({
				url: url,
				method: 'get', 
				onSuccess: onCommentPosted
		});

		cancelEdit();
	
		displayLoading(true);
		ajaxRequest.send(query);
}
function resizeOverlay(){
		
		var loadingDiv = $('jwf_comment_overlay');
		var loadingImg = $('jwf_comment_loading');

		windowSize       = window.getSize();
		windowScrollSize = window.getScrollSize();
		windowScroll     = window.getScroll();
		
		var scrollX = windowScroll.x;
		var scrollY = windowScroll.y;
		
		var w = windowScrollSize.x;
		var h = windowScrollSize.y;
		
		loadingDiv.set('styles', {
			'width' :w + 'px',
			'height':h + 'px',
			'left'  :'0px'   ,
			'top'   :'0px'
		});
		
		imageSize = loadingImg.getSize();
		imageX = scrollX + windowSize.x/2 - imageSize.x/2;
		imageY = scrollY + windowSize.y/2 - imageSize.y/2;
		
		loadingImg.set('styles', {
			'left'  :imageX + 'px',
			'top'   :imageY + 'px'
		});
		
}

function displayLoading(state){
		
		if( state == false ){
			loadingDiv.style.display = 'none';
			loadingImg.style.display = 'none';
			return;
		}
	
		var loadingDiv = $('jwf_comment_overlay');
		var loadingImg = $('jwf_comment_loading');
	
		resizeOverlay();
		
		window.addEvent("resize", function(){resizeOverlay()});
		window.addEvent("scroll", function(){
			windowSize       = window.getSize();
			var fx = new Fx.Morph($('jwf_comment_loading'), {
				duration:100, 
				wait:true
			});
			fx.start({
			"top":  (windowSize.y/2 + window.getScrollTop()) ,
			"left": (windowSize.x/2 + window.getScrollLeft())
			});
		});


		loadingDiv.set('opacity',0.70);
		loadingImg.set('opacity',1.00);

		loadingDiv.style.display = 'block';
		loadingImg.style.display = 'block';


}
function newComment(){postComment(0,$('jwf_comment_editor_input').value);}

function cancelEdit(){

	var div = $('jwf_comment_compact_editor');
	var txt = $('jwf_comment_compact_editor_input');
	
	div.style.display = 'none';
	txt.value = '';
	
	currentCommentID = 0;
	
}
var currentCommentID = 0;
function onEdit( commentID ){

	var div = $('jwf_comment_compact_editor');
	var txt = $('jwf_comment_compact_editor_input');
	
	
	var comment = $('jwf_comment_'+commentID);
	var dimensions = comment.getSize();
	var position   = comment.getPosition();
	
	if( dimensions.y < 20 )dimensions.y = 40;

	div.set('styles', {'width' :dimensions.x + 'px','height':dimensions.y + 'px'});
	div.set('styles', {'left'  :position.x   + 'px','top'   :position.y   + 'px'});
	
	txt.value = comment.get('html');
	div.style.display = 'block';
	div.style.position= 'absolute';
	
	
	currentCommentID = commentID;

}

function saveComment(){
	
	if( !currentCommentID )return;
	var comment = $('jwf_comment_compact_editor_input');
	postComment( currentCommentID, comment.value );
}

function onCommentDeleted(a,b){if( parseInt(a,10) == 1)window.location.reload();else alert( 'failed' );}
function onCommentPosted(a,b){if( parseInt(a,10) == 1)window.location.reload();else alert( 'failed' );}