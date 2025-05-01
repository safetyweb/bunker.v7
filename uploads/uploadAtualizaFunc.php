<?php

include '../_system/_functionsMain.php';
require_once '../js/plugins/Spout/Autoloader/autoload.php';

//echo fnDebug('true');
////fnEscreve('Entra no ajax');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$cod_empresa = fnLimpaCampoZero($_GET['id']);
if (isset($_GET['acao'])) $acao = fnLimpaCampo($_GET['acao']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

////fnEscreve($cod_empresa);

switch ($acao) {

	case "gravar": // Verificando qtd de registros

		if (isset($_FILES['arquivo'])) {
			$errors = "";
			$file_name = $_FILES['arquivo']['name'];
			$file_size = $_FILES['arquivo']['size'];
			$file_tmp = $_FILES['arquivo']['tmp_name'];
			$file_type = $_FILES['arquivo']['type'];

			$arquivo = array(
				'CAMINHO_TMP' => $file_tmp,
				'CONADM' => $connAdm->connAdm()
			);

			$retorno = fnScan($arquivo);

			if ($retorno['RESULTADO'] == 0) {

				$reader = ReaderFactory::create(Type::XLSX); // for XLSX files

				$reader->open($file_tmp);

				$totalRegistros = 0;
				$registrosDuplicados = 0;
				$countError = 0;
				$ultimo_cod = "";
				$sucess = 0;

				foreach ($reader->getSheetIterator() as $sheet) {
					// Evitar que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;

					foreach ($sheet->getRowIterator() as $row) {

						$row = array_map('trim', $row);

						if ($contador == 0) {
							$colunas = array_filter($row, create_function('$a', 'return preg_match("#\S#", $a);'));
						} elseif ($contador != 0 && count($colunas) == 2) {

							$totalRegistros++;

							$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($row[0]));

							if ($num_cgcecpf == $ultimo_cod) {
								// Este é um registro duplicado
								$registrosDuplicados++;
							} else {
								// Atualizar o último código externo
								$ultimo_cod = $num_cgcecpf;
								$log_funciona = fnLimpaCampo(fnLimpaDoc($row[1]));

								if ($ultimo_cod != "" && $log_funciona != "") {
									$sql = "UPDATE clientes SET LOG_FUNCIONA='$log_funciona' WHERE cod_empresa = $cod_empresa AND NUM_CGCECPF='$ultimo_cod'";

									$resultado = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

									$sql = "SELECT * FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND NUM_CGCECPF='$ultimo_cod'";
									$query = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
									if ($qrBusca = mysqli_fetch_assoc($query)) {
										$cod_cliente = $qrBusca['COD_CLIENTE'];
										if ($cod_cliente != "") {
											$sql = "DELETE from personaclientes
                                                    WHERE cod_cliente=$cod_cliente AND
                                                          cod_empresa=$cod_empresa";
											mysqli_query(connTemp($cod_empresa, ""), trim($sql));
										}
									}

									if ($resultado === false) {
										$countError++;
									} else {
										$sucess++;
									}
								} else {
									$countError++;
								}
							}
						} else {
							echo 'A planilha deve conter exatamente 2 colunas. Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
					$errorDuplica = $countError + $registrosDuplicados;
					echo $totalRegistros . "," . $errorDuplica . "," . $sucess;
				}

				$reader->close();
			} else {

				echo 'Arquivo infectado por: <i>' . $retorno['MSG'] . '</i>';
			}
		}

		break;

	default:
?>

		<div class="push100"></div>

		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4 text-center">
				<h4>Funcionarios atualizados com <b>sucesso</b>!</h4>
			</div>
		</div>

		<div class="row">

			<div class="push50"></div>

			<div class="col-md-3"></div>

			<div class="col-md-6">

				<div class="col-md-5">
					<div class="form-group">
						<label for="inputName" class="control-label">Nome e Tipo do Arquivo</label>
						<input type="text" class="form-control input-sm leitura2" name="NOM_ARQUIVO" id="NOM_ARQUIVO" value="" readonly>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label">Qtde de Registros</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_LINHAS" id="QTD_LINHAS" maxlength="45" value="<?= $qrLinhas['LINHAS']; ?>" readonly>
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label">Registros Atualizados</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_SUCESS" id="QTD_SUCESS" maxlength="45" value="" readonly>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="inputName" class="control-label">Registros com Erro ou Duplicados</label>
						<input type="text" class="form-control input-sm leitura2" name="QTD_DUPLICADOS" id="QTD_DUPLICADOS" maxlength="45" value="" readonly>
					</div>
				</div>

			</div>

		</div>

		<div class="push100"></div>

		<hr>

		<div class="col-md-10"></div>

		<div class="col-md-2">
			<a href="action.do?mod=<?php echo fnEncode(2029) . "&id=" . fnEncode($cod_empresa); ?>" class="col-md-12 btn btn-success concluir">Concluir</a>
		</div>

		<div class="push10"></div>
<?php
		break;
}
?>