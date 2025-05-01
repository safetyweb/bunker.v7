	<?php

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';

	use Box\Spout\Reader\ReaderFactory;
	use Box\Spout\Common\Type;

	echo fnDebug('true');

	$cod_empresa = $_REQUEST['id'];
	$typeFile = $_REQUEST['typeFile'];
	$cod_usucada = $_REQUEST['usC'];

	$diretorioEnvioDestino = $_REQUEST['diretorio'] . '/' . $cod_empresa;

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

		if($retorno['RESULTADO'] == 0){

			$reader = ReaderFactory::create(Type::XLSX);
			$reader->open($file_tmp);

			$ultimo_cod = 0;

			foreach ($reader->getSheetIterator() as $sheet) {
					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
				$contador = 0;

				foreach ($sheet->getRowIterator() as $row) {
					if($contador == 0){
						$colunas = array_filter($row, create_function('$a','return preg_match("#\S#", $a);'));
							////fnEscreve(count($colunas));
					}
					else if($contador != 0 && count($colunas) != 0){


						if(fnLimpaCampo(trim($row[0])) != $ultimo_cod && fnLimpaCampo(trim($row[0])) != ""){

							$cod_externo = preg_replace("/[\r\n]/", "", trim(rtrim($row[0])));
							$nom_usuario = fnLimpaCampo(trim($row[1]));
							$cod_univend = fnLimpaCampoZero(trim($row[2]));
							$log_estatus = fnLimpaCampo(trim($row[4]));


							if(!empty($cod_externo)){

								$sql = "UPDATE usuarios 
								SET 
								NOM_USUARIO = '$nom_usuario',
								COD_ALTERAC = '$cod_usucada',
								LOG_ESTATUS = '$log_estatus',
								DAT_ALTERAC = NOW() 
								where 
								cod_empresa=$cod_empresa AND 
								COD_EXTERNO='$cod_externo' AND 
								COD_TPUSUARIO IN (8,7,11) AND
								COD_UNIVEND = $cod_univend
								";

							//fnEscreve($sql);
							//fnTesteSql($connAdm->connAdm(), $sql);

								mysqli_query($connAdm->connAdm(), $sql);
							}

						}
						echo "";

					}else{
						echo 'A planilha deve conter exatamente 4 colunas: "COD_EXTERNO", "QTD_EXTRA", "GANHA", "LIMITE_DE_USO". Revise sua planilha e tente novamente.';
						break;
					}
					$contador++;
				}
			}
			$reader->close();

			move_uploaded_file($file_tmp, $diretorioEnvioDestino . $file_name);

		}else{

			echo 'Arquivo infectado por: <i>'.$retorno['MSG'].'</i>';

		}

	}

	?>
