<?php

include '../_system/_functionsMain.php';

$cod_empresa = $_REQUEST['id'];
$typeFile = $_REQUEST['typeFile'];

$diretorioAdicional = empty($_REQUEST['diretorioAdicional']) ? '' : $_REQUEST['diretorioAdicional'] . '/';
$diretorioEnvioDestino = $_REQUEST['diretorio'] . '3/' . $diretorioAdicional . $cod_empresa . '/';

if (isset($_FILES['arquivo'])) {
    $errors = "";
    $file_name = $_FILES['arquivo']['name'];
    $file_size = $_FILES['arquivo']['size'];
    $file_tmp = $_FILES['arquivo']['tmp_name'];
    $file_type = $_FILES['arquivo']['type'];
    // $file_ext = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
    $arquivo_nome = explode('.', $_FILES['arquivo']['name']);
    $file_ext = strtolower(end($arquivo_nome));

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
            $extensions = array("doc", "docx", "txt", "xls");
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
                // chown($dirPermit, 'adm');
                mkdir($diretorioEnvioDestino, 0777);
            }
            // if (!move_uploaded_file($file_tmp, $diretorioEnvioDestino . $file_name)) 

            //              {
            //                  echo "Em manutenção.";
            //                  // print_r($_FILES);
            //                  // fnEscreve($file_tmp);
            //              }
            move_uploaded_file($file_tmp, $diretorioEnvioDestino . $file_name);
            //fnEscreve($diretorioEnvioDestino . $file_name);
            echo $errors;
        } else {
            echo $errors;
        }
    } else {

        echo 'Arquivo infectado por: <i>' . $retorno['MSG'] . '</i>';
    }
}
