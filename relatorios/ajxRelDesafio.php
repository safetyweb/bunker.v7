<?php 

	include '../_system/_functionsMain.php'; 
	// require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	// use Box\Spout\Writer\WriterFactory;
	// use Box\Spout\Common\Type;	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$cod_empresa = fnDecode($_GET['id']);
	$cod_desafio = $_REQUEST['COD_DESAFIO'];
	$lojasSelecionadas = $_REQUEST['LOJAS'];

	//fnEscreve($opcao);


	$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));


	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}

	switch($opcao){

		case 'loja':


?>

		<div class="push20"></div>
												
		<table class="table table-bordered table-hover  ">
		
		  <thead>
			<tr>
			  <th><small>Loja</small></th>
			  <th><small>Atendimentos</small></th>
			  <th><small>Atingido</small></th>
			  <th><small>% Meta</small></th>
			  <th><small>Clientes</small></th>
			  <th><small>Tot. Vendas</small></th>
			  <th><small>Val. Resgate</small></th>
			  <th><small>Vendas <br/>Vinculadas</small></th>
			</tr>
		  </thead>
			
			<?php
				// Filtro por Grupo de Lojas
				include "filtroGrupoLojas.php";
				
				$sql = "SELECT A.COD_UNIVEND, 
						       F.NOM_FANTASI, 
						       COUNT(*) AS QTD_ATENDIMENTO, 
							       (SELECT COUNT(*) QTD_ATENDIMENTO 
							        FROM   DESAFIO_CONTROLE AA 
							               INNER JOIN CLIENTES BB 
							                       ON AA.COD_CLIENTE = BB.COD_CLIENTE 
							                          AND AA.COD_EMPRESA = BB.COD_EMPRESA 
							        WHERE  BB.LOG_AVULSO = 'N' 
							               AND (SELECT FC.DES_COMENT 
							                    FROM   FOLLOW_CLIENTE FC 
							                    WHERE  FC.COD_EMPRESA = A.COD_EMPRESA 
							                           AND FC.COD_CLIENTE = BB.COD_CLIENTE 
							                           AND FC.COD_DESAFIO = AA.COD_DESAFIO 
							                           AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) 
							                                             FROM   FOLLOW_CLIENTE 
							                                             WHERE  COD_CLIENTE = BB.COD_CLIENTE 
							                                                    AND COD_DESAFIO = 
							                                                        AA.COD_DESAFIO)) IS NOT NULL 
							               AND AA.COD_DESAFIO = A.COD_DESAFIO 
							               AND AA.COD_EMPRESA = A.COD_EMPRESA 
							               AND AA.COD_UNIVEND = A.COD_UNIVEND) AS QTD_ATINGIDO, 

						       D.VAL_METADES, 

							       IFNULL((SELECT COUNT(DISTINCT CC.COD_CLIENTE) 
							               FROM   VENDAS CC, 
							                      DESAFIO_CONTROLE DD 
							               WHERE  CC.COD_CLIENTE = DD.COD_CLIENTE 
							                      AND DD.COD_DESAFIO = A.COD_DESAFIO 
							                      AND CC.COD_UNIVEND = A.COD_UNIVEND 
							                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') >= D.DAT_INI 
							                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') <= D.DAT_FIM), 0) AS QTD_CLIENTE, 
							       IFNULL((SELECT SUM(VAL_TOTVENDA) 
							               FROM   VENDAS CC, 
							                      DESAFIO_CONTROLE DD 
							               WHERE  CC.COD_CLIENTE = DD.COD_CLIENTE 
							                      AND DD.COD_DESAFIO = A.COD_DESAFIO 
							                      AND CC.COD_UNIVEND = A.COD_UNIVEND 
							                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') >= D.DAT_INI 
							                      AND DATE_FORMAT(CC.dat_cadastr_ws, '%Y-%m-%d') <= D.dat_fim), 0) AS VAL_TOTVENDA, 
							       IFNULL((SELECT SUM(VAL_CREDITO) 
							               FROM   CREDITOSDEBITOS DD, 
							                      DESAFIO_CONTROLE EE 
							               WHERE  DD.COD_CLIENTE = EE.COD_CLIENTE 
							                      AND EE.COD_DESAFIO = A.COD_DESAFIO 
							                      AND DD.COD_UNIVEND = A.COD_UNIVEND 
							                      AND DD.TIP_CREDITO = 'D' 
							                      AND DATE_FORMAT(DD.DAT_REPROCE, '%Y-%m-%d') >= D.DAT_INI 
							                      AND DATE_FORMAT(DD.DAT_REPROCE, '%Y-%m-%d') <= D.DAT_FIM), 0) AS VAL_RESGATE, 
							       IFNULL((SELECT SUM(VAL_TOTVENDA) 
							               FROM   VENDAS EE, 
							                      CREDITOSDEBITOS FF, 
							                      DESAFIO_CONTROLE GG 
							               WHERE  EE.COD_VENDA = FF.COD_VENDA 
							                      AND EE.COD_UNIVEND = A.COD_UNIVEND 
							                      AND FF.COD_CLIENTE = GG.COD_CLIENTE 
							                      AND GG.COD_DESAFIO = A.COD_DESAFIO 
							                      AND FF.TIP_CREDITO = 'D' 
							                      AND DATE_FORMAT(FF.DAT_REPROCE, '%Y-%m-%d') >= D.DAT_INI 
							                      AND DATE_FORMAT(FF.DAT_REPROCE, '%Y-%m-%d') <= D.DAT_FIM), 0) AS VAL_VENDAS_VINCULADAS 
						FROM   DESAFIO_CONTROLE A 
						       INNER JOIN CLIENTES B 
						               ON A.COD_CLIENTE = B.COD_CLIENTE 
						                  AND A.COD_EMPRESA = B.COD_EMPRESA 
						       LEFT JOIN CATEGORIA_CLIENTE C 
						              ON C.COD_CATEGORIA = B.COD_CATEGORIA 
						       INNER JOIN DESAFIO D 
						               ON A.COD_DESAFIO = D.COD_DESAFIO 
						       INNER JOIN WEBTOOLS.UNIDADEVENDA F 
						               ON F.COD_UNIVEND = A.COD_UNIVEND 
						WHERE  B.LOG_AVULSO = 'N' 
						       AND A.COD_DESAFIO = $cod_desafio 
						       AND A.COD_EMPRESA = $cod_empresa 
						GROUP BY A.COD_UNIVEND 
						ORDER BY B.NOM_CLIENTE ";
				 		
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$countLinha = 1;
				while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)){

				?>

					<tr>
					  <td><small><b><?php echo $qrListaVendas['NOM_FANTASI']; ?></b></small></td>
					  <td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_ATENDIMENTO'],0); ?></small></td>
					  <td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_ATINGIDO'],0); ?></small></td>
					  <td class="text-center"><small><?php echo fnValor($qrListaVendas['VAL_METADES'],2); ?>%</small></td>
					  <td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_CLIENTE'],0); ?></small></td>
					  <td class="text-center"><small><small>R$ </small></small><small><?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
					  <td class="text-center"><small><small>R$ </small></small><small><?php echo fnValor($qrListaVendas['VAL_RESGATE'],2); ?></small></td>
					  <td class="text-center"><small><small>R$ </small></small><small><?php echo fnValor($qrListaVendas['VAL_VENDAS_VINCULADAS'],2); ?></small></td>
					</tr>

				<?php
					
					// $TOTAL_QTD_ATENDIMENTO += $qrListaVendas['QTD_ATENDIMENTO'];
					// $TOTAL_QTD_ATINGIDO += $qrListaVendas['QTD_ATINGIDO'];
					// $TOTAL_VAL_METADES += $qrListaVendas['VAL_METADES'];
					// $TOTAL_QTD_CLIENTE += $qrListaVendas['QTD_CLIENTE'];
					// $TOTAL_VAL_RESGATE += $qrListaVendas['VAL_RESGATE'];
					// $TOTAL_VAL_TOTVENDA += $qrListaVendas['VAL_TOTVENDA'];
					// $TOTAL_VAL_VENDAS_VINCULADAS += $qrListaVendas['VAL_VENDAS_VINCULADAS'];
					
				  $countLinha++;	
				  }		

				?>	
			</tbody>
			
			<tfoot>
				<tr>
					<th colspan="100">
						<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
					</th>
				</tr>														
			</tfoot>													
			
		</table>

