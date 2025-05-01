<?php
include '../_system/_functionsMain.php';

//echo file_get_contents("php://input");
//$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
//$server->service($HTTP_RAW_POST_DATA);

 
//fnDebug('true');

if(!isset($_SESSION["usuario"])){
        
          header("Location:".fnurl ()."http://bunker.mk");  
     
          exit();
    }else{
     
    }
$sql='select DAT_CADASTR as DATA_CADASTRO,IP,PORTA,COD_UNIVEND,COD_EMPRESA as EMPRESA,NOM_USUARIO,ID_MAQUINA as MAQUINA,NUM_CGCECPF as CPF,COD_PDV,MSG,DES_VENDA,origem_retorno 
       from origemvenda
      inner join msg_venda on origemvenda.COD_ORIGEM=msg_venda.ID
      where COD_EMPRESA='.$_POST['OK'].'
      order by origemvenda.COD_ORIGEM desc limit 500
        '; 


//$conn=$connREL->connREL();



?>

<style>
  table {
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid orange;
    padding: 10px;
    text-align: left;
  }
</style>
<form method="post" action="relvenda.do">
 Selecione a empresa: <br>
        <select name="OK">
          <option value="1">...........</option>  
            <?php
            
            $empre='select COD_EMPRESA,NOM_EMPRESA from empresas';
            $empreret=mysqli_query($connAdm->connAdm() , $empre);
            while ($emprearray=mysqli_fetch_assoc($empreret))
            {        
            ?>      
        
        <option value="<?php echo $emprearray['COD_EMPRESA'];?>"><?php echo $emprearray['COD_EMPRESA'].'-'.$emprearray['NOM_EMPRESA']; ?></option>
        
        <?php
            }
        ?>
            
    </select>   

<input type="submit" name="BUSCAR" value="BUSCAR" />
   
</form>    
<form method="post" action="Export.php">
    <input type="hidden"  name="sql" value="<?php echo $sql;?>" />    
    <input type="hidden" name="empresacod" value="<?php echo $_POST['OK'];?>" />
<input type="submit" name="EXPORT" value="EXPORT" />

</form>
<?php

$conn= connTemp($_POST['OK'], '');
//$t=fnExecSql($conn,$sql,'true','false');

//fnTestesql($conn, $sql);
fnRel($conn,$sql);
//fnRel(connTemp(7,''), $sql)
//'.htmlentities($fils).'
?>
