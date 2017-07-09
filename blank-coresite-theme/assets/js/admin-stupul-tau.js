/*
Name: stupul-tau-script
Dependencies: jquery,media
Version: 0.1
Footer: true
Scope: admin
*/
var custom_uploader;
jQuery('.custom-upload-button').click(function(e){
	e.preventDefault();
	if(custom_uploader){custom_uploader.open();return;}
	custom_uploader=wp.media.frames.file_frame=wp.media({title:"Choose Image",button:{text:"Choose Image"},multiple:false});
	custom_uploader.on("select",function(){var attachment=custom_uploader.state().get("selection").first.toJSON();jQuery(e.target).prev().val(attachment.url);});
	custom_uploader.open();
	});
