<?php
include '../_system/_functionsMain.php';
//fnDebug('true');
if(!isset($_SESSION["usuario"])){
        
          header("Location:".fnurl ()."http://bunker.mk");  
     
          exit();
    }else{
     
    }
$conn=$connAdm->connAdm(); 
$sql1='select count(*) as contador
            from log_cpf
             WHERE COD_EMPRESA='.$_POST['OK'].' and
                DATA_hora>="'.date('Y-m-d').' 00:00:01"
        order by ID desc  limit 1000
        ';
$contador=mysqli_fetch_assoc(mysqli_query($conn, $sql1));
echo $contador['contador'];
$sql='select *
            from log_cpf
       
         WHERE COD_EMPRESA='.$_POST['OK'].' and
                DATA_hora>="'.date('Y-m-d').' 00:00:01"
        order by ID desc  limit 1000
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
<form method="post" action="rel_consulta.do">
 Selecione a empresa: <br>
        <select name="OK">
          <option value="1">...........</option>  
            <?php
            
            $empre='select COD_EMPRESA,NOM_EMPRESA from empresas';
            $empreret=mysqli_query($conn , $empre);
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
<form method="post" action="Export_1.php">
    <input type="hidden"  name="sql" value="<?php echo $sql;?>" />    
    <input type="hidden" name="empresacod" value="<?php echo $_POST['OK'];?>" />
<input type="submit" name="EXPORT" value="EXPORT" />

</form>
<?php

//$conn= connTemp($_POST['OK'], '');
//$t=fnExecSql($conn,$sql,'true','false');

//fnTestesql($conn, $sql);
fnRel($conn,$sql);
//fnRel(connTemp(7,''), $sql)
//'.htmlentities($fils).'
?>
