<?php
include '../_system/_functionsMain.php';
//fnMostraForm();
//fnDebug('true');
if(!isset($_SESSION["usuario"])){
        
          header("Location:".fnurl ()."http://bunker.mk");  
     
          exit();
    }else{
     
    }

if($_REQUEST['CPF']!=''){
    
    $cpf="and NUM_CGCECPF='".$_REQUEST['CPF']."'";
}else{$cpf='';}

if($_REQUEST['PDV']!=''){
    
    $PDV="and COD_PDV='".$_REQUEST['PDV']."'";
}else{$PDV='';}  
$sql='select * from origemestornavenda
      where cod_empresa='.$_POST['OK'].'
       '.$cpf.'
       '.$PDV.'    
      order by origemestornavenda.COD_ORIGEM desc limit 1000
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
<form method="post" action="relestorno.do">
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
 <br>:PDV:<input type="text"  name="PDV" size="20"/>
 <br>CPF:<input type="text"  name="CPF" size="20"/>
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
