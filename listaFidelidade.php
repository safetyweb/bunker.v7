<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_servidor = "";
$des_servidor = "";
$des_abrevia = "";
$des_geral = "";
$cod_operacional = "";
$des_observa = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_segmentEmp = "";
$codEmpresa = "";
$qrPfl = "";
$sqlAut = "";
$qrAut = "";
$modsAutorizados = "";
$abaPersona = "";
$abaCampanha = "";
$abaVantagem = "";
$abaRegras = "";
$abaComunica = "";
$abaAtivacao = "";
$abaResultado = "";
$abaPersonaComp = "";
$abaCampanhaComp = "";
$abaVantagemComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaResultadoComp = "";
$abaAtivacaoComp = "";
$val_pesquisa = "";
$esconde = "";
$formBack = "";
$abaCampanhas = "";
$qrLista = "";
$sql2 = "";
$arrayQuery2 = [];
$qrLista2 = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$ARRAY_VENDEDOR1 = [];
$ARRAY_VENDEDOR = [];
$arrayAutorizado = [];
$CarregaMaster = "";
$qrListaPersonas = "";
$publico = "";
$NOM_ARRAY_NON_VENDEDOR = [];
$personaaAtivo = "";
$personaCongela = "";
$personaaAtualiza = "";
$lojaLoop = "";
$nomeLoja = "";
$NOM_ARRAY_UNIDADE = [];
$usuario = "";
$qrListaCampanha = "";
$filtro = "";


//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_servidor = fnLimpaCampoZero(@$_REQUEST['COD_SERVIDOR']);
		$des_servidor = fnLimpaCampo(@$_POST['DES_SERVIDOR']);
		$des_abrevia = fnLimpaCampo(@$_POST['DES_ABREVIA']);
		$des_geral = fnLimpaCampo(@$_POST['DES_GERAL']);
		$cod_operacional = fnLimpaCampoZero(@$_POST['COD_OPERACIONAL']);
		$des_observa = fnLimpaCampo(@$_POST['DES_OBSERVA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
	}
} else {
	$cod_empresa = 0;
	//$codEmpresa = $qrBuscaEmpresa['COD_SISTEMA'];

}

//Busca módulos autorizados
$sql = "SELECT COD_PERFILS FROM usuarios WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrPfl = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));

$sqlAut = "SELECT COD_MODULOS FROM perfil WHERE
			   COD_SISTEMA = 4 
			   AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
$qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlAut));

$modsAutorizados = explode(",", $qrAut['COD_MODULOS']);

//liberação das abas
$abaPersona	= "S";
$abaCampanha = "S";
$abaVantagem = "N";
$abaRegras = "N";
$abaComunica = "N";
$abaAtivacao = "N";
$abaResultado = "N";

$abaPersonaComp = "active ";
$abaCampanhaComp = "";
$abaVantagemComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaResultadoComp = "";

//revalidada na aba de regras	
$abaAtivacaoComp = "";

// esquema do X da barra - (recarregar pesquisa)
if ($val_pesquisa != '' && $val_pesquisa != 0) {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}


//fnMostraForm();
// fnEscreve(fnDecode("6H5vf3npkWw¢"));
//fnEscreve($_SESSION["SYS_COD_TPUSUARIO"]);
//fnEscreve($_SESSION["SYS_COD_UNIVEND"]);




?>

<style>
	.fa-1dot5x {
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
	}

	.tile {
		border: none !important;
	}
</style>

<link rel="stylesheet" href="css/widgets.css" />

<div class="push30"></div>

