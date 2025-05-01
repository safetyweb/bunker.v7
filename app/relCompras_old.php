<?php 
include 'header.php';
$tituloPagina = "Extrato";
include "navegacao.php";

// if(!isset($_SESSION["usuario"])){
        
//    header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
   
// }

list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf($cor_backpag, "#%02x%02x%02x");

if($r_cor_backpag > 50){
	$r = ($r_cor_backpag-50);
}else{
	$r =($r_cor_backpag+50);
	if($r_cor_backpag < 30){
		$r = $r_cor_backpag;
	}
}
if($g_cor_backpag > 50){
	$g = ($g_cor_backpag-50);
}else{
	$g =($g_cor_backpag+50);
	if($g_cor_backpag < 30){
		$g = $g_cor_backpag;
	}
}
if($b_cor_backpag > 50){
	$b = ($b_cor_backpag-50);
}else{
	$b =($b_cor_backpag+50);
	if($b_cor_backpag < 30){
		$b = $b_cor_backpag;
	}
}

if($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50){
	$r =($r_cor_backpag+40);
	$g =($g_cor_backpag+40);
	$b =($b_cor_backpag+40);
}
 

$dat_ini = date("Y-m-d",strtotime("-30 days"));


$sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE
			FROM CLIENTES 
			WHERE NUM_CGCECPF = $usuario 
			AND COD_EMPRESA = $cod_empresa";

$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

$qrCli = mysqli_fetch_assoc($arrayCli);

$cod_cliente = $qrCli[COD_CLIENTE];
$nom_cliente = $qrCli[NOM_CLIENTE];
$nom_cliente = explode(" ", $nom_cliente);
$nom_cliente = ucfirst(strtolower($nom_cliente[0]));

$sqlCount = "SELECT 1
		FROM CREDITOSDEBITOS A		
		WHERE A.COD_CLIENTE = $cod_cliente
		AND A.COD_STATUSCRED <> 6
		AND A.COD_STATUS <> 15  
		AND A.COD_EMPRESA = $cod_empresa";

// echo($sql);

$arrayQueryCount = mysqli_query(connTemp($cod_empresa,''),$sqlCount);

// $sql = "SELECT 
// 		A.TIP_CREDITO, 
// 		A.DAT_CADASTR, 
// 		A.VAL_CREDITO,
// 		A.DAT_EXPIRA,
// 		A.DES_STATUSCRED,
// 		G.NOM_FANTASI
// 		FROM CREDITOSDEBITOS A
// 		LEFT JOIN VENDAS F ON F.COD_VENDA=A.COD_VENDA
// 		and A.COD_VENDA > 0
// 		LEFT JOIN WEBTOOLS.UNIDADEVENDA G ON G.COD_UNIVEND=A.COD_UNIVEND		
// 		WHERE A.COD_CLIENTE = $cod_cliente
// 		AND A.COD_STATUSCRED <> 6
// 		AND A.COD_STATUS <> 15  
// 		AND A.COD_EMPRESA = $cod_empresa
// 		-- AND A.DAT_CADASTR >= '$dat_ini 00:00:01'											
// 		ORDER BY A.DAT_CADASTR DESC
// 		LIMIT 5";

// $sql = "CALL LISTA_WALLET($cod_cliente, '$cod_empresa', 0, 5)";
// // echo($sql);

// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

$sql2 = "CALL total_wallet($cod_cliente, '$cod_empresa')";
                
//fnEscreve($sql);

$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);
$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery2);


if (isset($arrayQuery2)){
    
    $total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
    $total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
    $credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
    $credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
    $credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
    $credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
}else{
    
    $total_creditos = 0;
    $total_debitos = 0;
    $credito_disponivel = 0;
    $credito_aliberar = 0;
    $credito_expirados = 0;
    $credito_bloqueado = 0;
    
}


?>

<style>
            
	body {
	    background-color: <?=$cor_backpag?>;
	}
				
	.shadow{
	    -webkit-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
	    -moz-box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
	    box-shadow: 0px 0px 18px -2px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
	    width: 100%;
	    border-radius: 5px;
	}

	.shadow2{
	    -webkit-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
		-moz-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
		box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
	    width: 100%;
	    border-radius: 5px;
	}

	.reduzMargem{
	    margin-bottom: 10px;
	}

</style>	

