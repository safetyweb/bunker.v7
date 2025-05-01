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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

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
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//busca perfil do usuário 
	//4 - fidelidade
	$sql1 = "select cod_usuario,cod_defsist,cod_perfils
			from usuarios
			where cod_empresa = ".$_SESSION["SYS_COD_EMPRESA"]." and
				  cod_defsist = 4 and
				  cod_usuario = ".$_SESSION["SYS_COD_USUARIO"]." ";
	
	//fnEscreve($sql1);			  
	if ($_SESSION["SYS_COD_SISTEMA"] == 3){
		$cod_perfils = '9999';	
		
	} else {
		$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
		$qrBuscaPerfil = mysqli_fetch_assoc($arrayQuery1);
		$cod_perfils = $qrBuscaPerfil['cod_perfils'];	
	}
	  
	//busca modulos autorizados
	$sql2 = "select cod_modulos from perfil
			where cod_sistema=4 and
			cod_perfils in($cod_perfils)";
	
	//fnEscreve($sql2);			
	$arrayQuery2 = mysqli_query($connAdm->connAdm(),$sql2) or die(mysqli_error());
	
	$count=0;
	while ($qrBuscaAutorizacao = mysqli_fetch_assoc($arrayQuery2))
	  {
		$cod_modulos_aut = $qrBuscaAutorizacao['cod_modulos'];
		$modulosAutorizados = $modulosAutorizados.$cod_modulos_aut.",";
	  }
	   
	   $arrayAutorizado = explode(",", $modulosAutorizados);
	
	
	//fnEscreve($sql2);

	$arrayParamAutorizacao = array('COD_MODULO'=>"9999",
						'MODULOS_AUT'=>$arrayAutorizado,
						'COD_SISTEMA'=>$_SESSION["SYS_COD_SISTEMA"]);

	//echo "<pre>";	
	//print_r($arrayParamAutorizacao);	
	//echo "</pre>";	
	
	function fnAutRelatorio($codRelatorio, $paramAutRelatorio){		
		$arrayCompara = $paramAutRelatorio['MODULOS_AUT'];	
		//se sistema adm marka
		if ($paramAutRelatorio['COD_SISTEMA'] == 3 ){
			$retornoAut = true;	
		}else {
			if(recursive_array_search($codRelatorio,$arrayCompara) !== false)
				{$retornoAut = true;
				}else
				{$retornoAut = false; }    
		}				
		return $retornoAut;    
	}

	if(isset($_GET['idc'])){
		//busca dados da campanha
		$cod_campanha = fnDecode($_GET['idc']);	
		$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($qrBuscaCampanha)){
			$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
			$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
			$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
			$des_icone = $qrBuscaCampanha['DES_ICONE'];
			$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];				
			$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
			
		}	
	 		
		//busca dados do tipo da campanha
		$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '".$tip_campanha."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($qrBuscaTpCampanha)){
			$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
			$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
			$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
			$label_1 = $qrBuscaTpCampanha['LABEL_1'];
			$label_2 = $qrBuscaTpCampanha['LABEL_2'];
			$label_3 = $qrBuscaTpCampanha['LABEL_3'];
			$label_4 = $qrBuscaTpCampanha['LABEL_4'];
			$label_5 = $qrBuscaTpCampanha['LABEL_5'];
			
		}   
	
	}
	
	//fnEscreve($cod_perfils);
	//fnEscreve($modulosAutorizados);
	//fnEscreve($_SESSION["SYS_COD_USUARIO"]);
	//fnEscreve($_SESSION["SYS_COD_EMPRESA"]);
	
	//fnEscreve($_SESSION["SYS_COD_SISTEMA"]);
	
	//fnMostraForm();
	//fnEscreve($modulosRelatorios);
?>


<style>


#services {}
#services .services-top {
    padding: 70px 0 50px;
}
#services .services-list {
    padding-top: 50px;
}
.services-list .service-block {
    margin-bottom: 25px;
}
.services-list .service-block .ico {
    font-size: 38px;
    float: left;
}
.services-list .service-block .text-block {
    margin-left: 58px;
}
.services-list .service-block .text-block .name {
    font-size: 20px;
    font-weight: 900;
    margin-bottom: 5px;
}
.services-list .service-block .text-block .info {
    font-size: 16px;
    font-weight: 300;
    margin-bottom: 10px;
}
.services-list .service-block .text-block .text {
    font-size: 12px;
    line-height: normal;
    font-weight: 300;
}
.highlight {
    color: #2ac5ed;
}                    