<!-- Portlet -->
<div class="portlet portlet-bordered">

	<div class="portlet-title">
		<div class="caption">
			<i class="far fa-terminal"></i>
			<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
		</div>

		<?php
		$formBack = "1048";
		include "atalhosPortlet.php"; ?>

	</div>

	<div class="push10"></div>

	<div class="portlet-body">

		<?php if ($msgRetorno <> '') { ?>
			<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php echo $msgRetorno; ?>
			</div>
		<?php } ?>


		<div class="row">

			<div class="col-md-12">

				<?php $abaCampanhas = 1049;
				include "abasCampanhasConfig.php"; ?>

			</div>

		</div>

		<!--
							<div class="push10"></div>	
							
							<div class="row">
							
								<div class="col-md-12">
								
									<div class="alert alert-warning" role="alert">
										<h3 class="bg-warning " style="margin:10px 0 10px 0;">Como vamos melhorar os <strong>resultados</strong> do seu negócio <strong>hoje?</strong> </h3>
									</div>										
								
								</div>
								
							</div>
							-->

		<div class="push20"></div>

		<div class="row">

			<h3 style="margin: 0 0 20px 15px;">Qual tipo de comportamento você quer <strong>incentivar</strong>?</h3>

			<?php
			//segmentos
			$sql = "select * from SEGMENTOMARKA where COD_SEGMENT = '" . $cod_segmentEmp . "'  order by NUM_ORDENAC";
			$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
			//echo $sql;
			$count = 1;
			while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

				//itens do segmento
				$sql2 = "select * from SEGMARKAITEM where COD_SEGMENT = '" . $qrLista['COD_SEGMENT'] . "' order by NUM_ORDENAC";
				$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);

				while ($qrLista2 = mysqli_fetch_assoc($arrayQuery2)) {
			?>
					<div class="col-md-2">
						<!--<a href="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa); ?>&pre=<?php echo fnEncode($qrLista2['COD_SEGITEM']); ?>" class="tile tile-default shadow" style="color: #2c3e50;">-->
						<a href="#" class="tile tile-default shadow addBox" style="color: #2c3e50;" data-url="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Persona / <?php echo $nom_empresa; ?>">

							<span style="margin: 20px 0 0 0;" class="<?php echo $qrLista2['DES_ICONE']; ?>"></span>
							<p style="height: 50px;"><?php echo $qrLista2['NOM_SEGITEM']; ?></p>
							<!-- <div class="informer informer-default" style="color: #2c3e50;"><span class="fas <?php echo $qrLista['DES_ICONE']; ?>"></span></div> -->
						</a>
					</div>
			<?php
				}
				echo "<div class='push10'></div>";
				$count++;
			}

			?>

		</div>

		<div class="push30"></div>

		<div class="row">

			<h3 style="margin: 0 0 20px 15px;">Incentive seus clientes ao criar ou editar <strong>Personas</strong></h3>

			<div class="col-md-3">

				<a class="btn btn-info btn-block addBox" data-url="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Persona / <?php echo $nom_empresa; ?>"><i class="fas fa-plus" aria-hidden="true" style="margin: 5px 0 5px 0;"></i> Criar Nova Persona</a>

			</div>

			<div class="push20"></div>

			<!-- <a name="campanha"/> -->

			<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
			<div class="push30"></div>

			<div class="row">
				<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

					<div class="col-xs-4 col-xs-offset-4">
						<div class="input-group activeItem">
							<div class="input-group-btn search-panel">
								<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
									<span id="search_concept">Sem filtro</span>&nbsp;
									<span class="far fa-angle-down"></span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<li class="divisor"><a href="#">Sem filtro</a></li>
									<!-- <li class="divider"></li> -->
									<!--  <li><a href="#NOM_EMPRESA">Razão social</a></li>
									                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
									                    <li><a href="#NUM_CGCECPF">CNPJ</a></li> -->
								</ul>
							</div>
							<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
							<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
							<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
								<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
							</div>
							<div class="input-group-btn">
								<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
							</div>
						</div>
					</div>

					<input type="hidden" name="modsAutorizados" id="modsAutorizados" value="<?php echo implode(',', $modsAutorizados); ?>" />
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

				</form>

			</div>

			<div class="push30"></div>

			<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->



			<div class="col-md-2">
				<div class="form-group">
					<label for="inputName" class="control-label">Somente minhas personas</label>
					<div class="push5"></div>
					<label class="switch">
						<input type="checkbox" name="LOG_PERSONASUSU" id="LOG_PERSONASUSU" class="switch" value="S" onchange='RefreshPersona("<?= fnEncode($cod_empresa) ?>", "", 0)'>
						<span></span>
					</label>
				</div>
			</div>



			<div class="col-md-12">

				<table class="table table-bordered table-striped table-hover tablesorter buscavel">
					<thead>
						<tr>
							<th class="{sorter:false}"></th>
							<th>Nome da Persona</th>
							<th class="text-center"><i class='fas fa-users'></i></th>
							<th class="text-center">Unidade</th>
							<th class="text-center">Usuário Cad.</th>
							<th class="text-center {sorter:false}">Ativa</th>
							<th class="text-center {sorter:false}">Bloqueada</th>
							<th>Data de Criação</th>
							<th>Última Alteração</th>
							<th class="{sorter:false}"></th>
							<!-- <th class="{sorter:false}"></th> -->
						</tr>
					</thead>

					<tbody id="div_refreshPersona">

						<?php

						$ARRAY_UNIDADE1 = array(
							'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
							'cod_empresa' => $cod_empresa,
							'conntadm' => $connAdm->connAdm(),
							'IN' => 'N',
							'nomecampo' => '',
							'conntemp' => '',
							'SQLIN' => ""
						);
						$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

						$ARRAY_VENDEDOR1 = array(
							'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa in($cod_empresa,3)",
							'cod_empresa' => $cod_empresa,
							'conntadm' => $connAdm->connAdm(),
							'IN' => 'N',
							'nomecampo' => '',
							'conntemp' => '',
							'SQLIN' => ""
						);
						$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);
						$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

						$sql = "CALL SP_BUSCA_PERSONA($cod_empresa, 'S');";

						// fnEscreve($sql);
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


						if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
							$CarregaMaster = '1';
						} else {
							$CarregaMaster = '0';
						}


						$count = 0;
						while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

							$count++;
							$publico = $qrListaPersonas['LOG_PUBLICO'];

							$NOM_ARRAY_NON_VENDEDOR = (array_search($qrListaPersonas['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
							if ("S" == "S") {
								$personaaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";
							} else {
								$personaaAtivo = "";
							}

							if ($qrListaPersonas['LOG_CONGELA'] == "S") {
								$personaCongela = "<i class='far fa-pause-circle' aria-hidden='true'></i>";
							} else {
								$personaCongela = "";
							}

							if ($qrListaPersonas['LOG_RESTRITO'] == "S") {
								$personaaAtualiza = "<i class='fal fa-check' aria-hidden='true'></i>";
							} else {
								$personaaAtualiza = "";
							}
							// fnEscreve($qrListaPersonas['LOG_RESTRITO']);
							//echo fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"],$_SESSION["SYS_COD_EMPRESA"]);
							//$qrListaPersonas['COD_UNIVED']

							$lojaLoop = $qrListaPersonas['COD_UNIVEND'];
							if ($lojaLoop == 9999 || $lojaLoop == 0) {
								$nomeLoja = "Todas";
							} else {
								$NOM_ARRAY_UNIDADE = (array_search($qrListaPersonas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
								$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
							}

							if ($qrListaPersonas['COD_USUCADA'] == 0 || $qrListaPersonas['COD_USUCADA'] == '') {
								$usuario = "";
							} else {
								$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
							}

							if ($CarregaMaster == '1' || $publico == 'S') {

								if ($publico == 'S') {
									$nomeLoja = "Pública";
								}


						?>
								<tr>
									<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
									<td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
									<td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'], 0); ?></td>
									<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
									<td class="text-center"><small><?php echo $usuario; ?></small></td>
									<td class='text-center'><?php echo $personaaAtivo; ?></td>
									<td class='text-center'><?php echo $personaaAtualiza . "&nbsp;" . $personaCongela; ?></td>
									<td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
									<td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
									<?php if (fnControlaAcesso("1600", $modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
										<td></td>
									<?php } else { ?>
										<td class="text-center">
											<small>
												<div class="btn-group dropdown dropleft">
													<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														ações &nbsp;
														<span class="fas fa-caret-down"></span>
													</button>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
														<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar </a></li>
														<li><a href="action.do?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>">Acessar </a></li>
														<?php if ($_SESSION["SYS_COD_EMPRESA"] == 2 || $_SESSION["SYS_COD_EMPRESA"] == 3) { ?>
															<li><a href="javascript:void(0)" onclick="exportar('<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>')">Exportar </a></li>
														<?php } ?>
														<li class="divider"></li>
														<li><a href="javascript:void(0)" onclick='RefreshPersona("<?= fnEncode($cod_empresa) ?>","","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>")'>Desativar </a></li>
														<li><a href="javascript:void(0)" onclick='excluiPersona("<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>","<?= $qrListaPersonas['qtd_campanha'] ?>","<?= $qrListaPersonas['DES_PERSONA'] ?>")'>Excluir </a></li>
														<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
													</ul>
												</div>
											</small>
										</td>
									<?php } ?>
								</tr>
								<?php
							} else {


								if (recursive_array_search($qrListaPersonas['COD_UNIVEND'], $arrayAutorizado) !== false) {
								?>
									<tr>
										<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
										<td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
										<td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'], 0); ?></td>
										<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
										<td class="text-center"><small><?php echo $usuario; ?></small></td>
										<td class='text-center'><?php echo $personaaAtivo; ?></td>
										<td class='text-center'><?php echo $personaaAtualiza . "&nbsp;" . $personaCongela; ?></td>
										<td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
										<td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
										<?php if (fnControlaAcesso("1600", $modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
											<td></td>
										<?php } else { ?>
											<td class="text-center">
												<small>
													<div class="btn-group dropdown dropleft">
														<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															ações &nbsp;
															<span class="fas fa-caret-down"></span>
														</button>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar </a></li>
															<li><a href="action.do?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>">Acessar </a></li>
															<li class="divider"></li>
															<li><a href="javascript:void(0)" onclick='RefreshPersona("<?= fnEncode($cod_empresa) ?>","","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>")'>Desativar </a></li>
															<li><a href="javascript:void(0)" onclick='excluiPersona("<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>","<?= $qrListaPersonas['qtd_campanha'] ?>","<?= $qrListaPersonas['DES_PERSONA'] ?>")'>Excluir </a></li>
															<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
														</ul>
													</div>
												</small>
											</td>
										<?php } ?>
									</tr>
						<?php
								}
							}
						}

						?>

					</tbody>
				</table>

			</div>

			<div class="push30"></div>


		</div>

		<div class="row">



			<div class="push"></div>

			<div class="panel-group" id="accordion">

				<div><!-- div controle do acordion -->

					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
								<button class="btn btn-sm btn-default" onClick="RefreshPersona('<?php echo fnEncode($cod_empresa) ?>','Arquivadas', 0);"><i class="fas fa-archive"></i> Personas Arquivadas</button>
							</a>
						</h4>
					</div>
					<div id="collapse1" class="panel-collapse collapse">

						<div class="panel-body">

							<div class="row">

								<table class="table table-bordered table-striped table-hover tablesorter">
									<thead>
										<tr>
											<th class="{sorter:false}"></th>
											<th>Nome da Persona</th>
											<th class="text-center"><i class='fas fa-users'></i></th>
											<th class="text-center">Unidade</th>
											<th class="text-center {sorter:false}">Ativa</th>
											<th class="text-center {sorter:false}">Bloqueada</th>
											<th>Data de Criação</th>
											<th>Última Alteração</th>
											<th class="{sorter:false}"></th>
											<th class="{sorter:false}"></th>
										</tr>
									</thead>

									<tbody id="div_refreshPersonaArquivadas">
									</tbody>
								</table>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div><!-- fim div controle ajax persona -->

	</div>

	<div class="push30"></div>

</div>

</div><!-- fim Portlet body -->

</div><!-- fim Portlet  -->

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>

<form id="formModal">
	<input type="hidden" class="input-sm" name="REFRESH_CAMPANHA" id="REFRESH_CAMPANHA" value="N">
	<input type="hidden" class="input-sm" name="REFRESH_PERSONA" id="REFRESH_PERSONA" value="N">
</form>

<script type="text/javascript">
	//Barra de pesquisa essentials ------------------------------------------------------
	$(document).ready(function(e) {
		var value = $('#INPUT').val().toLowerCase().trim();
		if (value) {
			$('#CLEARDIV').show();
		} else {
			$('#CLEARDIV').hide();
		}
		$('.search-panel .dropdown-menu').find('a').click(function(e) {
			e.preventDefault();
			var param = $(this).attr("href").replace("#", "");
			var concept = $(this).text();
			$('.search-panel span#search_concept').text(concept);
			$('.input-group #VAL_PESQUISA').val(param);
			$('#INPUT').focus();
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		});

		$('#CLEAR').click(function() {
			$('#INPUT').val('');
			$('#INPUT').focus();
			$('#CLEARDIV').hide();
			if ("<?= $filtro ?>" != "") {
				location.reload();
			} else {
				var value = $('#INPUT').val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel tr").each(function(index) {
					if (!index) return;
					$(this).find("td").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('tr').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		});

		// $('#SEARCH').click(function(){
		// 	$('#formulario').submit();
		// });


	});

	function buscaRegistro(el) {
		var filtro = $('#search_concept').text().toLowerCase();

		if (filtro == "sem filtro") {
			var value = $(el).val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$(".buscavel tr").each(function(index) {
				if (!index) return;
				$(this).find("td").each(function() {
					var id = $(this).text().toLowerCase().trim();
					var sem_registro = (id.indexOf(value) == -1);
					$(this).closest('tr').toggle(!sem_registro);
					return sem_registro;
				});
			});
		}
	}

	//-----------------------------------------------------------------------------------

	$(document).ready(function() {

		$(".addBox").click(function() {
			$('#popModal').find('.modal-content').css({
				'width': 'auto',
				'height': 'auto',
				'marginLeft': 'auto',
				'marginRight': 'auto'
			});
			$('#popModal').find('.modal-dialog').css({
				'maxWidth': '1080px',
				'margin': 'auto'
			});
		});

		//modal close
		$('#popModal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_PERSONA').val() == "S") {
				//alert("atualiza");
				RefreshPersona("<?= fnEncode($cod_empresa) ?>", '', 0);
				RefreshPersona("<?= fnEncode($cod_empresa) ?>", 'Arquivadas', 0);
				$('#REFRESH_PERSONA').val("N");
			}

			if ($('#REFRESH_CAMPANHA').val() == "S") {
				//alert("atualiza");
				RefreshCampanha("<?php echo fnEncode($cod_empresa) ?>");
				$('#REFRESH_CAMPANHA').val("N");
			}

		});

	});

	function exportar(codPersona) {
		$.confirm({
			title: 'Exportação',
			content: '' +
				'<form action="" class="formName">' +
				'<div class="form-group">' +
				'<label>Insira o nome do arquivo:</label>' +
				'<input type="text" placeholder="Nome" class="nome form-control" required />' +
				'</div>' +
				'</form>',
			buttons: {
				formSubmit: {
					text: 'Gerar',
					btnClass: 'btn-blue',
					action: function() {
						var nome = this.$content.find('.nome').val();
						if (!nome) {
							$.alert('Por favor, insira um nome');
							return false;
						}
						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxListaPersonasClientes.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&codPersona=" + codPersona,
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
									var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
									SaveToDisk('media/excel/' + fileName, fileName);
									console.log(response);
								}).fail(function(jqXHR, textStatus, errorThrown) {
									// Falha: Exibe mensagem de erro
									self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									// Caso queira exibir no corpo do modal:
									self.setContentAppend('<div>Erro: ' + jqXHR.responseText + '</div>');
								});
							},
							buttons: {
								fechar: function() {
									//close
								}
							}
						});
					}
				},
				cancelar: function() {
					//close
				},
			}
		});
	}

	function RefreshPersona(idEmp, tipo, cod_persona) {
		var log_personasusu;

		if (tipo == 'Arquivadas') {
			log_ativo = 'N';
		} else {
			log_ativo = 'S';
		}

		if ($('#LOG_PERSONASUSU').prop('checked')) {
			log_personasusu = 'S';
		} else {
			log_personasusu = 'N';
		}

		let modsAutorizados = $('#modsAutorizados').val();


		$.ajax({
			type: "POST",
			url: "ajxRefreshPersona.do",
			data: {
				COD_EMPRESA: idEmp,
				MASTER: "<?= $CarregaMaster ?>",
				LOG_ATIVO: log_ativo,
				COD_PERSONA: cod_persona,
				LOG_PERSONASUSU: log_personasusu,
				modsAutorizados: modsAutorizados,
			},
			beforeSend: function() {
				$('#div_refreshPersona' + tipo).html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshPersona" + tipo).html(data);
				if (cod_persona != 0 && tipo == "") {
					if ($("#collapse1").is(":visible")) {
						RefreshPersona("<?= fnEncode($cod_empresa) ?>", 'Arquivadas', 0);
					}
				} else if (cod_persona != 0 && tipo == "Arquivadas") {
					RefreshPersona("<?= fnEncode($cod_empresa) ?>", '', 0);
				}
			},
			error: function() {
				$('#div_refreshPersona' + tipo).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function excluiPersona(idEmp, cod_persona, qtd_campanha, des_persona) {

		if ($('#LOG_PERSONASUSU').prop('checked')) {
			log_personasusu = 'S';
		} else {
			log_personasusu = 'N';
		}

		if (qtd_campanha == 0) {

			$.alert({
				title: "Aviso",
				type: 'orange',
				content: "Deseja mesmo excluir a persona <b>" + des_persona + "</b>?<br>Essa ação não pode ser desfeita.",
				buttons: {
					"Sim": {
						btnClass: 'btn-danger',
						action: function() {
							$.ajax({
								type: "POST",
								url: "ajxRefreshPersona.do?opcao=EXC",
								data: {
									COD_EMPRESA: idEmp,
									MASTER: "<?= $CarregaMaster ?>",
									COD_PERSONA: cod_persona,
									LOG_PERSONASUSU: log_personasusu
								},
								beforeSend: function() {
									$('#div_refreshPersona').html('<div class="loading" style="width: 100%;"></div>');
								},
								success: function(data) {

									RefreshPersona("<?= fnEncode($cod_empresa) ?>", '', 0);

									if ($("#collapse1").is(":visible")) {
										RefreshPersona("<?= fnEncode($cod_empresa) ?>", 'Arquivadas', 0);
									}

								},
								error: function() {
									$('#div_refreshPersona').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
								}
							});
						}
					},
					"Não": {
						action: function() {

						}
					}
				}
			});

		} else {

			$.alert({
				title: "Alerta",
				type: 'red',
				content: "A persona <b>" + des_persona + "</b> não pode ser excluída!<br>Existem campanhas vinculadas a ela.",
				buttons: {
					"Ok": {
						action: function() {
							// window.location.href = "novoLogin.do";
						}
					},
					"Ver detalhes": {
						btnClass: 'btn-info',
						action: function() {
							var popLink = "action.php?mod=<?php echo fnEncode(1785) ?>&id=<?= fnEncode($cod_empresa) ?>&idp=" + cod_persona + "&pop=true";
							var popTitle = "Campanhas vinculadas - " + des_persona;
							//alert(popLink);	
							setIframe(popLink, popTitle);
							$('.modal').not('#popModalNotifica').appendTo("body").modal('show');
						}
					}
				}
			});

		}

	}

	function RefreshCampanha(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshCampanha.do#campanha",
			data: {
				ajx1: idEmp
			},
			beforeSend: function() {
				$('#div_refreshCampanha').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshCampanha").html(data);
			},
			error: function() {
				$('#div_refreshCampanha').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_" + index).val());
		$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_" + index).val());
		$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
		$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_" + index).val());
		$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>