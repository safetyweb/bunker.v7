<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));
	$opcao = fnLimpaCampo($_GET['opcao']);
	$chave_linha = fnLimpaCampo($_POST['CHAVE_LINHA']);
	$cod_documento = fnLimpaCampoZero(fnDecode($_POST['COD_ANEXO']));
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

	switch ($opcao) {
		case 'aprovar':

			$sql = "INSERT INTO HISTORICO_ANEXO(
										COD_EMPRESA,
										COD_ANEXO,
										COD_STATUS,
										DAT_STATUS,
										DES_JUSTIFICA,
										COD_USUCADA
									) VALUES(
										$cod_empresa,
										$cod_documento,
										2,
										NOW(),
										'Aprovado',
										$cod_usucada
									  );

						UPDATE ANEXO_DOC
            			SET COD_STATUS = 2
            			WHERE COD_DOCUMENTO = $cod_documento
            			AND COD_EMPRESA = $cod_empresa;";
					
				// fnEscreve($sql);
                mysqli_multi_query(connTemp($cod_empresa,''),$sql);

		break;
		
		default:

			?>

			<table class="table table-striped">

				<thead>

					<tr>
						<th class="{ sorter: false }" width="5%"></th>
						<th class="{ sorter: false }" width="30%">Arquivo</th>
						<th class="{ sorter: false }" width="10%">Dt. Recebimento</th>
						<th class="{ sorter: false } text-center" width="8%">Status</th>
						<th class="{ sorter: false }" width="10%">Dt. Status</th>
						<th class="{ sorter: false }" width="20%">Usuário</th>
						<th class="{ sorter: false } text-center" width="10%">Histórico</th>
						<th class="{ sorter: false }" width="7%">Ação</th>
					</tr>

				</thead>

				<tbody>

				<?php
					$completaCont = "";

					$sqlDocConvenio = "SELECT AC.*, SA.DES_STATUS, US.NOM_USUARIO FROM ANEXO_DOC AC 
										INNER JOIN WEBTOOLS.STATUS_ANEXO SA ON SA.COD_STATUS = AC.COD_STATUS
										INNER JOIN ANEXO_DOCUMENTO AD ON AD.COD_ANEXO = AC.COD_ANEXO
										LEFT JOIN WEBTOOLS.USUARIOS US ON US.COD_USUARIO = AC.COD_USUCADA
										WHERE AC.COD_CLIENTE = $cod_cliente $completaCont 
										AND (AD.COD_EXCLUSA IS NULL OR AD.COD_EXCLUSA = 0)
										ORDER BY DAT_CADASTR DESC";

					$arDocConvenio = mysqli_query(connTemp($cod_empresa,''),$sqlDocConvenio);

					// fnEscreve($sqlDocConvenio);

					while($qrDocConvenio = mysqli_fetch_assoc($arDocConvenio)){

						if($qrDocConvenio[COD_STATUS] == 2){
							continue;
						}

						$file_ext = strtolower(end(explode('.', $qrDocConvenio['NOM_ORIGEM'])));

						$sqlHist = "SELECT * FROM HISTORICO_ANEXO 
									WHERE COD_ANEXO = $qrDocConvenio[COD_DOCUMENTO]";


						$arrayHist = mysqli_query(connTemp($cod_empresa,''),$sqlHist);

						$qtd_hist = mysqli_num_rows($arrayHist);
						
						$qtd_hist++;

						$sqlHist = "SELECT A.*,
									CASE WHEN A.COD_STATUS =3 THEN
									B.DES_JUSTIFICA
									ELSE 
									A.DES_JUSTIFICA
									END AS JUSTIFICA
									FROM HISTORICO_ANEXO A
									LEFT JOIN JUSTIFICATIVA B ON A.DES_JUSTIFICA=COD_JUSTIFICA AND A.COD_STATUS=3
									WHERE A.COD_ANEXO = $qrDocConvenio[COD_DOCUMENTO]
									ORDER BY 1 DESC
									LIMIT 1";

						// fnEscreve($sqlHist);

						$arrayHist = mysqli_query(connTemp($cod_empresa,''),$sqlHist);

						$qrHist = mysqli_fetch_assoc($arrayHist);

						$dat_status = $qrHist[DAT_STATUS];

						$mostra_status = "";
						$cor = "";
						$badge = "badge";
						$txtBadge = "txtBadge";
						$tooltip = "";
						$mostraAprovar = "block";
						$textoReprova = "Reprovar";

						if($qtd_hist == 0){
							$qtd_hist = 1;
						}

						if($dat_status == ""){
							$dat_status = $qrDocConvenio['DAT_CADASTR'];
						}

						if($qrHist[COD_STATUS] == 2){

							$cor = "background:#18bc9c;";
							$mostra_status = "<span class='fas fa-check'></span>";
							$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrHist[JUSTIFICA]'";
							$mostraAprovar = "none";

						}else if($qrHist[COD_STATUS] == 3){

							$cor = "background:red; color:white;";
							$mostra_status = "<span class='fas fa-info'></span>";
							$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrHist[JUSTIFICA]'";
							$textoReprova = "Alterar Justificativa";

						}else{
							$cor = "background:blue; color:white;";
							$mostra_status = "<span class='fas fa-sync'></span>";
							$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrDocConvenio[DES_STATUS]'";
							$textoReprova = "Reprovar";

						}

						$status = "<span class='".$badge."' style='".$cor."' $tooltip><span class='".$txtBadge." ".$textRed."'>".$mostra_status."</span></span>";

	        	?>				
																			

							<tr class="accordion-toggle"  data-toggle="collapse" data-target=".Convenio">
								<td></td>
								<td>
									<?php if($file_ext == "jpeg" || $file_ext == "jpg" || $file_ext == "png"){ ?>
										<a href="https://adm.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrDocConvenio['NOM_ORIGEM']; ?>" class="download" target="files" onclick="openNav()"><span class="fal fa-file-search"></span>
										</a>
									<?php }else{ ?>
										<a href="https://docs.google.com/a/192.99.240.249/viewer?url=http://adm.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrDocConvenio['NOM_ORIGEM']; ?>&pid=explorer&efh=false&a=v&chrome=false&embedded=true" class="download" target="files" onclick="openNav()"><span class="fal fa-file-search"></span></a>
									<?php } ?>

									&nbsp;&nbsp;
								
									<a class="download" href="../media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrDocConvenio['NOM_ORIGEM']; ?>" download><span class="fal fa-arrow-to-bottom"></span></a>

									&nbsp;&nbsp;

									<?php echo $qrDocConvenio['NOM_REFEREN']; ?>
								</td>
								<td>
									<small><?php echo date("d/m/Y",strtotime($qrDocConvenio['DAT_CADASTR'])) ?></small>
									<small><?php echo date("H:i:s",strtotime($qrDocConvenio['DAT_CADASTR'])) ?></small>
								</td>
								<td class="text-center"><?=$status?></td>
								<td>
									<small><?php echo date("d/m/Y",strtotime($dat_status)) ?></small>
									<small><?php echo date("H:i:s",strtotime($dat_status)) ?></small>
								</td>
								<td><?=$qrDocConvenio['NOM_USUARIO']?></td>
								<td class="text-center"><?=$qtd_hist?></td>
								<td>
									<small>
					           			<div class="btn-group dropdown dropleft">
											<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												ações &nbsp;
												<span class="fas fa-caret-down"></span>
										    </button>
											<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
												<!-- <li class="divider"></li> -->
												<li style="display: <?=$mostraAprovar?>;"><a href="javascript:void(0)" onclick='reloadAnexo("<?=fnEncode($qrDocConvenio[COD_DOCUMENTO])?>","aprovar","<?=$chave_linha?>","<?=fnEncode($cod_cliente)?>")'><span class="fal fa-clipboard-check"></span>&nbsp; Aprovar</a></li>
												<li><a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1851)?>&id=<?php echo fnEncode($cod_empresa)?>&ida=<?=fnEncode($qrDocConvenio[COD_DOCUMENTO])?>&idc=<?=fnEncode($chave_linha)?>&idcli=<?=fnEncode($cod_cliente)?>&pop=true" data-title="Justificativa de reprovação"><span class="fal fa-ban"></span>&nbsp; <?=$textoReprova?></a></li>
											</ul>
										</div>
					           		</small>
								</td>
							</tr>
						
						
	            <?php
	        		}
	            ?>

			</tbody>

			<script type="text/javascript">$('[data-toggle="tooltip"]').tooltip();</script>

<?php 
		
		break;
	}

?>