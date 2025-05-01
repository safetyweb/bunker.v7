<?php

include '../_system/_functionsMain.php';

$cod_empresa = $_REQUEST['id'];
$typeFile = $_REQUEST['typeFile'];

$diretorioAdicional = empty($_REQUEST['diretorioAdicional']) ? '' : $_REQUEST['diretorioAdicional'] . '/';
$diretorioEnvioDestino = $_REQUEST['diretorio'] . '/' . $cod_empresa . '/' . $diretorioAdicional;

if (isset($_FILES['arquivo'])) {
    $errors = "";
    $file_name =  explode('.', $_FILES['arquivo']['name']);
    $file_name = codificar($file_name[0]);
    $file_size = $_FILES['arquivo']['size'];
    $file_tmp = $_FILES['arquivo']['tmp_name'];
    $file_type = $_FILES['arquivo']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
    $file_encode = $file_name . '.' . $file_ext;

    $arquivo = array(
        'CAMINHO_TMP' => $file_tmp,
        'CONADM' => $connAdm->connAdm()
    );

    $retorno = fnScan($arquivo);

    if ($retorno['RESULTADO'] == 0) {

        if ($typeFile == "img") {
            $extensions = array("jpeg", "jpg", "png");
            if (in_array($file_ext, $extensions) === false) {
                $errors = "Extensão " . $file_ext . " não permitida.";
            }
        } else if ($typeFile == "doc") {
            $extensions = array("doc", "docx", "txt", "xls", "csv");
            if (in_array($file_ext, $extensions) === false) {
                $errors = "Extensão " . $file_ext . " não permitida.";
            }
        } else if ($typeFile == "all") {
            $extensions = array("exe");
            if (in_array($file_ext, $extensions) === true) {
                $errors = "Extensão " . $file_ext . " não permitida.";
            }
        }

        if ($file_size > 2097152) {
            //$errors[]='File size must be excately 2 MB';
        }

        if (empty($errors) == true) {
            if (!file_exists($diretorioEnvioDestino)) {
                // fnEscreve($diretorioEnvioDestino);
                if (!mkdir($diretorioEnvioDestino, 0777, true)) {
                    $error = error_get_last();
                    echo $error['message'];
                }
            }
            // if (!move_uploaded_file($file_tmp, $diretorioEnvioDestino . $file_name)) 

            //              {
            //                  echo "Em manutenção.";
            //                  // print_r($_FILES);
            //                  // fnEscreve($file_tmp);
            //              }
            move_uploaded_file($file_tmp, $diretorioEnvioDestino . $file_encode);
            $response = [
                'success' => true,
                'nome_arquivo' => $file_encode
            ];

            echo json_encode($response);
        } else {
            echo $errors;
        }
    } else {

        echo 'Arquivo infectado por: <i>' . $retorno['MSG'] . '</i>';
    }
}
