<?php
include '../_system/_functionsMain.php';

//fnDebug('true');
if(!isset($_SESSION["usuario"])){
        
          header("Location:".fnurl ()."https://adm.bunker.mk");  
     
          exit();
    }else{
     
    }
$sql='select STRAIGHT_JOIN  DAT_CADASTR as DATA_CADASTRO,IP,PORTA,
    COD_UNIVEND,COD_EMPRESA as EMPRESA,NOM_USUARIO,
    ID_MAQUINA as MAQUINA,NUM_CGCECPF as CPF,MSG,DES_VENDA  from msg_busca
        inner join origembusca on origembusca.COD_ORIGEM=msg_busca.ID
       where COD_EMPRESA='.$_POST['OK'].'
      order by ID desc limit 1000
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
<form method="post" action="relbusca.do">
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
//fnTestesql($conn, $sql);
fnRel($conn,$sql);
//fnRel(connTemp(7,''), $sql)
$cache->CacheEnd();
?>


