<?php
	include '../_system/_functionsMain.php';
    // fnDebug('true');
	
	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['id']));
    $tabela = $_REQUEST['table'];

    if(!is_numeric($_REQUEST['cod_registr'])){
        $cod_registr = fnLimpaCampoZero(fnDecode($_REQUEST['cod_registr']));
    }else{
        $cod_registr = fnLimpaCampoZero($_REQUEST['cod_registr']);
    }

    // fnEscreve($cod_empresa);
	
    $target_dir = "../media/clientes/$cod_empresa/";
    $uploadfile = $target_dir . $_FILES['arquivo']['name'];
    $file_tmp = $_FILES['arquivo']['tmp_name'];
    $imageFileType = pathinfo($uploadfile,PATHINFO_EXTENSION);

    // echo '<pre>';
    // print_r($_FILES);
    // echo '</pre>';

    $arquivo = array(
                'CAMINHO_TMP' => $file_tmp,
                'CONADM' => $connAdm->connAdm()
            );

    $retorno = fnScan($arquivo);

    if($retorno['RESULTADO'] == 0){
  
        $upload=1;
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
        {
          $upload=0;  
        }    
        
        if ($upload == 0) {
            echo "Formato do arquivo nao permitido!";
        
        } else {
            if (!file_exists($target_dir)) { 
                if (!mkdir($target_dir, 0777, true)) { 
                    $error = error_get_last(); 
                    echo $error['message'];
                }
            }
            // chmod($target_dir, 0777); 
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)){
                if($tabela == "TEMPLATE_DOCUMENTO"){
                    $sql = "UPDATE TEMPLATE_DOCUMENTO SET DES_IMAGEM = '".$_FILES['arquivo']['name']."' WHERE COD_TEMPLATE = $cod_registr";
                }else{
                    $sql = "UPDATE MODELOTEMPLATETKT SET DES_IMAGEM = '".$_FILES['arquivo']['name']."' WHERE COD_REGISTR = $cod_registr";
                }
    			
    			$retorno = mysqli_query(connTemp($cod_empresa,""),trim($sql));
    			echo "<div  style='height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;'>";
                echo "<img src='$uploadfile' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>";
    			echo "<input type='file' cod_registr='$cod_registr' accept='text/cfg' class='form-control image-file' name='arquivo' style='display: none;'/>";
    			echo "</div>";
            } 
            else 
             {echo "Houve um problema no upload do arquivo.";}
        }

    }else{

        echo 'Arquivo infectado por: <i>'.$retorno['MSG'].'</i>';

    }
?>
