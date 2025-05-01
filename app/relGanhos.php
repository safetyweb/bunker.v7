<?php 
include_once 'header.php';
$tituloPagina = "Extrato";
include_once "navegacao.php";

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

$sql = "CALL LISTA_WALLET($cod_cliente, '$cod_empresa', 0, 5)";
// echo($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

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

<div class="container" style="background-color: <?= $cor_fullpag ?>;">

    <div class="row">

		<div class="col-xs-12 fundoCel1" style=" color: <?= $cor_textfull ?>; background-color: <?= $cor_fullpag ?>;  width: 100%; border-radius: 0px; margin-bottom: 20px; padding-top: 20px;">

			<h3>Olá, <?= $nom_cliente ?>!</h3>

			<p class="f14 text-muted">Saldo disponível</p>
			<span class="f32b"><?= ($casasDec == 2) ? "R$" : " PONTOS: " ?><?= fnValor($credito_disponivel, $casasDec) ?></span>

		</div>

		<div class="col-xs-6" style="padding: 15px;">
			<div class="bloco-saldo d-flex space-between-centered">
				<span class="f14">Resgatado</span><span class="fal fa-angle-right pull-right"></span>
				<span class="line-break"></span>
				<span class="f16b"><?= ($casasDec == 2) ? "R$" : "" ?><?= fnValor($total_debitos, $casasDec) ?></span>
			</div>
		</div>
		<div class="col-xs-6" style="padding: 15px;">
			<div class="bloco-saldo d-flex space-between-centered">
				<span class="f14">Expirado</span><span class="fal fa-angle-right pull-right"></span>
				<span class="line-break"></span>
				<span class="f16b"><?= ($casasDec == 2) ? "R$" : "" ?><?= fnValor($credito_expirados, $casasDec) ?></span>
			</div>
		</div>

	</div>

	<div class="row" style="border-radius: 15px 15px 0px 0px; padding-top: 25px; margin-top:35px; background-color: <?= $cor_backpag ?>;">

		<div id="relConteudo">

			<?php

			if(mysqli_num_rows($arrayQuery) > 0){

				while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)){

				$data = explode(" ", $qrBuscaProdutos['DAT_CADASTR']);

				$txtExpira = "Expira em:";
				$corExpira = "";
				$corCred = "";

				if(trim($qrBuscaProdutos['DES_STATUSCRED']) == 'Expirado'){
					$txtExpira = "Expirado em:";
					$corExpira = "text-danger";
				}

				$sinal = "+";

				if($qrBuscaProdutos[TIP_CREDITO] == 'D'){
					$sinal = "-";
					$txtExpira = "";
					$corExpira = "";
					$corCred = "text-danger";
				}

				if($qrBuscaProdutos[VAL_CREDITO] == 0){
					$sinal = "";
					$txtExpira = "";
					$corExpira = "";
				}

			?>

					<div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
		    			<div class="shadow2">
		            		<div class="push5"></div>
		                    <div class="col-xs-4 zeraPadLateral text-center">
		                        <h5 class="f12"><b><?=fnDataShort($data[0])?></b><br/><span class="f10"><?=$data[1]?></span></h5>
		                    </div>
		                    <div class="col-xs-1 zeraPadLateral text-center">
		                        <h5><?=$qrBuscaProdutos['TIP_CREDITO']?></h5>
		                    </div>
		                    <div class="col-xs-4 zeraPadLateral text-center">
		                        <h5><?=$qrBuscaProdutos['NOM_FANTASI']?></h5>
		                    </div>
		                    <div class="col-xs-3 zeraPadLateral text-center">
		                        <h5 class="<?=$corCred?>"><?=$sinal?> <?=fnValor($qrBuscaProdutos['VAL_CREDITO'],$casasDec)?></h5>
		                    </div>
		                    <div class="col-xs-12">
		                        <h5 class="f10 <?=$corExpira?>" style="margin-top: -10px; margin-bottom: 0;"><?=$txtExpira?> <b><?=fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])?></b></h5>
		                    </div>
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

		if(mysqli_num_rows($arrayQueryCount) > 5){
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
			data: {itens: cont, casasDec: "<?=$casasDec?>", corTextos: "<?=$cor_textos?>", COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", COD_CLIENTE: "<?=fnEncode($cod_cliente)?>"},
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