<?php

		break;

		case 'vendedor':

?>

		<div class="push20"></div>
												
		<table class="table table-bordered table-hover  ">
		
		  <thead>
			<tr>
			  <th></th>
			  <th><small>Loja</small></th>
			  <th><small>Atendimentos</small></th>
			  <th><small>Realizados</small></th>
			  <th><small>% Meta</small></th>
			  <th><small>Clientes</small></th>
			  <th><small>Tot. Vendas</small></th>
			  <th><small>Val. Resgate</small></th>
			  <th><small>Vendas <br/>Vinculadas</small></th>
			  <!-- <th><small>VVR (%)</small></th> -->
			</tr>
		  </thead>
			
			<?php
				// Filtro por Grupo de Lojas
				include "filtroGrupoLojas.php";
				
				$sql = "SELECT A.COD_UNIVEND, 
						       F.NOM_FANTASI, 
						       Count(*) 
						       AS QTD_ATENDIMENTO, 
						       (SELECT Count(*) QTD_ATENDIMENTO 
						        FROM   DESAFIO_CONTROLE AA 
						               INNER JOIN CLIENTES BB 
						                       ON AA.COD_CLIENTE = BB.COD_CLIENTE 
						                          AND AA.COD_EMPRESA = BB.COD_EMPRESA 
						        WHERE  BB.LOG_AVULSO = 'N' 
						               AND (SELECT FC.DES_COMENT 
						                    FROM   FOLLOW_CLIENTE FC 
						                    WHERE  FC.COD_EMPRESA = A.COD_EMPRESA 
						                           AND FC.COD_CLIENTE = BB.COD_CLIENTE 
						                           AND FC.COD_DESAFIO = AA.COD_DESAFIO 
						                           AND COD_FOLLOW = (SELECT Max(COD_FOLLOW) 
						                                             FROM   FOLLOW_CLIENTE 
						                                             WHERE  COD_CLIENTE = BB.COD_CLIENTE 
						                                                    AND COD_DESAFIO = 
						                                                        AA.COD_DESAFIO)) 
						                   IS NOT 
						                   NULL 
						               AND AA.COD_DESAFIO = A.COD_DESAFIO 
						               AND AA.COD_EMPRESA = A.COD_EMPRESA 
						               AND AA.COD_UNIVEND = A.COD_UNIVEND) 
						       AS QTD_ATINGIDO, 
						       D.VAL_METADES, 
						       IFNULL((SELECT Count(DISTINCT CC.COD_CLIENTE) 
						               FROM   VENDAS CC, 
						                      DESAFIO_CONTROLE DD 
						               WHERE  CC.COD_CLIENTE = DD.COD_CLIENTE 
						                      AND DD.COD_DESAFIO = A.COD_DESAFIO 
						                      AND CC.COD_UNIVEND = A.COD_UNIVEND 
						                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') >= 
						                          D.DAT_INI 
						                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') <= 
						                          D.DAT_FIM), 0) 
						                                      AS QTD_CLIENTE, 
						       IFNULL((SELECT Sum(VAL_TOTVENDA) 
						               FROM   VENDAS CC, 
						                      DESAFIO_CONTROLE DD 
						               WHERE  CC.COD_CLIENTE = DD.COD_CLIENTE 
						                      AND DD.COD_DESAFIO = A.COD_DESAFIO 
						                      AND CC.COD_UNIVEND = A.COD_UNIVEND 
						                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') >= 
						                          D.DAT_INI 
						                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') <= 
						                          D.DAT_FIM), 0) 
						                                      AS VAL_TOTVENDA, 
						       IFNULL((SELECT Sum(VAL_CREDITO) 
						               FROM   CREDITOSDEBITOS DD, 
						                      DESAFIO_CONTROLE EE 
						               WHERE  DD.COD_CLIENTE = EE.COD_CLIENTE 
						                      AND EE.COD_DESAFIO = A.COD_DESAFIO 
						                      AND DD.COD_UNIVEND = A.COD_UNIVEND 
						                      AND DD.TIP_CREDITO = 'D' 
						                      AND DATE_FORMAT(DD.DAT_REPROCE, '%Y-%m-%d') >= D.DAT_INI 
						                      AND DATE_FORMAT(DD.DAT_REPROCE, '%Y-%m-%d') <= D.DAT_FIM), 
						       0) AS 
						       VAL_RESGATE, 
						       IFNULL((SELECT Sum(VAL_TOTVENDA) 
						               FROM   VENDAS EE, 
						                      CREDITOSDEBITOS FF, 
						                      DESAFIO_CONTROLE GG 
						               WHERE  EE.COD_VENDA = FF.COD_VENDA 
						                      AND EE.COD_UNIVEND = A.COD_UNIVEND 
						                      AND FF.COD_CLIENTE = GG.COD_CLIENTE 
						                      AND GG.COD_DESAFIO = A.COD_DESAFIO 
						                      AND FF.TIP_CREDITO = 'D' 
						                      AND DATE_FORMAT(FF.DAT_REPROCE, '%Y-%m-%d') >= D.DAT_INI 
						                      AND DATE_FORMAT(FF.DAT_REPROCE, '%Y-%m-%d') <= D.DAT_FIM), 
						       0) AS 
						       VAL_VENDAS_VINCULADAS, 
						       US.NOM_USUARIO, 
						       US.COD_USUARIO, 
						       (SELECT Count(COD_CONTROLE) AS hitsFeitos 
						        FROM   DESAFIO_CONTROLE AF 
						        WHERE  AF.COD_DESAFIO = A.COD_DESAFIO
						               AND AF.LOG_CONCLUIDO = 'S' 
						               AND AF.COD_VENDEDOR = A.COD_VENDEDOR) 
						       AS HITSVEND 
						FROM   DESAFIO_CONTROLE A 
						       INNER JOIN CLIENTES B 
						               ON A.COD_CLIENTE = B.COD_CLIENTE 
						                  AND A.COD_EMPRESA = B.COD_EMPRESA 
						       LEFT JOIN CATEGORIA_CLIENTE C 
						              ON C.COD_CATEGORIA = B.COD_CATEGORIA 
						       INNER JOIN DESAFIO D 
						               ON A.COD_DESAFIO = D.COD_DESAFIO 
						       INNER JOIN WEBTOOLS.UNIDADEVENDA F 
						               ON F.COD_UNIVEND = A.COD_UNIVEND 
						       INNER JOIN WEBTOOLS.USUARIOS US 
						              ON US.COD_USUARIO = A.COD_VENDEDOR 
						                 AND US.COD_EMPRESA = A.COD_EMPRESA 
						WHERE  B.LOG_AVULSO = 'N' 
						       AND A.COD_DESAFIO = $cod_desafio 
						       AND A.COD_EMPRESA = $cod_empresa 
						GROUP BY A.COD_UNIVEND,A.COD_VENDEDOR 
						ORDER BY A.COD_UNIVEND,B.NOM_CLIENTE ";
				 		
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$countLinha = 1;
				$loja = 0;
				while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
					  if($loja != $qrListaVendas['COD_UNIVEND']){
						  $loja = $qrListaVendas['COD_UNIVEND'];

							?>	
							<thead>
								<tr id="bloco_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">																
								  <th width="3%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?php echo $qrListaVendas['COD_UNIVEND']; ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
								  <th width="10%"><?php echo $qrListaVendas['NOM_FANTASI']; ?></th>
								  <th width="16%" class="text-center"><div id="total_col0_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div></th>
								  <th width="16%" class="text-center"><div id="total_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div></th>
								  <th width="7%" class="text-center"><div  style="display: inline;" id="total_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div>%</th>
								  <th width="16%" class="text-center"><div id="total_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div></th>
								  <th width="16%" class="text-center"><small>R$ </small><div style="display: inline;" id="total_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div></th>
								  <th width="16%" class="text-center"><small>R$ </small><div style="display: inline;" id="total_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div></th>
								  <th width="16%" class="text-center"><small>R$ </small><div style="display: inline;" id="total_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"></div></th>
								</tr>
							</thead>	
							</tbody>	
							<tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
							  <td width="3%"></td>
							  <td width="10%"><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col0_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_ATENDIMENTO'],0); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['HITSVEND'],0); ?></small></td>
							  <td width="7%" class="text-center"><small class="qtde_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_METADES'],2); ?></small>%</td>
							  <td width="16%" class="text-center"><small class="qtde_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_CLIENTE'],0); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_RESGATE'],2); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_VENDAS_VINCULADAS'],2); ?></small></td>
							</tr>
					<?php																	
					  }else{
						?>	
							<tr style="background-color: #fff; display: none;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
							  <td width="3%"></td>
							  <td width="10%"><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col0_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_ATENDIMENTO'],0); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col2_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['HITSVEND'],0); ?></small></td>
							  <td width="7%" class="text-center"><small class="qtde_col3_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_METADES'],2); ?></small>%</td>
							  <td width="16%" class="text-center"><small class="qtde_col4_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['QTD_CLIENTE'],0); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col5_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col6_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_RESGATE'],2); ?></small></td>
							  <td width="16%" class="text-center"><small class="qtde_col7_<?php echo $qrListaVendas['COD_UNIVEND']; ?>"><?php echo fnValor($qrListaVendas['VAL_VENDAS_VINCULADAS'],2); ?></small></td>
							</tr>
						<?php																  
					  }
                                                                               
						$TOTAL_QTD_TOTAVULSA += $qrListaVendas['QTD_TOTAVULSA'];
						$TOTAL_QTD_TOTFIDELIZ += $qrListaVendas['QTD_TOTFIDELIZ'];
						$TOTAL_VAL_TOTVENDA += $qrListaVendas['VAL_TOTVENDA'];
						$TOTAL_VAL_TOTFIDELIZ += $qrListaVendas['VAL_TOTFIDELIZ'];
					
						$countLinha++;	
				  }			
				  
				  ?>
				  
						<<!-- tr>
						  <td></td>
						  <td></td>
						  <td class="text-center"><b><?php echo fnValor($TOTAL_QTD_TOTAVULSA + $TOTAL_QTD_TOTFIDELIZ,0); ?></b></small></td>
						  <td class="text-center"><b><?php echo fnValor($TOTAL_QTD_TOTAVULSA,0); ?></b></small></td>
						  <td class="text-center"><b><?php echo fnValor($TOTAL_QTD_TOTFIDELIZ,0); ?></b></small></td>
						  <td class="text-center"><b><?php echo fnValor($TOTAL_QTD_TOTFIDELIZ / ($TOTAL_QTD_TOTAVULSA + $TOTAL_QTD_TOTFIDELIZ) * 100,2); ?></b></small>%</td>
						  <td class="text-center"><b><small>R$ </small><?php echo fnValor($TOTAL_VAL_TOTVENDA,2); ?></b></td>
						  <td class="text-center"><b><small>R$ </small><?php echo fnValor($TOTAL_VAL_TOTFIDELIZ,2); ?></b></td>
						</tr> -->
				  
				  <?php 

			//fnEscreve($countLinha-1);				
			?>	
			</tbody>
			
			<tfoot>
				<tr>
					<th colspan="100">
						<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
					</th>
				</tr>														
			</tfoot>													
			
		</table>

