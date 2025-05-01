<?php
require '../../_system/_functionsMain.php';
$conadmin=$connAdm->connAdm();
echo '<pre>';
print_r($_POST);
echo '</pre>';
//$contempcliente= connTemp($parametro, $retornoBDNAME)

?>
<html>
    <body>
        <form method="POST" action="setup.php">
            <select name="empresa">
                <?php
                   $sql="select * from empresas";
                   $dadosempresa=mysqli_query($conadmin, $sql);
                   while($cod_empresa=mysqli_fetch_assoc($dadosempresa)){
                       print_r($cod_empresa)
                ?>
                <option value="<?php echo $cod_empresa['COD_EMPRESA']; ?>"><?php echo $cod_empresa['NOM_FANTASI']; ?></option>
                   <?php } ?> 
            </select>
            
            <input type="submit" value="atualizar" name="atualizar">
        </form>    
        
        
    </body>        
</html>    