<div class="container">

	<div class="push30"></div>
	<div class="push30"></div>

    <div class="col-xs-12 text-center shadow2 fundoCel1" style=" color: <?=$cor_textfull?>; background-color: <?=$cor_fullpag?>;  border-radius: 30px 30px 5px 5px;">
    
        <div class="push"></div>

        <div class="col-md-12 textoCel1">
            <h4 style="margin-bottom: 0;"><?=$nom_cliente?>, <span class="f12">você tem</span></h4>
            <span class="f21">
                <strong style="font-size: 36px;"><?=fnValor($credito_disponivel,$casasDec)?></strong>
            </span>
            <div class="push"></div>
            <span class="f10"><?= ($casasDec==2) ? "REAIS" : " PONTO(S)" ?> DISPONÍVEIS</span>
        </div>

        <div class="push10"></div>

        <div class="col-md-12 texto2Cel1">

            <div class="push10"></div>

            <hr class="separador">

            <div class="push10"></div>
            
                <div class="col-xs-4 text-center">
                    <span class="f14"><?=fnValor($total_debitos,$casasDec)?></span><br/>
                    <span class="f12">Resgatado</span>
                </div>
                <div class="col-xs-4 text-center">
                    <span class="f14"><?=fnValor($credito_expirados,$casasDec)?></span><br/>
                    <span class="f12">Expirado</span>
                </div>
                <div class="col-xs-4 text-center">
                    <span class="f14"><?=fnValor($total_creditos,$casasDec)?></span><br/>
                    <span class="f12">Ganho</span>
                </div>

            <div class="push10"></div>
                    
        </div>

    </div>

	<div class="push30"></div>

	<div class="row">

		<div id="relConteudo">

			<?php

			$sqlCount = "SELECT 1
					FROM VENDAS a  
					WHERE a.COD_CLIENTE = $cod_cliente AND 
					      a.COD_EMPRESA = $cod_empresa 
					UNION
						 SELECT  1 
					FROM CREDITOSDEBITOS a 
					WHERE a.COD_CLIENTE = $cod_cliente AND 
					      a.COD_EMPRESA = $cod_empresa AND 
					      A.TIP_CREDITO='C' AND 
					      A.COD_VENDA=0";

				$arrayCount = mysqli_query(connTemp($cod_empresa,''),$sqlCount);

				$registros = mysqli_num_rows($arrayCount);


			if($registros > 0){

            	// $sql = "SELECT B.DES_LANCAMEN,
				// 			   C.DES_OCORREN,
				// 			   D.NOM_FANTASI,
				// 			   E.DES_FORMAPA,
				// 			   A.*,
				// 			   ROUND(IFNULL((SELECT SUM(VAL_CREDITO) 
				// 									FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
				// 									AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
				// 									AND TIP_CREDITO = 'C'), 0), 2) VAL_CREDITOS,
				// 			   (SELECT MIN(DAT_EXPIRA) 
				// 						FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
				// 						AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
				// 						AND TIP_CREDITO = 'C') DAT_EXPIRA,
				// 			   (select count(*) from itemvenda where cod_venda=a.cod_venda and itemvenda.cod_exclusa > 0)as EXCLUIDO																   
				// 		FROM VENDAS a
				// 		LEFT JOIN $connAdm->DB.tipolancamentomarka b ON b.COD_LANCAMEN = a.COD_LANCAMEN
				// 		LEFT JOIN $connAdm->DB.ocorrenciamarka c ON c.COD_OCORREN = a.COD_OCORREN
				// 		LEFT JOIN $connAdm->DB.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND
				// 		LEFT JOIN formapagamento e ON e.COD_FORMAPA = a.COD_FORMAPA
				// 		WHERE a.COD_CLIENTE = $cod_cliente
				// 		AND a.COD_EMPRESA = $cod_empresa 
				// 		ORDER BY DAT_CADASTR_WS DESC limit 10
				// 		";

				if($cod_empresa == 19){
					$ifCred = "ROUND(IFNULL((SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE AND TIP_CREDITO = 'C' AND creditosdebitos.cod_campanha=169) , 0), 2) VAL_CREDITOS_EXTRA,";
					$colCred = "'' AS VAL_CREDITOS_EXTRA,";
				}else{
					$ifCred = "";
					$colCred = "";
				}

				$sql = "SELECT * FROM(

							(SELECT  B.DES_LANCAMEN, 
										C.DES_OCORREN, 
										D.NOM_FANTASI, 
										E.DES_FORMAPA, 
								        A.COD_STATUSCRED,
										A.VAL_TOTPRODU,
										A.VAL_RESGATE,
										A.VAL_DESCONTO,
										A.VAL_TOTVENDA,
										A.DAT_CADASTR_WS,


										ROUND(IFNULL((SELECT SUM(VAL_CREDITO) 
													FROM CREDITOSDEBITOS 
													WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA AND 
													CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE AND 
													TIP_CREDITO = 'C'), 0), 2) VAL_CREDITOS,
										$ifCred
										(SELECT MIN(DAT_EXPIRA) 
												FROM CREDITOSDEBITOS 
												WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA AND 
												      CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE AND 
														TIP_CREDITO = 'C') DAT_EXPIRA,
										(select count(*) from 
															itemvenda 
															where cod_venda=a.cod_venda and 
															      itemvenda.cod_exclusa > 0)as EXCLUIDO 
							FROM VENDAS a 
							LEFT JOIN webtools.tipolancamentomarka b ON b.COD_LANCAMEN = a.COD_LANCAMEN 
							LEFT JOIN webtools.ocorrenciamarka c ON c.COD_OCORREN = a.COD_OCORREN 
							LEFT JOIN webtools.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND 
							LEFT JOIN formapagamento e ON e.COD_FORMAPA = a.COD_FORMAPA 
							WHERE a.COD_CLIENTE = $cod_cliente AND 
							      a.COD_EMPRESA = $cod_empresa)
							UNION
							(SELECT   'AVULSO', 
										'AVULSO', 
										D.NOM_FANTASI, 
										'AVULSO' AS DES_FORMAPA, 
								      A.COD_STATUSCRED,
										0,
										0,
										0,
										0,
										A.DAT_REPROCE,
							         A.VAL_CREDITO,
							         $colCred
									   A.DAT_EXPIRA, 
										'' AS EXCLUIDO 
							FROM CREDITOSDEBITOS a 
							LEFT JOIN webtools.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND 
							WHERE a.COD_CLIENTE = $cod_cliente AND 
							      a.COD_EMPRESA = $cod_empresa AND 
							      A.TIP_CREDITO='C' AND 
							      A.COD_VENDA=0)
						) saldoCli
						ORDER BY DAT_CADASTR_WS DESC
						limit 10";
				// echo $sql;											
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				
				$count = 0;
				$valorTTotal = 0;
				$valorTRegaste = 0;
				$valorTDesconto = 0;
				$valorTvenda = 0;
				$classeExc = "";
				//pegar o ultimo tokem gerado 
                                                            
                //==fim==============
				while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)){

					$count++;
					$background="#F2F3F4";
					
					if ($qrBuscaProdutos['EXCLUIDO'] == 0) {
						$classeExc2 = "";
						$mostraItemExcluido = "";
					}else{
						$classeExc2 = "text-danger";	
						$mostraItemExcluido = "<i class='fa fa-minus-circle' aria-hidden='true'></i>";	
					}
					
					
					
				   $tokem="select itemvenda.COD_VENDA,itemvenda.DES_PARAM1,
								  itemvenda.DES_PARAM2,vendas.COD_VENDAPDV 
								  from itemvenda 
							inner join vendas on itemvenda.COD_VENDA= vendas.COD_VENDA
							where vendas.COD_VENDAPDV='".$qrBuscaProdutos['COD_VENDAPDV']."'";

					// echo $tokem;
					// exit();
				   $tokemexec=mysqli_query(connTemp($cod_empresa,''),$tokem);
				   $rwtokem=mysqli_fetch_assoc($tokemexec);
				   $colunaEspecial = $rwtokem['DES_PARAM2'];
				   if($colunaEspecial=='' || $colunaEspecial=='None')
				   {
						$colunaEspecial = '<i class="fa fa-times text-danger fa-1x" aria-hidden="true"></i>';
				   }     
						   
					

					$data = explode(" ", $qrBuscaProdutos['DAT_CADASTR_WS']);

					$txtExpira = "Expira: <b>".fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])."</b>";
					$corExpira = "";

					$val_venda = fnValor($qrBuscaProdutos['VAL_TOTVENDA'],2);

					if($qrBuscaProdutos['DES_FORMAPA'] == "AVULSO"){
						$val_venda = "Créd. Avulso";
					}

					if($qrBuscaProdutos['DAT_EXPIRA'] == ""){
						$txtExpira = "&nbsp;";
						$corExpira = "";
					}else if($qrBuscaProdutos['DAT_EXPIRA'] < date("Y-m-d")){
						$txtExpira = "Expirado: <b>".fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])."</b>";
						$corExpira = "text-danger";
					}

					if ($qrBuscaProdutos['COD_STATUSCRED'] != 6) {
						$valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
						$valorTRegaste = $valorTRegaste + $qrBuscaProdutos['VAL_RESGATE'];
						$valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
						$valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
						$classeExc = "";
					}else{
						$classeExc = "text-danger";
						$background="#FADBD8";
						$txtExpira="<b>Estornado</b>";
						$corExpira="text-danger";
					}

					$val_credito = $qrBuscaProdutos['VAL_CREDITOS'];
					$cred_extra = 0;

					if($cod_empresa == 19){
						$cred_extra = $qrBuscaProdutos['VAL_CREDITOS_EXTRA'];
						$val_credito = $val_credito - $cred_extra;
					}

			?>

					<div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
						<div class="shadow2" style="background-color: <?=$background?>;">
			        		<div class="push5"></div>
			                <div class="col-xs-4 zeraPadLateral text-center">
			                    <h5 class="f12"><b><?=fnDataShort($data[0])?></b><br/><span class="f9"><?=$data[1]?></span></h5>
			                </div>
			                <div class="col-xs-2 zeraPadLateral text-center">
			                    <h5 class="f9"><?=$colunaEspecial?></h5>
			                </div>
			                <div class="col-xs-4 zeraPadLateral text-center">
			                    <h5 class="f12"><?=$qrBuscaProdutos['NOM_FANTASI']?>
			                    <?php if($qrBuscaCliente['COD_PROFISS'] == 108){ ?>
			                    	<div class="push5"></div>
			                	<?php } ?>
			                    </h5>
			                </div>
			                <div class="col-xs-2 zeraPadLateral text-center">
			                    <h5 class="f12"><?=$val_venda?>
			                    </h5>
			                </div>
		                    <?php // if($qrBuscaCliente['COD_PROFISS'] == 108){ ?>
		                    	<div class="push"></div>
		                    	<div class="col-xs-6" style="margin-top: -10px;">
				        			<span class="f9 <?=$corExpira?>"><?=$txtExpira?></span>
				        		</div>
				        		<div class="col-xs-6 text-right" style="margin-top: -10px;">
				        			<span class="f9 <?=$corExpira?>">Cashback: <b>+ <?=fnValor($val_credito,2)?></b></span>
				        		</div>
		                	<?php //} ?>
		                	<?php if($cred_extra > 0){ ?>
		                		<div class="push10"></div>
				        		<div class="col-xs-6 col-xs-offset-6 text-right" style="margin-top: -10px;">
				        			<span class="f9 <?=$corExpira?>">Extra: <b>+ <?=fnValor($cred_extra,2)?></b></span>
				        		</div>
		                	<?php } ?>
		                	<?php if($qrBuscaProdutos['VAL_RESGATE'] > 0){ ?>
		                		<div class="push10"></div>
				        		<div class="col-xs-6 col-xs-offset-6 text-right" style="margin-top: -10px;">
				        			<span class="f9 text-danger">Resgate: <b>- <?=fnValor($qrBuscaProdutos['VAL_RESGATE'],2)?></b></span>
				        		</div>
		                	<?php } ?>
			        		
			        		<div class="push5"></div>
			            </div>
			        </div>

			<?php 

					$totCredito+=$qrBuscaProdutos['VAL_CREDITO'];

				}

			?>

		</div>

		<div class="push10"></div>

	<?php

		if($registros > 5){
	?>	
			<div class="col-xs-12 text-center">
				<a class="btn btn-primary" id="loadMore">Carregar Mais</a>
			</div>
			<div class="push50"></div>
	<?php

		}


	}else{

	?>

		<div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
			<div class="shadow2">
        		<div class="push5"></div>
                <div class="col-xs-12 zeraPadLateral text-center">
                    <h5>Não há movimentação</h5>
                </div>
        		<div class="push5"></div>
            </div>
        </div>

	<?php	

	}

	?>
												
	</div>		

</div> <!-- /container -->

<script type="text/javascript">

	var cont = 0;

	$('#loadMore').click(function(){
		
		cont +=5;

		if(cont >= "<?=mysqli_num_rows($arrayQueryCount)?>"){
			$('#loadMore').addClass('disabled');
			$('#loadMore').text('Não há mais movimentações');
		}

		$.ajax({
			type: "POST",
			url: "ajxRelGanhos.do",
			data: {itens: cont, casasDec: "<?=$casasDec?>", corTextos: "<?=$cor_textos?>", key: "<?=fnEncode($cod_empresa)?>"},
			beforeSend:function(){	
				$('#loadMore').text('Carregando...');
			},
			success:function(data){

				if(cont >= "<?=mysqli_num_rows($arrayQueryCount)?>"){
					$('#loadMore').addClass('disabled');
					$('#loadMore').text('Não há mais movimentações');
				}else{
					$('#loadMore').text('Carregar Mais');
				}
					$('#relConteudo').append(data);
				
				// console.log(data);
			},
			error:function(){
				alert('Erro ao carregar...');
			}
		});
	});
</script>

<?php include 'footer.php' ?>