<?php 

		break;

	}

?>

<script type="text/javascript">
	
	// Carregar totais de quantidade na linhas
	$("div[id^='total_col']").each(function( index ) {
		var total = 0;
		
		// Se não tiver a classe porcent faça
		if(!$(this).hasClass('porcent')){
			$(".qtde_col" + $(this).attr('id').replace('total_col','')).each(function(index, item) {
			  total += limpaValor($(this).text());
			});

			var totalVar = $('#' + $(this).attr('id'));
			totalVar.unmask();
			totalVar.text(total.toFixed(0));				 
			// totalVar.mask("#.##0,00", {reverse: true});	

		}else{
			var numLinha = $(this).attr('id').replace('total_col3_', '');
			var result = limpaValor($('#total_col2_' + numLinha).text()) / (limpaValor($('#total_col1_' + numLinha).text()) + limpaValor($('#total_col2_' + numLinha).text())) * 100;
			var totalVar = $('#' + $(this).attr('id'));
			console.log(totalVar);
			totalVar.unmask();					
			totalVar.text(result.toFixed(2));				 
			totalVar.mask("#.##0,00", {reverse: true});					
		}
	});	

	// $("div[id^='total_col1']").each(function() {
 //        $(this).text($(this).text().slice(0,-3));
	// });
	
	// $("div[id^='total_col2']").each(function() {
	// 	$(this).text($(this).text().slice(0,-3));
	// });

	function abreDetail(idBloco){
		var idItem = $('.abreDetail_' + idBloco)
		if (!idItem.is(':visible')){
			idItem.show();
			$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
		}else{
			idItem.hide();
			$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}

</script>