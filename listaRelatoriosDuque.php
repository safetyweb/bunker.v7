<?php
	
	//echo "<h5>_".$opcao."</h5>";

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
	
	//fnMostraForm();

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

										<div class="row">
											<div class="services-list">
											
												<div class="row" style="margin: 0 0 0 1px;">
													
													<div class="col-sm-6 col-md-4 col-md-4">
														<div class="service-block" style="visibility: visible;">
															<div class="ico fal fa-chart-line highlight"></div>
															<div class="text-block">
																<h4>Vendas</h4>
																<div class="text">Informações detalhadas do seu programa</div>
																<div class="push10"></div>
																<a href="action.do?mod=<?php echo fnEncode(1214)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas</a> <br/>
																<a href="action.do?mod=<?php echo fnEncode(1009)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas (Overview)</a> <br/>
																<a href="action.do?mod=<?php echo fnEncode(1219)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Produtos Mais Vendidos</a> <br/>
																<!--<a href="action.do?mod=<?php echo fnEncode(1290)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Identificadas</a> <br/>-->
																<a href="action.do?mod=<?php echo fnEncode(1291)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Melhores Clientes</a> <br/>
																<a href="action.do?mod=<?php echo fnEncode(1303)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastros Geral</a> <br/>
																<a href="action.do?mod=<?php echo fnEncode(1292)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Usuários Administrativos</a> <br/>
															</div>
														</div>
													</div>
													
													<!--
													<div class="col-sm-6 col-md-4">
														<div class="service-block" style="visibility: visible;">
															<div class="ico fal fa-code highlight"></div>
															<div class="text-block">
																<h4>Sistema</h4>
																<div class="text">Logs de acessos e muito mais</div>
																<div class="push10"></div>
																<a href="action.do?mod=<?php echo fnEncode(1196)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Entrada de Venda WS</a> <br/>
																<a href="action.do?mod=<?php echo fnEncode(1197)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastro de Clientes WS</a> <br/>
																<a href="action.do?mod=<?php echo fnEncode(1198)."&id=".fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consulta de Clientes WS</a> <br/>
															</div>
														</div>
													</div>
													-->
													
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