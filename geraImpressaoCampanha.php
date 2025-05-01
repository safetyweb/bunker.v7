<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	
	$cod_modulo = fnDecode($_GET['mod']);
	//tipo de lançamento	
	switch ($cod_modulo) {
	case 1711: //folha de pagamento
		$tip_lancame = "F";
		$andContabiliza = " ";
		$andAvulso = " ";
		break;
	case 1721: //bonificação
		$tip_lancame = "B";
		$andContabiliza = "AND TIP_CREDITO.LOG_CONTABILIZA = 'N' ";
		$andAvulso = " ";
		break;
	case 1741: //bonificação avulso
		$tip_lancame = "B";
		$andContabiliza = " ";
		$cod_tipo = fnDecode($_GET['idt']);
		$andAvulso = "AND TIP_CREDITO.COD_TIPO  = $cod_tipo ";
		break;
	case 1742: //folha de pagamento avulso
		$tip_lancame = "F";
		$andContabiliza = " ";
		$cod_tipo = fnDecode($_GET['idt']);
		$andAvulso = "AND TIP_CREDITO.COD_TIPO  = $cod_tipo ";
		break;
	}
	
	//echo($andAvulso);

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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_caixa = fnLimpaCampoZero(fnDecode($_GET['idx']));	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, NOM_EMPRESA, NUM_CGCECPF FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$razao_social = $qrBuscaEmpresa['NOM_EMPRESA'];
			$cnpj = $qrBuscaEmpresa['NUM_CGCECPF'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_mes = fnLimpaCampoZero(fnDecode($_GET['idm']));

	if($cod_cliente != 0){

		$sqlCli = "SELECT NOM_CLIENTE, NUM_CGCECPF, LOG_JURIDICO, NUM_RGPESSO, COD_UNIVEND FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
		$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
		$qrCli = mysqli_fetch_assoc($arrayCli);

		$pessoa = "FÍSICA";
		$letraPessoa = "F";

		if($qrCli[LOG_JURIDICO] == "S"){
		    $pessoa = "JURÍDICA";
		    $letraPessoa = "J";
		}

		$nom_cliente = strtoupper($qrCli[NOM_CLIENTE]);
		$num_cgcecpf = $qrCli['NUM_CGCECPF'];
		$num_rgpesso = $qrCli[NUM_RGPESSO];
		$cod_univend = $qrCli[COD_UNIVEND];

	}

	$sqlContrato = "SELECT UV.NOM_FANTASI,
                       	   UV.NUM_CGCECPF AS CNPJ
                FROM UNIDADEVENDA UV 
                WHERE UV.COD_EMPRESA = $cod_empresa 
                AND UV.COD_UNIVEND = $cod_univend";

$arrayCont = mysqli_query(connTemp($cod_empresa,''), $sqlContrato);
$qrContrato = mysqli_fetch_assoc($arrayCont);

$cnpj = $qrContrato['CNPJ'];
$nom_fantasi = $qrContrato['NOM_FANTASI'];

	$sql = "SELECT 	CAIXA.VAL_CREDITO,
					CAIXA.COD_CAIXA,
					CAIXA.DAT_LANCAME,
					CAIXA.COD_CONTRAT,
					CAIXA.NUM_DIA
			FROM CAIXA
			where CAIXA.COD_CLIENTE=$cod_cliente
			AND CAIXA.COD_EMPRESA=$cod_empresa
			AND CAIXA.DAT_EXCLUSA IS NULL
			AND CAIXA.TIP_LANCAME = 'D'
			AND CAIXA.COD_EXCLUSA = 0
			AND CAIXA.COD_CAIXA = $cod_caixa
			ORDER BY CAIXA.DAT_LANCAME DESC";
//fnEscreve($sql);
	//echo($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	
	$count=0;
	$tot_debito = 0;
	$tot_credito = 0;
	$dat_ref = "";

	$cod_tipo = [];
	$des_tipo = [];
	$des_referencia = [];
	$tip_operacao = [];
	$val_credito = [];
	$val_debito = [];

	while ($qrListaCaixa = mysqli_fetch_assoc($arrayQuery)){														  

		if ($dat_ref !=  $qrListaCaixa['DAT_LANCAME'] || $count == 0){

			$dat_ref = $qrListaCaixa['DAT_LANCAME'];
			$dat_lancame = $dat_ref;

		} else {

			$dat_lancame = "";	
			$mes_ref = "";

		}
		
		$tip_operacao = $qrListaCaixa['TIP_OPERACAO'];
		
		if ($tip_operacao == "D") {
			$corTexto = "text-danger";
			$tot_debito += $qrListaCaixa['VAL_CREDITO'];
			array_push($val_credito, '&nbsp;');
			array_push($val_debito, fnValor($qrListaCaixa['VAL_CREDITO'],2));
		} else { 
			$corTexto = ""; 
			$tot_credito += $qrListaCaixa['VAL_CREDITO'];
			array_push($val_credito, fnValor($qrListaCaixa['VAL_CREDITO'],2));
			array_push($val_debito, '&nbsp;');
		}

		$cod_contrat = $qrListaCaixa['COD_CONTRAT'];

		$sqlContrat = "SELECT TIP_CONTRAT 
						 FROM CONTRATO_ELEITORAL
						 WHERE COD_EMPRESA = $cod_empresa
						 AND COD_CONTRAT = $cod_contrat";

		$arrContrat = mysqli_query(connTemp($cod_empresa,''),$sqlContrat);

		$qrContrat = mysqli_fetch_assoc($arrContrat);

		$tip_contrat = $qrContrat[TIP_CONTRAT];

		switch ($tip_contrat) {
			case '2':
				$tipoContrato = "Cabo Eleitoral";
			break;

			case '3':
				$tipoContrato = "Coordenador Cabo Eleitoral";
			break;

			case '4':
				$tipoContrato = "Cessão Serviços";
			break;
			
			case '5':
				$tipoContrato = "Cessão Gratuita de Veículos";
			break;

			default:
				$tipoContrato = "Serviços Gerais";
			break;
		}

		$ref = fnValor($qrListaCaixa['NUM_DIA'],0);

		array_push($des_tipo, "Pagamento pelos serviços de $tipoContrato que prestei para campanha eleitoral nas eleições gerais de 2022.");
		array_push($des_referencia, $ref);
		array_push($cod_tipo, 99);
		// array_push($tip_operacao, $qrListaCaixa['TIP_OPERACAO']);

	}

	$num_mes = date("m",strtotime(fnDataSql($dat_ref)));

	switch ($num_mes) {
        case "01":    $mes_ref = 'JANEIRO';     break;
        case "02":    $mes_ref = 'FEVEREIRO';   break;
        case "03":    $mes_ref = 'MARÇO';       break;
        case "04":    $mes_ref = 'ABRIL';       break;
        case "05":    $mes_ref = 'MAIO';        break;
        case "06":    $mes_ref = 'JUNHO';       break;
        case "07":    $mes_ref = 'JULHO';       break;
        case "08":    $mes_ref = 'AGOSTO';      break;
        case "09":    $mes_ref = 'SETEMBRO';    break;
        case "10":    $mes_ref = 'OUTUBRO';     break;
        case "11":    $mes_ref = 'NOVEMBRO';    break;
        case "12":    $mes_ref = 'DEZEMBRO';    break; 
	 }



	$anoRef = date("Y", strtotime(fnDataSql($dat_ref)));
	// fnEscreve($anoRef);

	$sqlCandidato = "SELECT NUM_CANDIDATO 
					 FROM CANDIDATO
					 WHERE COD_EMPRESA = $cod_empresa
					 AND COD_UNIVEND = $cod_univend";

	$arrCandidato = mysqli_query(connTemp($cod_empresa,''),$sqlCandidato);

	$qrCandidato = mysqli_fetch_assoc($arrCandidato);

	$num_candidato = $qrCandidato[NUM_CANDIDATO];

	
	//fnMostraForm();

?>

<style>
	.f9{
		font-size: 9px;
	}
	.f11{
		font-size: 11px;
	}
	.verticaltext{
	    transform: rotate(-90deg);
    }
    #segundaImpressao{
	  	display: none;
	  }
	 .portlet, .portlet-body, .login-form, .portlet-bordered{
	  	padding: 0;
	  	margin: 0;
	  	border: none!important;
	  }
    @media print {

	  /*body *:not(#primeiraImpressao):not(#segundaImpressao) {
	    display: none;
	  }*/

	  body{
	  	background: #000;
	  	padding: 0;
	  	margin: 0;
	  	page-break-after: avoid;
        page-break-before: avoid;
	  }

	  .modal-content{
	  	width: 100%;
	  	height: 100%;
	  }

	  .navbar-fixed-left{
	  	display: none;
	  }

	  .portlet, .portlet-body, .login-form, .portlet-bordered,.containerfluid,.outContainerPop{
	  	padding: 0;
	  	margin: 0;
	  	border: none!important;
	  	page-break-after: avoid;
        page-break-before: avoid;
	  }

	  #segundaImpressao{
	  	display: block;
	  }

	  #primeiraImpressao, #segundaImpressao{
	  	page-break-after: avoid;
        page-break-before: avoid;
	  }

	}
