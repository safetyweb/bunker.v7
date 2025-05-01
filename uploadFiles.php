	<?php
	$cod_empresa = $_GET['id'];	
	//fnEscreve($cod_empresa);
	?>
	
	<style>

	/* layout.css Style */
	.upload-drop-zone {
	  height: 200px;
	  border-width: 2px;
	  margin-bottom: 20px;
	}

	/* skin.css Style*/
	.upload-drop-zone {
	  color: #ccc;
	  border-style: dashed;
	  border-color: #ccc;
	  line-height: 200px;
	  text-align: center
	}
	.upload-drop-zone.drop {
	  color: #222;
	  border-color: #222;
	}

	.file-preview-input {
		position: relative;
		overflow: hidden;
		margin: 0px;    
		color: #333;
		background-color: #fff;
		border-color: #ccc;    
	}
	.file-preview-input input[type=file] {
		position: absolute;
		top: 0;
		right: 0;
		margin: 0;
		padding: 0;
		font-size: 20px;
		cursor: pointer;
		opacity: 0;
		filter: alpha(opacity=0);
	}
	.file-preview-input-title {
		margin-left:2px;
	}

	</style>
    
	<script src="js/jquery.form.js"></script> 
    
    <script> 
        $(document).ready(function() { 

         var progressbar     = $('.progress-bar');

            $(".upload-image").click(function(){
            	$(".form-horizontal").ajaxForm({
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
					success:function(data){
						$(".progress").css("display","none");
						$("#msgUpload").css("display","block");
					},
				})
				.submit();
            });

        }); 
    </script>
	
	<div class="container">
		
            <form action="uploads/uploadpro.do?id=<?php echo $cod_empresa; ?>" enctype="multipart/form-data" class="form-horizontal" method="post">
				
				<div class="row">
				
				
					<div class="col-md-12">
					
						<div class="alert alert-success alert-dismissible " role="alert" id="msgUpload" style="display:none;">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
							Upload realizado com <strong>sucesso!</strong>
						</div>

						<div class="input-group file-preview">
						<input placeholder="" type="text" class="form-control file-preview-filename" disabled="disabled">
							
							<!-- don't give a name === doesn't send on POST/GET --> 
							<span class="input-group-btn"> 
								<!-- file-preview-clear button -->
								<button type="button" class="btn btn-default file-preview-clear" style="display:none;"> <span class="glyphicon glyphicon-remove"></span> Limpar </button>
								<!-- file-preview-input -->
								<div class="btn btn-default file-preview-input"> <span class="glyphicon glyphicon-folder-open"></span> <span class="file-preview-input-title"> Anexar imagem</span>
								<input type="file" accept="text/cfg" class="form-control" name="arquivo"/>
								<!-- rename it --> 
								</div>
							</span> 
							
						</div>
						<!-- /input-group image-preview [TO HERE]--> 					

						<div class="push5"></div>
						
					</div>
					
				</div>
				
				<div class="push10"></div>
				
				<div class="row pull-right">					
					<div class="col-md-12">				
						<button class="btn btn-primary upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp;  Upload da Imagem</button>				
					</div>
				</div>
			
				<div class="push20"></div>
				 <div class="progress" style="display:none">
				  <div class="progress-bar" role="progressbar" aria-valuenow="0"
				  aria-valuemin="0" aria-valuemax="100" style="width:0%">
				    0%
				  </div>
				</div>
				<div class="push20"></div>
				<div class="preview"></div>
				
			</form>
		
	</div>
