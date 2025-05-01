<?php
include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$acao = fnLimpaCampo(@$_REQUEST['acao']);
$cod_empresa = fnDecode(fnLimpaCampo(@$_REQUEST['COD_EMPRESA_PG']));
$cod_modulos = fnDecode(fnLimpaCampo(@$_REQUEST['COD_MODULOS']));
$cod_usuario = fnDecode(fnLimpaCampo(@$_REQUEST['COD_USUARIO']));
$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
$pagina = fnLimpaCampoZero(@$_REQUEST['PAGINA']);
$qtd_pagina = fnLimpaCampoZero(@$_REQUEST['ITENS_POR_PAGINA']);
$limit_ini = (($pagina - 1) * $qtd_pagina);
$id_min = fnLimpaCampoZero(@$_REQUEST['ID_MIN']);

if ($acao == "usuarios") {

	$sql = "SELECT * FROM USUARIOS
			WHERE COD_EMPRESA = 0" . $cod_empresa . "
			ORDER BY LOG_USUARIO";
	echo "<option></option>";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	while ($row = mysqli_fetch_assoc($arrayQuery)) {
		echo "<option value='" . fnEncode(@$row["COD_USUARIO"]) . "'>" . @$row["LOG_USUARIO"] . "</option>";
	}
} else {

	$sql = "SELECT 
				L.ID,
				L.DATA_HORA,
				U.COD_USUARIO,
				U.LOG_USUARIO NOM_USUARIO,
				E.COD_EMPRESA,
				E.NOM_FANTASI NOM_EMPRESA,
				P.COD_MODULOS COD_PAGINA,
				P.NOM_MODULOS NOM_PAGINA,
				L.OPCAO_FORM,
				L.IP,
				L.NAVEGADOR,
				L.GET,
				L.POST
			FROM LOG_MEN L
			LEFT JOIN USUARIOS U ON U.COD_USUARIO=L.COD_USUARIO
			LEFT JOIN EMPRESAS E ON E.COD_EMPRESA=L.COD_EMPRESA_PAGE
			LEFT JOIN MODULOS P ON P.COD_MODULOS=L.COD_PAGINA
			WHERE 1=1
				" . ($id_min > 0 ? " AND L.ID < $id_min" : "") . "
				" . ($cod_empresa <> 0 ? " AND L.COD_EMPRESA_PAGE = $cod_empresa" : "") . "
				" . ($cod_modulos <> 0 ? " AND L.COD_PAGINA = $cod_modulos" : "") . "
				" . ($cod_usuario <> 0 ? " AND L.COD_USUARIO = $cod_usuario" : "") . "
				" . ($dat_ini <> "" ? " AND DATE(L.DATA_HORA) >= '$dat_ini'" : "") . "
				" . ($dat_fim <> "" ? " AND DATE(L.DATA_HORA) <= '$dat_fim'" : "") . "
			ORDER BY L.ID DESC
			LIMIT $qtd_pagina";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	while ($row = mysqli_fetch_assoc($arrayQuery)) {
?>
		<tr id="bloco_<?= $row["ID"] ?>">
			<th width="5%" class="text-center"><a href="javascript:void(0);" onclick="abreDetail(<?= $row["ID"] ?>)" style="padding:10px;"><i class="fa fa-angle-right" aria-hidden="true"></i></a></th>
			<th>
				<script>
					$("#ID_MIN").val(<?= @$row["ID"] ?>);
				</script><?= $row["ID"] ?>
			</th>
			<th><?= fnDataFull(@$row["DATA_HORA"]) ?></th>
			<th><?= @$row["COD_USUARIO"] . "-" . @$row["NOM_USUARIO"] ?></th>
			<th><?= @$row["COD_EMPRESA"] . "-" . @$row["NOM_EMPRESA"] ?></th>
			<th><?= @$row["COD_PAGINA"] . "-" . @$row["NOM_PAGINA"] ?></th>
			<?php /*
		<td class="text-center">
			<a class="btn btn-xs btn-success transparency" data-toggle="modal" onClick="$('#modal_<?=@$row["ID"]?>').appendTo('body');" data-target="#modal_<?=@$row["ID"]?>"><i class="fas fa-bars"></i> Detalhes </a>
			<!-- Modal -->
			<div class="modal fade" id="modal_<?=@$row["ID"]?>" tabindex="-1" role="dialog" aria-labelledby="modal_<?=@$row["ID"]?>" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">

				  <div class="modal-body">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label">ID Log</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=@$row["ID"]?>">
							</div>														 
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="inputName" class="control-label">Usuário</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=@$row["COD_USUARIO"]."-".@$row["NOM_USUARIO"]?>">
							</div>														 
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">Empresa</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=@$row["COD_EMPRESA"]."-".@$row["NOM_EMPRESA"]?>">
							</div>														 
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="inputName" class="control-label">Página</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=@$row["COD_PAGINA"]."-".@$row["NOM_PAGINA"]?>">
							</div>														 
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">IP</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=@$row["IP"]?>">
							</div>														 
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="inputName" class="control-label">Navegador</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=@$row["NAVEGADOR"]?>">
							</div>														 
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">Data/Hora</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=fnDataFull(@$row["DATA_HORA"])?>">
							</div>														 
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<label for="inputName" class="control-label">Ação Botão</label>
								<input type="text" class="form-control input-sm leitura" readonly value="<?=@$row["OPCAO_FORM"]?>">
							</div>														 
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="inputName" class="control-label">Dados via GET</label>
								<br>
								<?php
									$arr = json_decode(@$row["GET"],true);
								?>
								<pre><?php print_r($arr)?></pre>
							</div>														 
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="inputName" class="control-label">Dados via POST</label>
								<br>
								<?php
									$arr = json_decode(@$row["POST"],true);
								?>
								<pre><?php print_r($arr)?></pre>
							</div>														 
						</div>
					</div>

				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
				  </div>
				</div>
			  </div>
			</div>
		</td>
		*/ ?>
		</tr>
		<tr style="background-color: #fff; display: none;" class="abreDetail_<?= $row["ID"] ?> flex">
			<td>&nbsp;</td>
			<td colspan=5>

				<div style="width: 1024px; overflow: auto;">

					<table id="table" class="table">

						<tr>
							<td width="200"><span class='label bg-success'><i class="fal fa-network-wired"></i></span><small> <?= @$row["IP"] ?><small></td>
							<td><span class='label bg-success'><i class="fab fa-internet-explorer"></i></span><small> <?= @$row["NAVEGADOR"] ?><small></td>
						</tr>
						<tr>
							<td colspan="10">
								<?php
								$arr = json_decode(@$row["GET"], true);
								foreach ($arr as $key => $value) {
									echo "<span class='label bg-warning'>GET &nbsp;" . $key . "</span>&nbsp;" . $value . " | " . fnDecode(Str_Replace("u00a2", "¢", $value)) . " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
								}
								?>
							</td>
						</tr>

					</table>

					<table id="table" class="table table-bordered table-hover tablesorter" style="min-width:800px; width: 800px; max-width:800px; overflow-x: scroll;">
						<thead>
							<tr>
								<th width="150"><small>Parâmetro</small></th>
								<th width="150"><small>Origem</small></th>
								<th><small>Valor</small></th>
							</tr>
						</thead>
						<tbody>
							<?php
							/*
						$arr = json_decode(@$row["GET"],true);
						foreach($arr as $key => $value){
							echo "<tr>";
							echo "222<td><small>".$key."</small></td>";
							echo "<td><span class='label bg-warning'>GET</span></td>";
							echo "<td>".$value."</td>";
							echo "</tr>";
						}
						*/

							$arr = json_decode(@$row["POST"], true);
							foreach ($arr as $key => $value) {
								echo "<tr>";
								echo "<td><small>" . $key . "</small></td>";
								echo "<td><span class='label bg-info'>POST</span></td>";
								echo "<td width='100px'><div style='white-space: nowrap; overflow-x:auto; width:800px'>" . $value . "</div></td>";
								echo "</tr>";
							}
							?>
						</tbody>

					</table>

				</div>
			</td>
		</tr>

<?php
	}
}
?>