</style>
			
<div class="row" style="height: 530px;" id="primeiraImpressao">

	<div class="col-xs-10" style="height: 530px;">

		<table class="table table-bordered" style="height: 180px;">

			<tbody>

				<tr>

					<td>

					  	<div class="row">

					  		<div class="col-xs-6">

					  			<div class="push10"></div>

					  			<!-- <div class="col-xs-6">
					  				<p class="f10"><b>Mês de Referência:</b></p>
					  			</div>

					  			<div class="col-xs-6">
					  				<p class="f10"><?=$mes_ref."/".$anoRef?></p>
					  			</div> -->

					  			<div class="col-xs-12">
					  				<p class="f10"><?=$nom_fantasi?> (<?=fnValor($num_candidato,0)?>)</p>
					  				<p class="f10">CNPJ <?=fnCompletaDoc($cnpj,"J")?></p>
					  			</div>

					  		</div>

					  		<div class="col-xs-6 text-center">
					  			<p class="f10"><b>RECIBO DE PAGAMENTO DE PRESTAÇÃO DE SERVIÇOS</b></p>
					  			<p class="f10">(Res. TSE 23.607/2019, art.60, § 2°)</p>
					  		</div>

					  	</div>

				  	</td>

				</tr>

				<tr>
					
					<td>

					  	<div class="row">

					  		<div class="col-xs-1">
					  			<p class="f9">
					  				Código
					  			</p>
					  		</div>

					  		<div class="col-xs-5">
					  			<p class="f9">
					  				Nome do Prestador
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f9">
					  				RG
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f9">
					  				CPF/CNPJ
					  			</p>
					  		</div>					  		

					  	</div>

					  	<div class="row">

					  		<div class="col-xs-1">
					  			<p class="f11">
					  				<?=$cod_cliente?>
					  			</p>
					  		</div>

					  		<div class="col-xs-5">
					  			<p class="f11">
					  				<?=$nom_cliente?>
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f11">
					  				<?=$num_regpesso?>
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f11">
					  				<span class="cpfcnpj"><?=fnCompletaDoc($num_cgcecpf,"$letraPessoa")?></span>
					  			</p>
					  		</div>					  		

					  	</div>

				  	</td>

				</tr>

			</tbody>

		</table>

		<!-- <div class="push5"></div> -->

		<table class="table table-bordered" style="height: 327px;">

			<thead>
				
				<tr>

					<!-- <td>
						
						<div class="row"> -->
							
							<th class="col-xs-1 f9">Cód.</th>
							<th class="col-xs-4 f9">Descrição</th>
							<th class="col-xs-1 f9 text-center">Referência</th>
							<th class="col-xs-3 f9 text-right">Vencimentos</th>
							<th class="col-xs-3 f9 text-right">Descontos</th>

						<!-- /div>

					</td> -->

					
				</tr>

			</thead>

			<tbody>

				<tr style="height: 180px;">

					<td>
						<div class="row">
							<?php 
								for ($i=0; $i < count($cod_tipo); $i++) { 
							?>
									<div class="col-xs-12 f10"><?=$cod_tipo[$i]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($i=0; $i < count($des_tipo); $i++) { 
							?>
									<div class="col-xs-12 f10"><?=$des_tipo[$i]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($k=0; $k < count($des_tipo); $k++) { 
							?>
									<div class="col-xs-12 f10"><?=$des_referencia[$k]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($j=0; $j < count($val_credito); $j++) { 
							?>
									<div class="col-xs-12 f10 text-right"><?=$val_credito[$j]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($k=0; $k < count($val_debito); $k++) { 
							?>
									<div class="col-xs-12 f10 text-right"><?=$val_debito[$k]?></div>
							<?php 
								}
							?>
						</div>
					</td>

				</tr>

				<tr>
					
					<td colspan="3" rowspan="2"></td>

					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right">Total de Vencimentos</div>
							<div class="col-xs-12 f10 text-right"><?=fnValor($tot_credito,2)?></div>
						</div>
					</td>
					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right">Total de Descontos</div>
							<div class="col-xs-12 f10 text-right"><?=fnValor($tot_debito,2)?></div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right"></div>
							<div class="col-xs-12 f10 text-right"></div>
						</div>
					</td>
					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right">Valor Líquido</div>
							<div class="col-xs-12 f10 text-right"><?=fnValor(($tot_credito-$tot_debito),2)?></div>
						</div>
					</td>
				</tr>

			</tbody>

		</table>

	</div>

	<div class="col-xs-2 text-center" style="height: 530px; margin-left: -15px; border: 1px solid #ecf0f1;">
		<p class="f10 verticaltext" style="position: absolute; width: 530px; height: 100px; top: 220px; left: -200px;">
			DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMINADA NESTE RECIBO
			<br>
			DANDO GERAL E IRREVOGÁVEL QUITAÇÃO													
		</p>

		<span class="f10 verticaltext" style="position: absolute; width: 120px; height: 100px; top: 380px; left: 60;">
			_______/_______/______________<br/>
			DATA
																
		</span>

		<span class="f10 verticaltext" style="position: absolute; width: 240px; height: 100px; top: 130px; left: 0;">
			____________________________________________________________<br/>
			ASSINATURA DO PRESTADOR
															
		</span>

	</div>
	
</div>

<hr style="padding: 0; margin-top: 7.5px; margin-bottom: 7.5px;">

<div class="row" style="height: 530px;" id="segundaImpressao" style="display: none; margin-bottom: -50px;">

	<div class="col-xs-10" style="height: 530px;">

		<table class="table table-bordered" style="height: 180px;">

			<tbody>

				<tr>

					<td>

					  	<div class="row">

					  		<div class="col-xs-6">

					  			<div class="push10"></div>

					  			<!-- <div class="col-xs-6">
					  				<p class="f10"><b>Mês de Referência:</b></p>
					  			</div>

					  			<div class="col-xs-6">
					  				<p class="f10"><?=$mes_ref."/".$anoRef?></p>
					  			</div> -->

					  			<div class="col-xs-12">
					  				<p class="f10"><?=$nom_fantasi?></p>
					  				<p class="f10">CNPJ <?=fnCompletaDoc($cnpj,"J")?></p>
					  			</div>

					  		</div>

					  		<div class="col-xs-6 text-center">
					  			<p class="f10"><b>RECIBO DE PAGAMENTO DE PRESTAÇÃO DE SERVIÇOS</b></p>
					  			<p class="f10">(Res. TSE 23.607/2019, art.60, § 2°)</p>
					  		</div>

					  	</div>

				  	</td>

				</tr>

				<tr>
					
					<td>

					  	<div class="row">

					  		<div class="col-xs-1">
					  			<p class="f9">
					  				Código
					  			</p>
					  		</div>

					  		<div class="col-xs-5">
					  			<p class="f9">
					  				Nome do Prestador
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f9">
					  				RG
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f9">
					  				CPF/CNPJ
					  			</p>
					  		</div>					  		

					  	</div>

					  	<div class="row">

					  		<div class="col-xs-1">
					  			<p class="f11">
					  				<?=$cod_cliente?>
					  			</p>
					  		</div>

					  		<div class="col-xs-5">
					  			<p class="f11">
					  				<?=$nom_cliente?>
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f11">
					  				<?=$num_regpesso?>
					  			</p>
					  		</div>

					  		<div class="col-xs-3">
					  			<p class="f11">
					  				<span class="cpfcnpj"><?=fnCompletaDoc($num_cgcecpf,"$letraPessoa")?></span>
					  			</p>
					  		</div>					  		

					  	</div>

				  	</td>

				</tr>

			</tbody>

		</table>

		<!-- <div class="push5"></div> -->

		<table class="table table-bordered" style="height: 327px;">

			<thead>
				
				<tr>

					<!-- <td>
						
						<div class="row"> -->
							
							<th class="col-xs-1 f9">Cód.</th>
							<th class="col-xs-4 f9">Descrição</th>
							<th class="col-xs-1 f9 text-center">Referência</th>
							<th class="col-xs-3 f9 text-right">Vencimentos</th>
							<th class="col-xs-3 f9 text-right">Descontos</th>

						<!-- /div>

					</td> -->

					
				</tr>

			</thead>

			<tbody>

				<tr style="height: 180px;">

					<td>
						<div class="row">
							<?php 
								for ($i=0; $i < count($cod_tipo); $i++) { 
							?>
									<div class="col-xs-12 f10"><?=$cod_tipo[$i]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($i=0; $i < count($des_tipo); $i++) { 
							?>
									<div class="col-xs-12 f10"><?=$des_tipo[$i]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($k=0; $k < count($des_tipo); $k++) { 
							?>
									<div class="col-xs-12 f10"><?=$des_referencia[$k]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($j=0; $j < count($val_credito); $j++) { 
							?>
									<div class="col-xs-12 f10 text-right"><?=$val_credito[$j]?></div>
							<?php 
								}
							?>
						</div>
					</td>

					<td>
						<div class="row">
							<?php 
								for ($k=0; $k < count($val_debito); $k++) { 
							?>
									<div class="col-xs-12 f10 text-right"><?=$val_debito[$k]?></div>
							<?php 
								}
							?>
						</div>
					</td>

				</tr>

				<tr>
					
					<td colspan="3" rowspan="2"></td>

					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right">Total de Vencimentos</div>
							<div class="col-xs-12 f10 text-right"><?=fnValor($tot_credito,2)?></div>
						</div>
					</td>
					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right">Total de Descontos</div>
							<div class="col-xs-12 f10 text-right"><?=fnValor($tot_debito,2)?></div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right"></div>
							<div class="col-xs-12 f10 text-right"></div>
						</div>
					</td>
					<td>
						<div class="row">
							<div class="col-xs-12 f9 text-right">Valor Líquido</div>
							<div class="col-xs-12 f10 text-right"><?=fnValor(($tot_credito-$tot_debito),2)?></div>
						</div>
					</td>
				</tr>

			</tbody>

		</table>

	</div>

	<div class="col-xs-2 text-center" style="height: 530px; margin-left: -15px; border: 1px solid #ecf0f1;">
		<p class="f10 verticaltext" style="position: absolute; width: 530px; height: 100px; top: 220px; left: -200px;">
			DECLARO TER RECEBIDO A IMPORTÂNCIA LÍQUIDA DISCRIMINADA NESTE RECIBO
			<br>
			DANDO GERAL E IRREVOGÁVEL QUITAÇÃO													
		</p>

		<span class="f10 verticaltext" style="position: absolute; width: 120px; height: 100px; top: 380px; left: 60;">
			_______/_______/______________<br/>
			DATA
																
		</span>

		<span class="f10 verticaltext" style="position: absolute; width: 240px; height: 100px; top: 130px; left: 0;">
			____________________________________________________________<br/>
			ASSINATURA DO PRESTADOR
															
		</span>

	</div>
	
</div>
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
