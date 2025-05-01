<?php

	include './_system/_functionsMain.php';

	$cod_empresa = 19;
	$cod_cliente = fnLimpaCampoZero(fnDecode($_POST[COD_CLIENTE]));
	$itens = $_POST[itens];

	$sql = "SELECT B.DES_LANCAMEN,
				   C.DES_OCORREN,
				   D.NOM_FANTASI,
				   E.DES_FORMAPA,
				   A.*,
				   ROUND(IFNULL((SELECT SUM(VAL_CREDITO) 
										FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
										AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
										AND TIP_CREDITO = 'C'), 0), 2) VAL_CREDITOS,
				   (SELECT MIN(DAT_EXPIRA) 
							FROM CREDITOSDEBITOS WHERE CREDITOSDEBITOS.COD_VENDA = A.COD_VENDA
							AND CREDITOSDEBITOS.COD_CLIENTE = A.COD_CLIENTE 
							AND TIP_CREDITO = 'C') DAT_EXPIRA,
				   (select count(*) from itemvenda where cod_venda=a.cod_venda and itemvenda.cod_exclusa > 0)as EXCLUIDO																   
			FROM VENDAS a
			LEFT JOIN $connAdm->DB.tipolancamentomarka b ON b.COD_LANCAMEN = a.COD_LANCAMEN
			LEFT JOIN $connAdm->DB.ocorrenciamarka c ON c.COD_OCORREN = a.COD_OCORREN
			LEFT JOIN $connAdm->DB.unidadevenda d ON d.COD_UNIVEND = a.COD_UNIVEND
			LEFT JOIN formapagamento e ON e.COD_FORMAPA = a.COD_FORMAPA
			WHERE a.COD_CLIENTE = $cod_cliente
			AND a.COD_EMPRESA = $cod_empresa 
			ORDER BY DAT_CADASTR_WS DESC limit $itens, 10
			";
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
		if ($qrBuscaProdutos['COD_STATUSCRED'] != 6) {
			$valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
			$valorTRegaste = $valorTRegaste + $qrBuscaProdutos['VAL_RESGATE'];
			$valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
			$valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
			$classeExc = "";
		}else{
			$classeExc = "text-danger";	
		}
		
		$count++;
		if ($qrBuscaProdutos['EXCLUIDO'] == 0) {
			$classeExc2 = "";
			$mostraItemExcluido = "";
		}else{
			$classeExc2 = "text-danger";	
			$mostraItemExcluido = "<i class='fa fa-minus-circle' aria-hidden='true'></i>";	
		}
		
		
		if ($cod_empresa != 19) {
			if ($qrBuscaProdutos['COD_STATUSCRED'] != 6) {	
				$colunaEspecial = $qrBuscaProdutos['DES_OCORREN'];
			}else{
				$colunaEspecial = "venda estornada";
			}
		} else { 
			   $tokem="select itemvenda.COD_VENDA,itemvenda.DES_PARAM1,
							  itemvenda.DES_PARAM2,vendas.COD_VENDAPDV 
							  from itemvenda 
						inner join vendas on itemvenda.COD_VENDA= vendas.COD_VENDA
						where vendas.COD_VENDAPDV='".$qrBuscaProdutos['COD_VENDAPDV']."'";
			   $tokemexec=mysqli_query(connTemp($cod_empresa,''),$tokem);
			   $rwtokem=mysqli_fetch_assoc($tokemexec);
			   $colunaEspecial = $rwtokem['DES_PARAM2'];
			   if($colunaEspecial=='' || $colunaEspecial=='None')
			   {
					$colunaEspecial = '<i class="fa fa-times text-danger fa-1x" aria-hidden="true"></i>';
			   }     
			   
		}

		$data = explode(" ", $qrBuscaProdutos['DAT_CADASTR_WS']);

		$txtExpira = "Expira: ";
		$corExpira = "";

		if($qrBuscaProdutos['DAT_EXPIRA'] == ""){
			$txtExpira = "&nbsp;";
			$corExpira = "";
		}else if($qrBuscaProdutos['DAT_EXPIRA'] < date("Y-m-d")){
			$txtExpira = "Expirado: ";
			$corExpira = "text-danger";
		}

?>

		<div class="col-xs-12 reduzMargem corIcones zeraPadLateral" style="color: <?=$cor_textos?>">
			<div class="shadow2">
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
                    <h5 class="f12"><?=fnValor($qrBuscaProdutos['VAL_TOTVENDA'],2)?>
                    </h5>
                </div>
                <?php // if($qrBuscaCliente['COD_PROFISS'] == 108){ ?>
                	<div class="push"></div>
                	<div class="col-xs-6" style="margin-top: -10px;">
	        			<span class="f9 <?=$corExpira?>"><?=$txtExpira?> <b><?=fnDataShort($qrBuscaProdutos['DAT_EXPIRA'])?></b></span>
	        		</div>
	        		<div class="col-xs-6 text-right" style="margin-top: -10px;">
	        			<span class="f9">Cashback: <b>+ <?=fnValor($qrBuscaProdutos['VAL_CREDITOS'],2)?></b></span>
	        		</div>
            	<?php //} ?>
        		
        		<div class="push5"></div>
            </div>
        </div>

<?php 

	}

?>