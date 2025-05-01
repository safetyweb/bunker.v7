<div class="push30"></div>

<div class="row">
	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?>
				</div>
			</div>

			<div class="push10"></div>

			<div class="portlet-body">

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Busca</legend>

							<div class="row">
								<div class="col-md-10">
									<div class="form-group">
										<label for="inputName" class="control-label">Digite a imagem a ser buscada</label>
										<input type="text" class="form-control input-sm" id="BUSCA" maxlength="100">
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Qtd. Imagens</label>
										<input type="number" class="form-control input-sm" id="LIMIT" value="10">
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
						</fieldset>
						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">
							<a href="javascript:" onclick='searchWeb()' class="btn btn-primary">
								<i class="fal fa-search" aria-hidden="true"></i>
								&nbsp; Buscar
							</a>
						</div>

						<div class="push5"></div>

					</form>


					<fieldset>
						<legend>Resultado da Busca</legend>

						<div class="row">
							<div class="col-md-12" id="result">
							</div>
						</div>
					</fieldset>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<script>
	function searchWeb() {
		$.ajax({
			method: 'POST',
			url: 'ajxBuscaImagensWeb.php',
			data: {
				query: $("#BUSCA").val(),
				limit: $("#LIMIT").val(),
			},
			beforeSend: function() {
				$('#result').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				console.log(data);
				$('#result').html('');
				if (data.message != "") {
					$('#result').html(data.message);
				} else if (data.images.length <= 0) {
					$('#result').html("Nenhuma imagem encontrada!");
				} else {
					data.images.map(image => {
						$('#result').append(`<img src="${image.uri}">`);
					});
				}
			}
		});
	}
</script>