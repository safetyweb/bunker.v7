<?php
include '../_system/_functionsMain.php';
//fnDebug('true');
if(!isset($_SESSION["usuario"])){
        
          header("Location:".fnurl ()."http://bunker.mk");  
     
          exit();
    }else{
       
    }
  $sql1="select count(*) as contador from log_cpf where COD_EMPRESA=".$_POST['OK'];
  $empreretcount=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm() , $sql1));
  $countempresa="total Por empresa=".fnValor($empreretcount['contador'],2);
  //geral
   $sqlgeral="select count(*) as contador from log_cpf";
  $empreretcountg=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm() , $sqlgeral));
  $countempresag="Total de consultas=".fnValor($empreretcountg['contador'],2);
  //ultima empresa a consultar
  $ultaconsultar="select ID,COD_EMPRESA from log_cpf order by ID desc limit 1";
  $ultaconsultarR=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm() , $ultaconsultar));
  $countempresagr="Ultima Empresa a consultar=".$ultaconsultarR['COD_EMPRESA'];
  
$sql='select * from log_cpf
      where COD_EMPRESA='.$_POST['OK'].' order by ID desc limit 100';   
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
<form method="post" action="ifaro.do">
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
<form method="post" action="Export_1.php">
    <input type="hidden"  name="sql" value="<?php echo $sql;?>" />    
    <input type="hidden" name="empresacod" value="<?php echo $_POST['OK'];?>" />
<input type="submit" name="EXPORT" value="EXPORT" />

</form>
<?php
echo @$countempresa."<br>";
echo @$countempresag."<br>";
echo @$countempresagr."<br>";
$conn=$connAdm->connAdm();
//fnTestesql($conn, $sql);
fnRel($conn,$sql);
//fnRel(connTemp(7,''), $sql)
//'.htmlentities($fils).'
?>
