<?php
include './_system/_functionsMain.php';
echo fnDebug('true');
  
     $uploaddir = '/var/lib/mysql-files/';
  
     $uploadfile = $uploaddir . $_FILES['arquivo']['name'];
  
     if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $uploadfile)){
  
     echo "Arquivo Enviado";
       $import=" LOAD DATA INFILE '$uploadfile' IGNORE
               INTO TABLE quiztv
              FIELDS TERMINATED BY ';'
              OPTIONALLY ENCLOSED BY '\"' 
             ";
//LINES TERMINATED BY ''
             mysqli_query($connUser->connUser(),$import);
//unlink($uploadfile);

} else {echo "Houve um problema no upload do arquivo.";}

?>
<html>
<body>
<form action="importcvs.php" enctype="multipart/form-data" method="POST">
     Selecione o arquivo desejado: <input name="arquivo" size="20" type="file"/>
     <input type="submit" value="Enviar"/>
     </form>
</body>
</html>



