jQuery(document).ready(function($){
	$('#commentImageInput').uploadify({
		'uploader'     : commentImagePluginPath + '/vendor/uploadify.swf',
		'script'       : commentImagePluginPath + '/scripts/upload.php',
		'cancelImg'    : commentImagePluginPath + '/vendor/cancel.png',
		'auto'         : true,
		'fileDataName' : 'commentImage',
		onComplete     : function(event, queueId, fileObj, response, data){
			if ( response.indexOf(' ') !== -1 ) {
				alert(response);
			} else {
				$('#uploadedFiles').append('<li>'+response+'</li>');
				$('#uploaded_comment_files').val( $('#uploaded_comment_files').val() + '[img]'+response+'[/img]');
			}
			return true;
		}
	});
});
