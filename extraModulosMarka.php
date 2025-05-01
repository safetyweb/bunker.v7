<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
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

			$cod_modulmk = fnLimpaCampoZero($_REQUEST['COD_MODULMK']);
			$nom_modulmk = fnLimpaCampo($_REQUEST['NOM_MODULMK']);
			$des_extras = addslashes(htmlentities($_REQUEST['DES_EXTRAS']));
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			}  
			

		}
	}
      
	//fnMostraForm();

//só pra fixar o upload
$cod_empresa = 7;

$cod_busca = $_GET['id'];

//fnEscreve($cod_busca);

$sql1 = "select * from MODULOSMARKA where COD_MODULMK = $cod_busca ";
$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery1);
$nom_modulmk = $qrBuscaModulos['NOM_MODULMK'];
$des_imagem = $qrBuscaModulos['DES_IMAGEM'];
$des_extras = $qrBuscaModulos['DES_EXTRAS'];

	
?>
<style>
	p {
	    margin: 0; 
	}
	.change-icon:hover > .fa + .fa {
	  display: inherit;
	}
	
	.fa-edit:hover{
		color: #18bc9c;
	}
	
	.item{
		padding-top: 0;
	}
	
	.folder {
		height: 30px;
	}
	
	a, a:hover {
		text-decoration:none;
	}
	
</style>


		<link rel="stylesheet" href="css/widgets.css" />
		<div class="row">								

			<div class="col-md-4">

				<div class="row">
					<div class="col-xs-12">
													
						<div class='tile tile-default shadow change-icon' style='background-color: <?php echo $qrBuscaModulos['DES_COR']; ?>; font-size: 15px; color: #fff'>		
						<div class="row">
							<div class="col-xs-12">
								<i class="fa fa-plus" style="font-size: 15px; line-height: 4px; color: #fff; float: right; margin: 5px 0 0 0;"></i>
							</div>
						</div>
						<div class="push"></div>
						
						
							<i class="fa <?php echo $qrBuscaModulos['DES_ICONE']; ?> fa-3x" style="line-height: 40px; margin-bottom: 25px; "></i>
						
							<p class="folder" style="margin-bottom: 5px; font-size: 12px;"><?php echo $qrBuscaModulos['NOM_MODULMK']; ?> </p>
							<p style="font-size: 12px; height: 60px;"><?php echo $qrBuscaModulos['DES_MODULMK']; ?> </p>
																
						</div>

					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<img src="media/clientes/7/logo_marka_cor.png" width="300px">
					</div>
				</div>									
					
			</div>	
			
			<div class="col-md-8">

				<h3 style="margin-top: 0;"><?php echo $nom_modulmk; ?></h3>				
				<div class="push10"></div>
				
				<?php echo html_entity_decode($des_extras); ?>

			</div>

			
		</div>
		
		<div class="push20"></div> 
					

	
	<script type="text/javascript">
		
		
		
	</script>	
   