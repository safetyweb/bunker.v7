<?php 

	include '_system/_functionsMain.php'; 	

	// echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];

	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$cod_externo = $_POST['COD_EXTERNO'];

	$nom_chamado = $_POST['NOM_CHAMADO'];

	$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
	$cod_status = $_POST['COD_STATUS'];
	$lojasSelecionadas = fnLimpaCampo($_POST['LOJAS']);
	//fnEscreve($cod_status);

	$hoje = fnFormatDate(date("Y-m-d"));
	

			if($dat_ini == date('Y-m-d')){$datIniAND = " ";}else{$datIniAND = "DATE_FORMAT(SC.DAT_CHAMADO, '%Y-%m-%d') >= '$dat_ini' AND ";}

			if($dat_fim == date('Y-m-d')){$dat_fim = fnDataSql($hoje);}

			if($cod_externo == ""){$ANDcodExterno = " ";}else{$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";}

			if($nom_chamado == ""){$ANDnomChamado = " ";}else{$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";}

			if($cod_tpsolicitacao == ""){$ANDcodTipo = " ";}else{$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";}

			if($cod_status == ""){$ANDcodStatus = " ";}else{$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";}

			if($cod_integradora == ""){$ANDcodIntegradora = " ";}else{$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";}

			if($cod_plataforma == ""){$ANDcodPlataforma = " ";}else{$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";}

			if($cod_versaointegra == ""){$ANDcodVersaointegra = " ";}else{$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";}

			if($cod_prioridade == ""){$ANDcodPrioridade = " ";}else{$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";}

		
			$sqlCount = "SELECT COUNT(*) AS CONTADOR FROM SAC_CHAMADOS SC 
						WHERE
						$datIniAND
		  				DATE_FORMAT(SC.DAT_CHAMADO, '%Y-%m-%d') <= '$dat_fim'
		  				$ANDcodExterno
		  				$ANDnomChamado
		  				$ANDcodStatus
		  				$ANDcodTipo
		  				$ANDcodIntegradora
		  				$ANDcodPlataforma
		  				$ANDcodVersaointegra
		  				$ANDcodPrioridade
		  				AND SC.COD_EMPRESA = $cod_empresa 
		  				AND SC.COD_UNIVEND IN($lojasSelecionadas)
						ORDER BY SC.DAT_CADASTR DESC
						";
			//fnEscreve($sqlSac);
			
			$retorno = mysqli_query($connAdmSAC->connAdm(),$sqlCount) or die(mysqli_error());
			$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
			
			$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);	

			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
		
			$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.LOG_INTERAC,
						SC.COD_EXTERNO,	SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.COD_USUARIO, SC.DAT_ENTREGA,
						ST.DES_TPSOLICITACAO, SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS
						FROM SAC_CHAMADOS SC 
						LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
						LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
						WHERE 
						$datIniAND
						DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
						$ANDcodExterno
						$ANDnomChamado
						$ANDcodStatus
						$ANDcodTipo
						$ANDcodIntegradora
						$ANDcodPlataforma
						$ANDcodVersaointegra
						$ANDcodPrioridade
						AND SC.COD_EMPRESA = $cod_empresa 
						-- AND SC.COD_UNIVEND IN($lojasSelecionadas)
						AND (
						      FIND_IN_SET(SUBSTRING_INDEX(SC.COD_UNIVEND, ',', 1), '$lojasSelecionadas') > 0 OR
						      FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(SC.COD_UNIVEND, ',', -1), ',', 1), '$lojasSelecionadas') > 0 OR
						      FIND_IN_SET('$lojasSelecionadas', SC.COD_UNIVEND) > 0  
						)
						ORDER BY SC.COD_CHAMADO DESC limit $inicio,$itens_por_pagina
						";

			// fnEscreve2($sqlSac);

			$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(),$sqlSac) or die(mysqli_error());
			
			$count=0;
			while ($qrSac = mysqli_fetch_assoc($arrayQuerySac))
			 {														  
				$count++;

				if($qrSac['DAT_ENTREGA'] == "1969-12-31"){
					$entrega = "";
				}else{
					$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
					if(fnDatasql($entrega) < fnDatasql($hoje)){
						$entrega = "<span class='text-danger'><b>".fnDataShort($qrSac['DAT_ENTREGA'])."</b></span>";
					}
				}

				if($qrSac['COD_USUARIO'] != ''){
					$selectSolicitante = "(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE";
				}else{
					$selectSolicitante = "('') AS NOM_SOLICITANTE";
				}

				if($qrSac['COD_USURES'] != ''){
					$selectRespons = "(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
				}else{
					$selectRespons = "('') AS NOM_RESPONSAVEL";
				}

				$sqlUsuarios = "SELECT $selectSolicitante,$selectRespons";
				//fnEscreve($sqlUsuarios);
				$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsuarios));

				if($qrSac['LOG_INTERAC']=='S'){
					$cor_interac = "background: #FCF3CF";
				}else{
					$cor_interac = "";
				}
																  
			?>

			<tr style="<?=$cor_interac?>">
			  <td class="text-center">
			  	<small>
			  		<a href="action.php?mod=<?=fnEncode(1288);?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']);?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank"><?=$qrSac['COD_CHAMADO'] ?>&nbsp; 
			  			<span class="fa fa-external-link-square"></span>
			  		</a>
			  	</small>
			  </td>
			  <td><small><?php echo $qrSac['NOM_CHAMADO']; ?></small></td>
			  <td class="text-center"><small><?php echo fnDataShort($qrSac['DAT_CADASTR']);; ?></small></td>
			  <td><small><?php echo $qrNomUsu['NOM_SOLICITANTE']; ?></small></td>
			  <td><small><?php echo $qrSac['DES_TPSOLICITACAO']; ?></small></td>
			  <td class="text-center f14"><small><?=$entrega?></small></td>
			  <td class="text-center">
			  	<small>
			  		<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>"> 
			  			<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
			  			&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
			  		</p>
			  	</small>
			  </td>

			</tr>
		    <?php
			}						
	?>