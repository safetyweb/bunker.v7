<?php

include '../_system/_functionsMain.php';

$cod_empresa = fnDecode($_REQUEST['id']);
$cod_cliente = fnDecode($_REQUEST['idC']);
$typeFile = $_REQUEST['typeFile'];


// $diretorioAdicional = empty($_REQUEST['diretorioAdicional']) ? '' : $_REQUEST['diretorioAdicional'] . '/';
$diretorioEnvioDestino = '../media/clientes/' . $cod_empresa . '/perfil/';
// $dirPermit = '../media/clientes/' . $cod_empresa;

// fnEscreve($cod_empresa);
// fnEscreve($diretorioEnvioDestino);

if (isset($_FILES['webcam'])) {
    $errors = "";
    $file_name = $cod_cliente."_webcam.jpg";
    $file_size = $_FILES['webcam']['size'];
    $file_tmp = $_FILES['webcam']['tmp_name'];
    $file_type = $_FILES['webcam']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['webcam']['name'])));

    $arquivo = array(
                'CAMINHO_TMP' => $file_tmp,
                'CONADM' => $connAdm->connAdm()
            );

    $retorno = fnScan($arquivo);

    if($retorno['RESULTADO'] == 0){

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
                mkdir($diretorioEnvioDestino, 0777, true);
                // chown($dirPermit, 'adm');
                // fnEscreve($diretorioEnvioDestino);
            }
           // if (!move_uploaded_file($file_tmp, $diretorioEnvioDestino . $file_name)) 
                        
           //              {
           //                  echo "Em manutenção.";
           //                  // print_r($_FILES);
           //                  // fnEscreve($file_tmp);
           //              }
            move_uploaded_file($file_tmp, $diretorioEnvioDestino . $file_name);
            // fnEscreve($diretorioEnvioDestino . $file_name);
            echo $errors;
        } else {
            echo $errors;
        }

    }else{

        echo 'Arquivo infectado por: <i>'.$retorno['MSG'].'</i>';

    }

}
?>
