<link href="js/plugins/fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="js/plugins/fileinput/js/plugins/piexif.min.js" type="text/javascript"></script>
<script src="js/plugins/fileinput/js/plugins/sortable.min.js" type="text/javascript"></script>
<script src="js/plugins/fileinput/js/plugins/purify.min.js" type="text/javascript"></script>
<script src="js/plugins/fileinput/js/fileinput.js?v=<?=date("his")?>"></script>
<script src="js/plugins/fileinput/js/locales/pt-BR.js"></script>

<input
	id="fileinput"
	name="file[]"
	type="file"
	multiple
>

<script>
$(document).ready(function() {
    $("#fileinput").fileinput({
		allowedFileExtensions: ['jpg', 'png', 'gif'],
		uploadUrl: "ajxUpload.php?<?=$_SERVER['QUERY_STRING'];?>",
		uploadAsync: true,
		//deleteUrl: "/site/file-delete",
		showUpload: false, // hide upload button
		showRemove: false, // hide remove button
		overwriteInitial: false, // append files to initial preview
		minFileCount: 1,
		maxFileCount: 5,
		initialPreviewAsData: true,
	}).on("filebatchselected", function(event, files) {
		$("#fileinput").fileinput("upload");
	});
});
</script>
<style>
.file-upload-indicator,
.file-footer-buttons,
.file-drag-handle{
	display:none;
}
</style>