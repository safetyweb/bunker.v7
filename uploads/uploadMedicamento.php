<?php

	include '../_system/_functionsMain.php';
	require_once '../js/plugins/Spout/Autoloader/autoload.php';
	
	// echo fnDebug('true');
	// fnEscreve('Entra no ajax');

	use Box\Spout\Reader\ReaderFactory;
	use Box\Spout\Common\Type;

	$adm = $Cdashboard->connAdm();
	// print_r($adm);

	$cod_empresa = fnLimpaCampoZero($_GET['id']);
	$acao = fnLimpaCampo(@$_GET['acao']);
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];

	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

	switch($acao){

		case "gravar":

		if (isset($_FILES['arquivo'])) {
			$errors = "";
			$file_name = $_FILES['arquivo']['name'];
			$file_size = $_FILES['arquivo']['size'];
			$file_tmp = $_FILES['arquivo']['tmp_name'];
			$file_type = $_FILES['arquivo']['type'];

			$arquivo = array(
                'CAMINHO_TMP' => $file_tmp,
                'CONADM' => $adm
            );

		    $retorno = fnScan($arquivo);

		    if($retorno['RESULTADO'] == 0){

				$reader = ReaderFactory::create(Type::XLSX); // for XLSX files

				$reader->open($file_tmp);
				
				/*
				Glossário do array da planilha:

				$row[0] = NOM_MEDICAMENTO;
				$row[1] = CODIGO_BARRA;
				$row[2] = DURACAO;

				*/
				$duplicado = 0;
				$ultimo_cod = 0;
				$sql1 = "";

				$arr_codigo = array();
				$tem_duplicado = 0;
				$sql2 = "SELECT CODIGO_BARRA FROM produtos_marka_to WHERE COD_EMPRESA = '$cod_empresa'";
			        $resultado = mysqli_query($adm, $sql2);
			        $registro = mysqli_fetch_array($resultado);

			        while ($registro = mysqli_fetch_array($resultado)) {
			        	array_push($arr_codigo, $registro['CODIGO_BARRA']);
			        }
				
				foreach ($reader->getSheetIterator() as $sheet) {
					//evitando que a primeira linha da planilha seja gravada (cabeçalho)
					$contador = 0;
					
					foreach ($sheet->getRowIterator() as $row) {
						if($contador == 0){
							// filtrando todas as colunas da planilha que não estão vazias
							$colunas = array_filter($row, create_function('$a','return preg_match("#\S#", $a);'));
							// echo count($colunas);
							// print_r($row);
						}
						else if($contador != 0 && count($colunas) == 3){

							// Consulta verifica se o código de barras já existe
					        $sql2 = "SELECT COUNT(*) FROM produtos_marka_to WHERE CODIGO_BARRA = '$row[1]' AND COD_EMPRESA = '$cod_empresa'";
					        $resultado = mysqli_query($adm, $sql2);
					        $registro = mysqli_fetch_array($resultado);

					        if (in_array($row[1], $arr_codigo)) {
					            // Se já existe, interromper a execução
					            $tem_duplicado++;
					            continue;
					        }

							//buscando string sql pelo código de barra do medicamento
							if (strpos($sql1, "$row[1]")) {
								//incrementando o contador caso o código de barra seja duplicado (para informar o nro de registros duplicados)
							    $duplicado++;
							}else{

								//comparando o ultimo cod externo com o cod externo a ser gravado
								if($row[1] != $ultimo_cod && $row[1] != ""){
									//limitando o nome do produto a 150 caracteres (limite definido no campo da tabela)
									$ultimo_cod = fnLimpaCampo(trim("$row[1]"));
									$prod = fnLimpaCampo(trim($row[0]));
									$prod = substr("$prod",0,149);
									$prod = str_replace("'","´",$prod);
									$duracao = preg_replace('/[^0-9]/', '', $row[2]);

									$sql1.= "INSERT INTO produtos_marka_to(
										NOM_MEDICAMENTO,
										CODIGO_BARRA,
										DURACAO,
										COD_EMPRESA,
										COD_CADASTR
										) VALUES(
										'$prod',
										'$row[1]',
										'$duracao',
										'$cod_empresa',
										$cod_usucada
										);";

								}else{
									//incrementando o contador caso o cod externo seja duplicado (para informar o nro de registros duplicados)
									if($row[1]){
										$ultimo_cod = "$row[1]";
										$duplicado++;
									}
								}
							}
						}else{
							echo 'A planilha deve conter exatamente 3 colunas: "Código Externo", "EAN(os valores são opcionais)" e "Nome do Produto". Revise sua planilha e tente novamente.';
							break;
						}
						$contador++;
					}
				}
				// echo $sql1;
				if($sql1 != ""){
					mysqli_multi_query($adm, trim($sql1)) or die("É possível que a ordem das colunas da planilha esteja incorreta. Verifique na planilha a ser importada se a ordem das colunas é: 1 - Código Externo, 2 - EAN e 3 - Nome do Produto");

					unset($sql1);

					// echo($duplicado);
					echo $tem_duplicado;
				}
				$reader->close();

			}else{

		        echo 'Arquivo infectado por: <i>'.$retorno['MSG'].'</i>';

		    }

		}

		break;
	}
?>