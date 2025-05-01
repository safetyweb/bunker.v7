<?php
include './_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_EMPRESA']));
$cod_cliente = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CLIENTE']));
$num_cgcecpf = fnLimpaCampoZero(fnDecode($_REQUEST['NUM_CGCECPF']));
$cod_veiculos = fnLimpaCampoZero(fnDecode(@$_REQUEST['COD_VEICULOS']));
$des_placa = strtoupper(fnLimpaCampo($_REQUEST['DES_PLACA']));
$opcao = fnLimpaCampo($_REQUEST['opcao']);

switch($opcao){

	case 'excluir':
                    $log_placa="INSERT INTO veiculos_exec( COD_VEICULOS,
									COD_EXTERNO,
									DAT_CADASTR,
									COD_CLIENTE,
									COD_CLIENTE_EXT,
									COD_MARCA,
									COD_EXTMARCA,
									COD_MODELO,
									COD_EXTMODE,
									DES_PLACA,
									DAT_ALTERAC,
									COD_USUALTE,
									COD_EMPRESA,
									LOG_ATUALIZ,
									DAT_EXCLUSA) 
									SELECT  COD_VEICULOS,
                                                                                COD_EXTERNO,
                                                                                DAT_CADASTR,
                                                                                COD_CLIENTE,
                                                                                COD_CLIENTE_EXT,
                                                                                COD_MARCA,
                                                                                COD_EXTMARCA,
                                                                                COD_MODELO,
                                                                                COD_EXTMODE,
                                                                                DES_PLACA,
                                                                                DAT_ALTERAC,
                                                                                COD_USUALTE,
                                                                                COD_EMPRESA,
                                                                                LOG_ATUALIZ,
                                                                                NOW() 
                    from veiculos  WHERE COD_VEICULOS = $cod_veiculos;";                    
                    $Rslog_placa=mysqli_query(connTemp($cod_empresa,''),$log_placa);
                
                    $sql = "DELETE FROM VEICULOS WHERE COD_VEICULOS = $cod_veiculos";
                    mysqli_query(connTemp($cod_empresa,''),$sql);
               
	break;

	default:

		$sql = "SELECT DES_PLACA FROM VEICULOS WHERE DES_PLACA = '$des_placa'";

		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		if(mysqli_num_rows($arrayQuery) == 0){

			if($num_cgcecpf != 0){

				$sql = "INSERT INTO VEICULOS(
										COD_CLIENTE,
										COD_CLIENTE_EXT,
										COD_EMPRESA,
										DES_PLACA
									)VALUES(
										$cod_cliente,
										'$num_cgcecpf',
										$cod_empresa,
										'$des_placa'
									)";

				// fnEscreve($sql);

				mysqli_query(connTemp($cod_empresa,''),$sql);

			?>
				<script type="text/javascript">
					parent.$.alert({
			          title: "CADASTRO REALIZADO",
			          content: "Veículo cadastrado com sucesso.",
			          type: 'green',
			          backgroundDismiss: true,
			          buttons: {
			            "OK": {
			               action: function(){
			                
			               }
			            }
			          }
			        });
				</script>

			<?php

			}else{

			?>
				<script type="text/javascript">
					parent.$.alert({
			          title: "ERRO",
			          content: "Ocorreu um erro ao realizar seu cadastro. Por favor, tente novamente mais tarde.",
			          type: 'red',
			          backgroundDismiss: true,
			          buttons: {
			            "OK": {
			               action: function(){
			                
			               }
			            }
			          }
			        });
				</script>

			<?php

			}

		}else{
			?>
			<script type="text/javascript">
				parent.$.alert({
		          title: "AVISO",
		          content: "Placa informada não é válida. Veículo já cadastrado por outra pessoa.",
		          type: 'orange',
		          backgroundDismiss: true,
		          buttons: {
		            "OK": {
		               action: function(){
		                
		               }
		            }
		          }
		        });
			</script>
			<?php 
		}

	break;

}

	$sql = "SELECT COD_VEICULOS, DES_PLACA FROM VEICULOS WHERE COD_CLIENTE_EXT = $num_cgcecpf AND COD_EMPRESA = $cod_empresa AND TRIM(DES_PLACA) != '' AND DES_PLACA IS NOT NULL";
	// fnescreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$loopH=0;

	while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {

	?>

	  <div class="col-xs-8" style="font-size: 20px!important;">
	   <div class="col-xs-9">
	   	<p class="text-muted"><span class="fa fa-car"></span>&nbsp; <?=$qrVeic['DES_PLACA']?></p>
	   </div>
	   <div class="col-xs-3 text-right">
	   	<a href="javascript:void(0)" onclick='parent.excPlaca("<?=fnEncode($qrVeic[COD_VEICULOS])?>")'><span class="fa fa-trash text-danger" style="padding-top: 3.5px;"></span></a>
	   </div>
	  </div>

	<?php

	}

?>