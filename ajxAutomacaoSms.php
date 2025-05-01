<?php 

	include '_system/_functionsMain.php'; 

	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_EMPRESA']));

	switch($opcao){

		case 'exc':

			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$tipo = fnLimpaCampo($_GET['tp']);

			$sql = "DELETE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
			// fnEscreve($sql);
			mysqli_query(connTemp($cod_empresa,''),$sql);

			switch ($tipo) {
				case "msg":
					$sql = "DELETE FROM MENSAGEM_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE_BLOCO = $cod_template";
					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa,''),$sql);
				break;
				case "wait":
					$sql = "DELETE FROM AGUARDO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa,''),$sql);
				break;
			}

		break;

		default:

			$cod_campanha = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CAMPANHA']));
			$cod_bltempl = fnLimpaCampoZero($_REQUEST['COD_BLTEMPL']);


			$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS(
								COD_EMPRESA,
								COD_CAMPANHA,
								COD_BLTEMPL
								) VALUES(
								$cod_empresa,
								$cod_campanha,
								$cod_bltempl
								)";
			//fnEscreve($sql);

			mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			
			$count=0;
														
			$sql = "SELECT * FROM TEMPLATE_AUTOMACAO_SMS 
					WHERE COD_TEMPLATE = (SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_AUTOMACAO_SMS 
											WHERE COD_EMPRESA = $cod_empresa AND COD_BLTEMPL = $cod_bltempl)
					ORDER BY NUM_ORDENAC";
			//fnEscreve($sql);
			$qrTempl = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));


				switch($cod_bltempl){

					case 25:
						$texto = "{configurar}";
						$texto2 = "Campanha #$cod_campanha: $des_campanha";
						$tipo = "msg";
						$modConfig = 1567;
					break;

					case 26:
						$texto = "{configurar}";
						$texto2 = "Campanha #$cod_campanha: $des_campanha";
						$tipo = "wait";
						$modConfig = 1570;
					break;

					case 27:

						$texto = "{configurar}";
						$texto2 = "Campanha #$cod_campanha: $des_campanha";
						$tipo = "tag";
						$modConfig = 1569;
						
					break;

				}

				$sql2 = "SELECT DES_COR, DES_ICONE FROM BLOCO_COMUNICACAO WHERE COD_BLTEMPL = $qrTempl[COD_BLTEMPL]";
				// fnEscreve($sql2);
				$qrIco = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql2));
?>

				<div class="row movable" id="BLOCO_<?=$qrTempl[COD_TEMPLATE]?>" style="border-color: <?=$qrIco['DES_COR']?>">
					<div class="col-md-1 col-xs-2 text-right barra" style="color:<?=$qrIco['DES_COR']?>">
			    		<span class="<?=$qrIco[DES_ICONE]?>" style="padding-top:2px; font-size: 21px;"></span>
			    	</div>
			    	<div class="col-md-8 col-xs-6 text-left no-padding-sides"><?=$texto?></div>
			    	<div class="col-md-2 col-xs-3 text-right no-padding-sides">
			    		<a href="javascript:void(0)" class="btn btn-info btn-xs transparency openModal" data-url="action.php?mod=<?php echo fnEncode($modConfig)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&idt=<?=fnEncode($qrTempl[COD_TEMPLATE])?>&pop=true" data-title="<?=$texto2?>" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm"); ajustaModal("<?=$tipo?>");} catch(err) {}'>
			    			<span class="fal fa-edit"></span>
			    			&nbsp;Editar
			    		</a>
			    		&nbsp;
			    	</div>
			    	<div class="col-md-1 col-xs-1 text-left no-padding-sides"><a href="javascript:void(0)" class="btn btn-danger btn-xs transparency" onclick='excBloco("<?=$qrTempl[COD_TEMPLATE]?>","<?=$tipo?>")'>&nbsp;<span class="fal fa-times"></span>&nbsp;</a></div>
				</div>

				<script type="text/javascript">
					var Ids = "";
					jQuery('#drop-target .movable').each(function( index ) {
						Ids += jQuery(this).attr('id').substring(6) + ",";
					});

					var arrayOrdem = Ids.substring(0,(Ids.length-1));

					execOrdenacao(arrayOrdem,9,"<?=$cod_empresa?>");

				</script>

<?php

		break;
	}
?>