</style>

					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									$formBack = "1048";
									include "atalhosPortlet.php"; 
									?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>

									<?php 
									//1190 - Lista relatórios - adm
									//1189 - Lista relatórios - campanhas
									if (fnDecode($_GET['mod']) == 1182){
										$abaCampanhas = 1182; 
										include "abasCampanhasConfig.php";
										echo "<div class='push30'></div>";
										}						
									//fnEscreve()	
									?>
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
										
										<h4><?php echo $nom_empresa; ?></h4>
										
										<div class="push30"></div>

										<div class="row" style="display: none">
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
											                    <li><a href="#NOM_EMPRESA">Razão social</a></li>
											                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
											                    <li><a href="#NUM_CGCECPF">CNPJ</a></li>										                      
										                    </ul>
										                </div>
										                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">         
										                <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
										                <div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
										                	<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										                </div>
										                <div class="input-group-btn">
										                    <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										                </div>
										            </div>
										        </div>
										         	
										        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
												<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

											</form>
										    
										</div>

										<div class="push30"></div>

										<div class="row">
											<div class="services-list">
											
												<div class="row" style="margin: 0 0 0 1px;">
												
													<div class="col-sm-6 col-md-4">
														<div class="service-block" style="visibility: visible;">
															<div class="ico fal fa-chart-pie highlight"></div>
															<div class="text-block">
																<h4>Dash Board</h4>
																<div class="text">Infográficos incríveis</div>
																<div class="push10"></div>
																
																<?php if(fnAutRelatorio("1414",$arrayParamAutorizacao) === true) { ?>
																<a href="action.do?mod=<?=fnEncode(1414)."&id=".fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>" target="_blank">&rsaquo; Cupons</a> <br/>
																<?php } ?>

																<?php if(fnAutRelatorio("1415",$arrayParamAutorizacao) === true) { ?>
																<a href="action.do?mod=<?=fnEncode(1415)."&id=".fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>" target="_blank">&rsaquo; Resultado de Indicações</a> <br/>
																<?php } ?>

																<?php if(fnAutRelatorio("1416",$arrayParamAutorizacao) === true) { ?>
																<a href="action.do?mod=<?=fnEncode(1416)."&id=".fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>" target="_blank">&rsaquo; Clientes Indicados por Loja</a> <br/>
																<?php } ?>

																<?php if(fnAutRelatorio("1417",$arrayParamAutorizacao) === true) { ?>
																<a href="action.do?mod=<?=fnEncode(1417)."&id=".fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>" target="_blank">&rsaquo; Cupons Emitidos por Loja</a> <br/>
																<?php } ?>

																<?php if(fnAutRelatorio("1418",$arrayParamAutorizacao) === true) { ?>
																<a href="action.do?mod=<?=fnEncode(1418)."&id=".fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>" target="_blank">&rsaquo; Números Gerados por Cliente por Loja</a> <br/>
																<?php } ?>

																<?php if(fnAutRelatorio("1419",$arrayParamAutorizacao) === true) { ?>
																<a href="action.do?mod=<?=fnEncode(1419)."&id=".fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>" target="_blank">&rsaquo; Comparação de Vendas Por Período</a> <br/>
																<?php } ?>


																
															</div>
														</div>
													</div>
													
												</div>
												
											</div>
											
										</div>
        
             										
		
										
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
		
        $(document).ready( function() {
			
			$(function(){

				// Id Canvas
				var idCanvas = 'myCanvas';
				//var idCanvas2 = 'myCanvas2';
				//var idCanvas3 = 'myCanvas3';

				// Cor que será usado no preenchimento do gráfico.
				var corGrafico = 'gold';

				// iD tabela
				var idTabela = 'tabela1';
				//var idTabela2 = 'tabela2';
				//var idTabela3 = 'tabela3';

				// Pontos de Inicio em cada coluna, mandar porcentagem de 0 a 100.
				var pontos = [100, 55, 20, 15, 8, 3];

				// Criar Gráfico
				criarGrafico(idCanvas, pontos, corGrafico, idTabela);
				// Criar Gráfico
				//criarGrafico(idCanvas2, pontos, corGrafico, idTabela2);
				// Criar Gráfico
				//criarGrafico(idCanvas3, pontos, corGrafico, idTabela3);

			});

			function criarGrafico(idCanvas, pontos, corGrafico, idTabela){
				var canvas = document.getElementById(idCanvas);
				var ctx = canvas.getContext('2d');

				// Seta Valores de altura e largura da tabela para o canvas
				ctx.canvas.width  = $('#' + idTabela).outerWidth();
				ctx.canvas.height = $('#' + idTabela).outerHeight();

				$('#'+ idCanvas).css('top', $('#' + idTabela).position().top);

				//Pega informações da tabela
				var larguraColuna = $('#' + idTabela +' td').outerWidth();
				var numColunas = $('#' + idTabela +' tr:first td').size();
				var alturaPrimeiraLinha = $('.primeiraLinha').outerHeight();
				var alturaSegundaLinha = $('.segundaLinha').outerHeight();

				ctx.beginPath();
				ctx.moveTo(0, (((1 - (pontos[0] / 100)) * alturaSegundaLinha) + alturaPrimeiraLinha)); // altura e largura do ponto inicial

				// Seta linhas
				var cont = 1;
				while(cont < pontos.length){
					ctx.lineTo((larguraColuna * cont) + cont, (((1 - (pontos[cont] / 100)) * alturaSegundaLinha) + alturaPrimeiraLinha));
					cont++;
				}

				// Pega a soma da primeira e segunda linha, assim é possível descobrir qual o ponto final do gráfico
				var pontoFinal = alturaPrimeiraLinha + alturaSegundaLinha;

				// Fecha lado direito
				ctx.lineTo((larguraColuna * numColunas) + cont, pontoFinal);

				// Fecha Parte de baixo
				ctx.lineTo(0, pontoFinal);

				//Pinta novo "Quadrado"
				ctx.fillStyle= corGrafico;
				ctx.fill();

				// Muda cor das linhas
				ctx.strokeStyle='lightgray';

				// Desenha propriedades definidas
				ctx.stroke();
			}
		
			
        });		
		
		
		
	</script>	