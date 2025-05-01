<?php
include '../_system/_functionsMain.php';

?>
<html lang="br">
<head>
    <title>File upload progress bar with percentage using form jquery example</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" >
    <script src="../js/jquery.min.js"></script> 
    <script src="../js/jquery.form.js"></script> 
    
    <script> 
        $(document).ready(function() { 

         var progressbar     = $('.progress-bar');

            $(".upload-image").click(function(){
            	$(".form-horizontal").ajaxForm(
		{
		  target: '.preview',
		  beforeSend: function() {
			$(".progress").css("display","block");
			progressbar.width('0%');
			progressbar.text('0%');
                    },
		    uploadProgress: function (event, position, total, percentComplete) {
		        progressbar.width(percentComplete + '%');
		        progressbar.text(percentComplete + '%');
		     },
		})
		.submit();
            });

        }); 
    </script>
</head>
<body>
	
	<div class="container text-center">
		
		<div style="border: 1px solid #a1a1a1;text-align: center;width: 500px;padding:30px;margin:0px auto">
                    <form action="uploadpro.php" enctype="multipart/form-data" class="form-horizontal" method="post">

				<div class="preview"></div>
				 <div class="progress" style="display:none">
				  <div class="progress-bar" role="progressbar" aria-valuenow="0"
				  aria-valuemin="0" aria-valuemax="100" style="width:0%">
				    0%
				  </div>
				</div>

				<input type="file" name="image" class="form-control" />
				<button class="btn btn-primary upload-image">Upload Image</button>

				
			</form>
		</div>
	</div>
</body>
</html>