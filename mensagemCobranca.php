		<?php

		$hashLocal = mt_rand();	
			//verifica se vem da tela sem pop up
		if (is_null($_GET['pre'])) {$log_preconf='N';}else{$log_preconf='S';}

		if( $_SERVER['REQUEST_METHOD']=='POST' )
		{
			$request = md5( implode( $_POST ) );
			
			if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
			{
				$msgRetorno = 'Essa página já foi utilizada';
				$msgTipo = 'alert-warning';
			}
			else
			{
				$_SESSION['last_request']  = $request;
				$msgcobr = addslashes($_REQUEST['MSGCOBR']);

				$opcao = $_REQUEST['opcao'];
				$hHabilitado = $_REQUEST['hHabilitado'];
				$hashForm = $_REQUEST['hashForm'];
			}                  
		}

	//busca dados da empresa
		$cod_empresa = $_GET['id'];	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
			//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	//fnEscreve($qrBuscaEmpresa['NOM_FANTASI']);

		if (isset($arrayQuery)) {
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}

		//busca dados da unidade
		$cod_univend = $_GET['idU'];	
		$sql2 = "SELECT COD_UNIVEND, NOM_UNIVEND FROM unidadevenda WHERE COD_EMPRESA = '".$cod_empresa."' AND COD_UNIVEND = '".$cod_univend."' ";

			//fnEscreve($sql);
		$arrayQuery2 = mysqli_query($connAdm->connAdm(),$sql2);
		$qrBuscaUnivend = mysqli_fetch_assoc($arrayQuery2);

		if (isset($arrayQuery2)) {
			$cod_univend = $qrBuscaUnivend['COD_UNIVEND'];
			$nom_univend = $qrBuscaUnivend['NOM_UNIVEND'];
		}

		$dir = "media/alertas/"; 
		$nomeArquivo = empty($cod_univend) ? $cod_empresa . "_mensagem_1.txt" : $cod_empresa . "_" . $cod_univend . "_mensagem_1.txt";

		if (($file = fopen($dir.$nomeArquivo, "r")) !== FALSE) {
			$contador = 1;
			while (($linha = fgetcsv($file, '24000', ";",'"',"\r\n")) !== FALSE) {

				if($contador > 1){
					$msgcobr = $linha[2];
				}

				$contador++;
			}
		}
		
		$hoje = date('d/m/Y');

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			$msgcobr = $_POST['MSGCOBR'];

			$nomeCaminho = $dir . $nomeArquivo;

			file_put_contents($nomeCaminho, '');
			
			$arquivo = fopen($nomeCaminho, 'w',0);

			$CABECHALHO = ['Empresa','Unidade','Mensagem_de_Cobranca','Data'];

			fputcsv ($arquivo,$CABECHALHO,';','"','\n');

			$row = [$cod_empresa,$cod_univend,$msgcobr,$hoje];

			fputcsv($arquivo, $row, ';', '"', '\n');

			echo '<script>window.parent.location.reload();</script>';
		}

		?>

		<?php if ($popUp != "true"){ ?>
			<div class="push30"></div> 
		<?php } ?>

		<div class="row">				

			<div class="col-md12 margin-bottom-30">
				<!-- Portlet -->
				<?php if ($popUp != "true"){  ?>							
					<div class="portlet portlet-bordered">
					<?php } else { ?>
						<div class="portlet" style="padding: 0 20px 20px 20px;" >
						<?php } ?>

						<?php if ($popUp != "true"){  ?>
							<div class="portlet-title">
								<div class="caption">
									<i class="glyphicon glyphicon-calendar"></i>
									<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
								</div>
								<?php include "atalhosPortlet.php"; ?>
							</div>
						<?php } ?>

						<div class="portlet-body">

							<?php if ($msgRetorno <> '') { ?>	
								<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<?php echo $msgRetorno; ?>
								</div>
							<?php } ?>

							<div class="login-form">

								<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

									<fieldset>
										<legend>Dados Gerais</legend> 

										<div class="row">
											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label required">Empresa</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
													<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
												</div>														
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label required">Unidade</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_univend; ?>">
													<input type="hidden" class="form-control input-sm" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo $cod_univend; ?>">
												</div>														
											</div>

											<div class="col-md-5">
												<div class="form-group">
													<label for="MSGCOBR">Mensagem de Cobrança</label>
													<textarea class="form-control" id="MSGCOBR" name="MSGCOBR" rows="3"><?php echo $msgcobr; ?></textarea>
												</div>
											</div>
										</div>

										<div class="push20"></div>

									</fieldset>										

									<div class="push10"></div>
									<hr>

									<div class="form-group text-right col-md-12">
										<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
										<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
									</div>

									<input type="hidden" name="opcao" id="opcao" value="">
									<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	

									<div class="push5"></div> 

								</form>

								<div class="push50"></div>

							</div>								

						</div>
					</div>
					<!-- fim Portlet -->
				</div>

			</div>					

			<div class="push20"></div>