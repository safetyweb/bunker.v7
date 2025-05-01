<?php
include '_system/_functionsMain.php';

$cod_empresa = fnDecode($_GET['id']);
$cod_contrat = fnDecode($_GET['idCT']);
$cod_conveni = fnDecode($_GET['idCN']);
$cod_cliente = fnDecode($_GET['idC']);
$acao = @$_GET['acao'];
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


if ($acao == "gera_lote"){
	$ids = @$_POST['ids'];

	$sql = "INSERT INTO CONTROLE_RECEBIMENTO(
			COD_EMPRESA,
			COD_CONVENI,
			COD_CONTRAT,
			COD_CLIENTE,
			LOG_LOTE,
			DES_NOMEBEM,
			TIP_CONTROLE,
			COD_USUCADA
			) VALUES(
			$cod_empresa,
			$cod_conveni,
			$cod_contrat,
			$cod_cliente,
			1,
			'Lote ".date("Ymd-His")."',
			'RCB',
			$cod_usucada
			)";
	//fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa, ''), $sql);

	$sql = "SELECT MAX(COD_RECEBIM) COD_RECEBIM FROM CONTROLE_RECEBIMENTO
			WHERE COD_EMPRESA=$cod_empresa";
	$rs = mysqli_query(connTemp($cod_empresa, ''), $sql);
	//fnEscreve($sql);
	$linha = mysqli_fetch_assoc($rs);

	$sql = "UPDATE CONTROLE_RECEBIMENTO SET
				COD_RECEBIM_LOTE = 0".$linha["COD_RECEBIM"]."
			WHERE COD_EMPRESA=$cod_empresa
			AND COD_RECEBIM IN (0$ids)";
	//fnEscreve($sql);
	$rs = mysqli_query(connTemp($cod_empresa, ''), $sql);

	fnCalculaLote($linha["COD_RECEBIM"],$cod_empresa);
	exit;

}elseif ($acao == "desfaz_lote"){
	$id = @$_POST['id'];
	$sql = "DELETE FROM CONTROLE_RECEBIMENTO
			WHERE COD_EMPRESA = $cod_empresa AND LOG_LOTE = 1 AND COD_RECEBIM=0$id";
	//fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa, ''), $sql);

	exit;

}elseif ($acao == "remove_do_lote"){
	$id = @$_POST['id'];

	$sql = "SELECT COD_RECEBIM_LOTE COD_RECEBIM FROM CONTROLE_RECEBIMENTO
			WHERE COD_EMPRESA=$cod_empresa AND COD_RECEBIM=0$id";
	$rs = mysqli_query(connTemp($cod_empresa, ''), $sql);
	//fnEscreve($sql);
	$linha = mysqli_fetch_assoc($rs);

	$sql = "UPDATE CONTROLE_RECEBIMENTO SET COD_RECEBIM_LOTE = NULL
			WHERE COD_EMPRESA = $cod_empresa AND COD_RECEBIM=0$id";
	//fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa, ''), $sql);

	fnCalculaLote($linha["COD_RECEBIM"],$cod_empresa);

	exit;

}elseif ($acao == "renomeia_lote"){
	$id = @$_POST['id'];
	$nome = @$_POST['nome'];
	$sql = "UPDATE CONTROLE_RECEBIMENTO SET DES_NOMEBEM = '$nome'
			WHERE COD_EMPRESA = $cod_empresa AND LOG_LOTE = 1 AND COD_RECEBIM=0$id";
	//fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa, ''), $sql);

	exit;

}elseif ($acao == "add_lote"){
	$ids = @$_POST['ids'];
	$idlote = @$_POST['idlote'];

	$sql = "UPDATE CONTROLE_RECEBIMENTO SET
				COD_RECEBIM_LOTE = 0".$idlote."
			WHERE COD_EMPRESA=$cod_empresa
			AND COD_RECEBIM IN (0$ids)";
	//fnEscreve($sql);
	$rs = mysqli_query(connTemp($cod_empresa, ''), $sql);

	fnCalculaLote($idlote,$cod_empresa);

	exit;
}
?>

