<?php
header('Content-Type: application/json'); // set json response headers
include '_system/_functionsMain.php';

//fnDebug(true);

$outData = upload(); // a function to upload the bootstrap-fileinput files
echo json_encode($outData); // return json data
exit();

// main upload function used above
// upload the bootstrap-fileinput files
// returns associative array
function upload() {

	$cod_empresa = @$_REQUEST["cod_empresa"];
	$cod_conveni = @$_REQUEST["cod_conveni"];
	$num_contador = @$_REQUEST["num_contador"];
	$tipo = $_REQUEST["tipo"];
	$tp_cont = fnDecode($_REQUEST["tpc"]);
	$cod_objetoanexo = $_REQUEST["ido"];

	// if ($tipo == "convenio"){

	$status = 'S';

	if($cod_conveni == 0){
		$status = 'N';
	}

	if ($tipo == "COD_CONVENI"){
		$tipo = "";
		$cod_objetoanexo = "";
	}else{
		if($cod_objetoanexo != ""){
			$tipo = ", ".$tipo;
			$cod_objetoanexo = ", ".$cod_objetoanexo;
		}else{
			$tipo = "";
			$cod_objetoanexo = "";
			$status = 'N';
		}
	}


	// $tp_cont = 'Anexo do Convênio';
	if($num_contador == 0){
		$sqlCont = "SELECT COD_CONTADOR,NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
		// fnEscreve($sqlCont);

		$rs = mysqli_query(connTemp($cod_empresa,''),$sqlCont);
		$qrCont = mysqli_fetch_assoc($rs);
		$num_contador = $qrCont['NUM_CONTADOR'];
		$cod_contador = $qrCont['COD_CONTADOR'];

	}

	$nom_origem = $cod_empresa.".".$cod_conveni.".".date("Ymdhis").".".$num_contador;
	$diretorio = "/media/clientes/".$cod_empresa."/convenios/convenio.".$cod_conveni."/";

	// }

	// else{
	// 	$out['error'] = "Par&acirc;metro &quot;tipo&quot; inv&aacute;lido!";
	// 	return $out;
	// 	exit();	
	// }

    $preview = $config = $errors = [];
    $input = 'file'; // the input name for the fileinput plugin
	
	if (empty($_FILES[$input])) {
		return [];
	}else{
		$total = count($_FILES[$input]['name']); // multiple files

		for ($i = 0; $i < $total; $i++) {
			$tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
			$fileName = $_FILES[$input]['name'][$i]; // the file name
			$fileSize = $_FILES[$input]['size'][$i]; // the file size
			
			//Make sure we have a file path
			if ($tmpFilePath != ""){
				$ext = substr(strrchr($_FILES[$input]['name'][$i], '.'), 1);
				$dir = __DIR__ . $diretorio;
				$newFilePath = $dir . $nom_origem . "." . $ext;
				$newFileUrl = $diretorio . $nom_origem . "." . $ext;

				if (!file_exists($dir)){
					mkdir($dir, 0777, true);
				}
				if (!file_exists($dir)){
					$out['error'] = "Erro ao criar diret&oacute;rios!";
					return $out;
					exit();	
				}
				
				//ANTIVIRUS
				$arquivo=array('CAMINHO_TMP'=>$tmpFilePath);
				$ret = fnScan($arquivo);
				if (@$ret["RESULTADO"] >= 1){
					$out['error'] = 'Arquivo infectdo com v&iacute;rus: '.@$ret["MSG"];
					return $out;
					exit();
				}

				//Upload the file into the new path
				if(move_uploaded_file($tmpFilePath, $newFilePath)) {
					$fileId = $fileName . $i; // some unique key to identify the file
					$preview[] = $newFileUrl;
					$config[] = [
						'key' => $fileId,
						'caption' => $fileName,
						'size' => $fileSize,
						'downloadUrl' => $newFileUrl, // the url to download the file
						'url' => 'http://localhost/delete.php', // server api to delete the file based on key
					];
					
					// if ($tipo == "convenio"){

						$sql = "INSERT INTO ANEXO_CONVENIO(
											COD_EMPRESA,
											COD_PROVISORIO,
											NOM_ORIGEM,
											NOM_REFEREN,
											COD_CONVENI,
											LOG_STATUS
											$tipo
											) VALUES(
											$cod_empresa,
											$num_contador,
											'".$nom_origem.".".$ext."',
											'$fileName',
											$cod_conveni,
											'$status'
											$cod_objetoanexo
											);";

						// fnEscreve($sql);
											
						mysqli_query(connTemp($cod_empresa,''),$sql);
						
					// }
				} else {
					$errors[] = $fileName;
				}
			} else {
				$errors[] = $fileName;
			}
		}
		$out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config, 'initialPreviewAsData' => true];
		if (!empty($errors)) {
			$img = count($errors) === 1 ? 'file "' . $error[0]  . '" ' : 'files: "' . implode('", "', $errors) . '" ';
			$out['error'] = 'Oh snap! We could not upload the ' . $img . 'now. Please try again later.';
		}
	}
    return $out;
}