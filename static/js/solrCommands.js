var socket;

window.addEvent('domready', function() {
	  socket = io.connect();
});

function executeCommand(commit) {
	socket.emit('proxyExecuteSolrCommand', {data: $('solrCommand').value, commit: commit}, function (resp) {
		$('response').value = resp;
	});	
}
