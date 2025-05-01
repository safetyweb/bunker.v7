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
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//fnMostraForm();

?>

<link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900" rel="stylesheet">
			
<style>

table {
    border-collapse: collapse;
    border:1px solid #e6e6e6;
    z-index: 2;
}

table {
    border-collapse: collapse;
    border:1px solid #e6e6e6;
	width: 100%;
	position: relative;
	z-index: 2;
}

table td, tr {
    border-collapse: collapse;
    border:1px solid #e6e6e6;
	position: relative;
	z-index: 2;
}

body{
    font-family: 'Roboto', sans-serif;
    
}

canvas{
    position: absolute;
    z-index: 1;
}

.primeiraLinha td{
    height: 80px;
    width: 150px;
    vertical-align:top;
}

.segundaLinha td{
    height: 200px;
    vertical-align:top;
}

.terceiraLinha td{
    height: 50px;
    text-align: center;
}

.titulo1{
    padding-left: 7px;
    padding-top: 7px;
    font-size: 10px;
    color: #999999;
    font-weight: 700;
}

.titulo2{
    padding-top: 10px;
    padding-left: 7px;
    font-size: 14px;
    color: #333333;
    font-weight: 700;
}

.titulo3{
    padding-top: 10px;
    padding-left: 7px;
    font-size: 20px;
    color: #404040;
    font-weight: 700;
}

.titulo4{
    padding-top: 3px;
    padding-left: 7px;
    font-size: 12px;
    color: #999999;
    font-weight: 700;
}

i.red{
  border-radius: 60px;
  padding: 5px;
  border: 3px solid #fff;
  background-color: #d9534f;
  font-size: 13px !important;
  color: white;
}

i.green{
  border-radius: 60px;
  padding: 5px;
  border: 3px solid #fff;
  background-color: #5cb85c;
  font-size: 13px !important;
  color: white;
}

.infoColuna{
    top: -30%;
    position: relative;
}

.red{
    color: #d9534f;
    padding-left: 0;
}

.green{
    color: #5cb85c;
    padding-left: 0;
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
									
									<?php $abaCampanhas = 1042; include "abasCampanhasConfig.php"; ?>
																		
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
											
										<div class="push10"></div>
										
										
    <table id="tabela1" border="1">

        <tr class="primeiraLinha">
            <td>
                <div class="titulo1">PASSO 1</div>
                <div class="titulo2">Clientes Campanha</div>
            </td>
            <td>
                <div class="titulo1">PASSO 2</div>
                <div class="titulo2">Filtro #1</div>
            </td>
            <td>
                <div class="titulo1">PASSO 3</div>
                <div class="titulo2">Filtro #2</div>
            </td>
            <td>
                <div class="titulo1">PASSO 4</div>
                <div class="titulo2">Filtro #3</div>
            </td>
            <td>
                <div class="titulo1">PASSO 5</div>
                <div class="titulo2">Filtro #4</div>
            </td>
        </tr>
        <tr class="segundaLinha">
            <td>
                <div class="titulo1">SESSIONS</div>
                <div class="titulo3">4,029</div>
                <div class="titulo4">0% a 55.3%</div>
            </td>
            <td>
                <div class="titulo1">SESSIONS</div>
                <div class="titulo3">5,942</div>
                <div class="titulo4">55.3% a 33.7%</div>
            </td>
            <td>
                <div class="titulo1">SESSIONS</div>
                <div class="titulo3">3,627</div>
                <div class="titulo4">33.7% a 41%</div>
            </td>
            <td>
                <div class="titulo1">SESSIONS</div>
                <div class="titulo3">886</div>
                <div class="titulo4">41% a 70.6%</div>
            </td>
            <td>
                <div class="titulo1">SESSIONS</div>
                <div class="titulo3">548</div>
                <div class="titulo4">70.6% a 10.9%</div>
            </td>
        </tr>
        <tr class="terceiraLinha">
            <td>
                <div class="infoColuna">
                    <i class="fa fa-arrow-down red" aria-hidden="true"></i>
                    <div class="titulo1 red">PREJUÍZO</div>
                    <div class="titulo3 red">35.3%</div>
                    <div class="titulo4 red">(4,029)</div>
                </div>
            </td>
            <td>
                <div class="infoColuna">
                    <i class="fa fa-check green" aria-hidden="true"></i>
                    <div class="titulo1 green">LUCRO</div>
                    <div class="titulo3 green">65.3%</div>
                    <div class="titulo4 green">(7,089)</div>
                </div>
            </td>
            <td>
                <div class="infoColuna">
                    <i class="fa fa-arrow-down red" aria-hidden="true"></i>
                    <div class="titulo1 red">PREJUÍZO</div>
                    <div class="titulo3 red">15.3%</div>
                    <div class="titulo4 red">(4,029)</div>
                </div>
            </td>
            <td>
                <div class="infoColuna">
                    <i class="fa fa-arrow-down red" aria-hidden="true"></i>
                    <div class="titulo1 red">PREJUÍZO</div>
                    <div class="titulo3 red">25.3%</div>
                    <div class="titulo4 red">(1,009)</div>
                </div>
            </td>
            <td>
                <div class="infoColuna">
                    <i class="fa fa-check green" aria-hidden="true"></i>
                    <div class="titulo1 green">LUCRO</div>
                    <div class="titulo3 green">85.3%</div>
                    <div class="titulo4 green">(9,829)</div>
                </div>
            </td>
        </tr>
    </table>
    
    <canvas id="myCanvas" class="">Your browser does not support the HTML5 canvas tag.</canvas>
	
	
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <!--<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>-->
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
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