<?php
include '../_system/_functionsMain.php';
//fnDebug('true');
if(!isset($_SESSION["usuario"])){
        
          header("Location:".fnurl ()."http://bunker.mk");  
     
          exit();
    }else{
     
    }
$conn=$connAdm->connAdm();    
$sql='select HIGH_PRIORITY
            count(Q.CPF) as qtd,
            l.ID,
            l.DATA_HORA,
            l.CPF as original,
            Q.CPF ,
            l.NOME,
            l.SEXO,
            l.DT_NASCIMENTO,
            l.COD_EMPRESA as CONSULTA_ORIGINAL,
            Q.COD_EMPRESA as  CONSULTA_OUTRAS
            from log_cpf l
        inner join log_cpfqtd Q on Q.CPF=l.CPF
         WHERE Q.COD_EMPRESA='.$_POST['OK'].'
        group by Q.CPF,Q.COD_EMPRESA
          HAVING COUNT(Q.CPF) >=1
        order by qtd desc
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
<form method="post" action="relconsulta.do">
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
