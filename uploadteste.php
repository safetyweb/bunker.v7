<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
 foreach ($_REQUEST as $nome_campo => $valor_campo) {
        //Exibi o campo e o valor contido
        if(is_array($valor_campo)){
            echo $nome_campo." => ";
            print_r($valor_campo); 
            echo "<br />";
         
        }else{
        echo $nome_campo . " => " . $valor_campo . "<br />";
        
        }
    
    }       
function fnupload($destino,$nomecampo){
    $target_dir = "C:\\temp\\".$destino;
    $target_file = $target_dir . basename($_FILES["$nomecampo"]["name"]);
    $uploadOk = 1;
    //verifica se o diretorio existe
    if (!file_exists($target_dir)){
        mkdir("$target_dir", 0770);
        }
        //verifica se o arquivo existe
    if (!file_exists($target_dir)){
        echo "arquivo ja existe!";
        $uploadOk = 0;
       
         }
        
  
    
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
   
   
    // Check file size
    if ($_FILES["$nomecampo"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
   
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["$nomecampo"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["$nomecampo"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }


}

fnupload('7',$_FILES["arquivo"]["name"]);

        ?>
        
        <form action="uploadteste.php" enctype="multipart/form-data" method="POST">
     Selecione o arquivo desejado: <input name="arquivo" size="20" type="file"/>
     <input type="submit" name ='submit' value="submit"/>
     </form>        
    </body>
</html>