<table class="table table-bordered table-striped table-hover">

		<?php
		$val_total_g = 0;
		$val_totmed_g = 0;
		$val_totevo_g = 0;
		$count = 0;
		$lote = 0;
		$list_lote_drop = "";
		
		$sqlote = "SELECT 0 COD_RECEBIM,'' DES_NOMEBEM UNION
				SELECT DISTINCT COD_RECEBIM,DES_NOMEBEM FROM controle_recebimento
				WHERE COD_EMPRESA = $cod_empresa AND  
				TIP_CONTROLE = 'RCB' AND 
				COD_CONTRAT = $cod_contrat AND  
				COD_CONVENI = $cod_conveni AND
				LOG_LOTE=1";
		//fnEscreve($sqlote);
		$arrayQueryLote = mysqli_query(connTemp($cod_empresa, ''), $sqlote);
		while ($qrLote = mysqli_fetch_assoc($arrayQueryLote)) {
			$lote++;
			?>
			<thead>
				<?php
				if ($qrLote["COD_RECEBIM"] > 0){
				?>
				<tr class="bg-primary">
					<th colspan=100>
						<div style="display: flex;align-content: center;align-items: center;">
							<div style="flex:auto"><input type='text' value='<?=$qrLote["DES_NOMEBEM"]?>' class='input-sm bg-primary' style='border:0;width:100%;font-size:15px;' onChange='renomeiaLote(<?=$qrLote["COD_RECEBIM"]?>);' name='nome_lote[<?=$qrLote["COD_RECEBIM"]?>]'></div>
							<button style="margin-left:5px;" class='btn btn-danger btn-sm' onClick='desfazLote(<?=$qrLote["COD_RECEBIM"]?>);return false;'><i class=' fas fa-trash'></i> Desfazer Lote</button>
						</div>
					</th>
				</tr>
				<?php
					$list_lote_drop .= "<li><a href='javascript:' onClick='addLote(".$qrLote["COD_RECEBIM"].")'>".$qrLote["DES_NOMEBEM"]."</a></li>";
				}
				?>
				<tr>
					<th width="40"></th>
					<th width="40"></th>
					<th>Código</th>
					<th>Descrição do Bem</th>
					<th>Núm. Recebimento</th>
					<th>Data Recebimento</th>
					<th class="text-right">Quantidade</th>
					<th class="text-right">Valor Un.</th>
					<th class="text-right">Valor</th>
					<th class="text-right"></th>
				</tr>
			</thead>
			<tbody>
			<?php
			$sql = "SELECT * FROM CONTROLE_RECEBIMENTO 
			WHERE COD_EMPRESA = $cod_empresa AND  
			TIP_CONTROLE = 'RCB' AND 
			COD_CONTRAT = $cod_contrat AND  
			COD_CONVENI = $cod_conveni AND
			IFNULL(LOG_LOTE,0)=0 AND
			".($qrLote["COD_RECEBIM"] > 0?"IFNULL(COD_RECEBIM_LOTE,0) = ".$qrLote["COD_RECEBIM"]:"IFNULL(COD_RECEBIM_LOTE,0) <= 0");
	

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

			$val_total = 0;
			$val_totmed = 0;
			$val_totevo = 0;
			while ($qrItem = mysqli_fetch_assoc($arrayQuery)) {
				$count++;
				echo "
				<tr>
					<td>".($qrLote["COD_RECEBIM"] <= 0?"<input type='checkbox' value='" . $qrItem['COD_RECEBIM'] . "' name='checklote' onclick='verificaLote()'>":"")."</th>
					<td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
					<td>" . $qrItem['COD_RECEBIM'] . "</td>
					<td>" . $qrItem['DES_NOMEBEM'] . "</td>
					<td>" . $qrItem['NUM_MEDICAO'] . "</td>
					<td>" . fnDataShort($qrItem['DAT_MEDICAO']) . "</td>
					<td class='text-right'>" . fnValor($qrItem['VAL_EVOLUCAO'], 2) . "</td>
					<td class='text-right'>" . fnValor($qrItem['VAL_MEDICAO'], 2) . "</td>
					<td class='text-right'>" . fnValor($qrItem['VAL_TOTAL'], 2) . "</td>
					<td>".($qrLote["COD_RECEBIM"] > 0?"<button title='Remover do Lote' class='btn btn-danger btn-sm' onClick='removeDoLote(".$qrItem['COD_RECEBIM'].");return false;'><i class=' fas fa-unlink'></i></button>":"")."</th>
				</tr>
				
				<input type='hidden' id='ret_COD_RECEBIM_" . $count . "' value='" . $qrItem['COD_RECEBIM'] . "'>
				<input type='hidden' id='ret_COD_CONVENI_" . $count . "' value='" . $qrItem['COD_CONVENI'] . "'>
				<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . $qrItem['COD_CLIENTE'] . "'>
				<input type='hidden' id='ret_COD_MEDICAO_" . $count . "' value='" . $qrItem['COD_MEDICAO'] . "'>
				<input type='hidden' id='ret_NUM_MEDICAO_" . $count . "' value='" . $qrItem['NUM_MEDICAO'] . "'>
				<input type='hidden' id='ret_DAT_MEDICAO_" . $count . "' value='" . fnDataShort($qrItem['DAT_MEDICAO']) . "'>
				<input type='hidden' id='ret_VAL_EVOLUCAO_" . $count . "' value='" . fnValor($qrItem['VAL_EVOLUCAO'], 2) . "'>
				<input type='hidden' id='ret_VAL_MEDICAO_" . $count . "' value='" . fnValor($qrItem['VAL_MEDICAO'], 2) . "'>
				<input type='hidden' id='ret_VAL_TOTAL_" . $count . "' value='" . fnValor($qrItem['VAL_TOTAL'], 2) . "'>
				<input type='hidden' id='ret_DES_NOMEBEM_" . $count . "' value='" . $qrItem['DES_NOMEBEM'] . "'>
				";


				$val_total += $qrItem['VAL_TOTAL'];
				$val_totmed += $qrItem['VAL_MEDICAO'];
				$val_totevo += $qrItem['VAL_EVOLUCAO'];
				$val_total_g += $qrItem['VAL_TOTAL'];
				$val_totmed_g += $qrItem['VAL_MEDICAO'];
				$val_totevo_g += $qrItem['VAL_EVOLUCAO'];
			}
			?>

			
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-right"><b><?= fnValor($val_totevo, 2); ?></b></td>
					<td class="text-right"><b><?= fnValor($val_totmed, 2); ?></b></td>
					<td class="text-right"><b><?= fnValor($val_total, 2); ?></b></td>
					<td></td>
				</tr>

				<?php
				if ($qrLote["COD_RECEBIM"] <= 0){
				?>
				<tr>
					<td colspan=100 class="chk_items text-right" style="display:none;">
						<div style="display: flex;align-items: center;justify-content: flex-end;">
							<button type="submit" class="btn btn-primary btn-sm" onClick="geraLote();return false;">Gerar Lote</button>
							<div style="margin-left:5px;" class="dropdown">
								<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									Adicionar ao Lote
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenu1" id="drop_list_lote">
								</ul>
							</div>
						</div>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
			<?php
		}

		if ($lote > 1){
		?>

		<tfoot class="bg-primary">
			<tr>
				<td colspan=6>Total Geral</td>
				<td class="text-right"><b><?= fnValor($val_totevo_g, 2); ?></b></td>
				<td class="text-right"><b><?= fnValor($val_totmed_g, 2); ?></b></td>
				<td class="text-right"><b><?= fnValor($val_total_g, 2); ?></b></td>
				<td></td>
			</tr>
		</tfoot>

		<?php
		}
		?>
</table>

<script>
	url = "ajxRecebimentoLista.do?id=<?=fnEncode($cod_empresa)?>&idCT=<?=fnEncode($cod_contrat)?>&idCN=<?=fnEncode($cod_conveni)?>&idC=<?=fnEncode($cod_cliente)?>";
	$("#drop_list_lote").html("<?=$list_lote_drop?>");

	function verificaLote(){
		if ($('input[type=checkbox][name=checklote]:checked').size() > 0){
			$(".chk_items").show();
		}else{
			$(".chk_items").hide();
		}
	}

	function geraLote(){
		let ids = $.map($('input[type=checkbox][name=checklote]:checked'), function(n, i){
			return n.value;
		}).join(',');

		$.ajax({
			type: "POST",                
			url: url+"&acao=gera_lote",
			data: {ids},
			beforeSend:function(){
				$("#tb_lista").addClass("loading_data");
			},
			success: function(data) {
				console.log(data);
				refresh_grid();
			}
		});
	}

	function desfazLote(id){
		$.ajax({
			type: "POST",                
			url: url+"&acao=desfaz_lote",
			data: {id},
			beforeSend:function(){
				$("#tb_lista").addClass("loading_data");
			},
			success: function(data) {
				console.log(data);
				refresh_grid();
			}
		});
	}
	function removeDoLote(id){
		$.ajax({
			type: "POST",                
			url: url+"&acao=remove_do_lote",
			data: {id},
			beforeSend:function(){
				$("#tb_lista").addClass("loading_data");
			},
			success: function(data) {
				console.log(data);
				refresh_grid();
			}
		});
	}
	function renomeiaLote(id){
		let nome = $("[name='nome_lote["+id+"]']").val();
		$.ajax({
			type: "POST",                
			url: url+"&acao=renomeia_lote",
			data: {id,nome},
			beforeSend:function(){
				$("#tb_lista").addClass("loading_data");
			},
			success: function(data) {
				console.log(data);
				refresh_grid();
			}
		});
	}
	function addLote(idlote){
		let ids = $.map($('input[type=checkbox][name=checklote]:checked'), function(n, i){
			return n.value;
		}).join(',');

		$.ajax({
			type: "POST",                
			url: url+"&acao=add_lote",
			data: {ids,idlote},
			beforeSend:function(){
				$("#tb_lista").addClass("loading_data");
			},
			success: function(data) {
				console.log(data);
				refresh_grid();
			}
		});
	}
</script>