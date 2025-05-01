<?php
include '../_system/_functionsMain.php';


function fnExportDADOS($conn,$sql){
  
    
$sqlcomand=mysqli_query($conn,$sql);
$dados1.='<table border="1">';

$dados3.='<tr>';
while($namecampos=mysqli_fetch_field($sqlcomand))
{
    $dados3.='<td>'.$namecampos->name.'</td>'; 
}

 while ($sqldadosr= mysqli_fetch_assoc($sqlcomand))
        { 
            
            $dados4.='<tr>';
            foreach ($sqldadosr as  $campo => $fils )
            {
            $dados4.= '<td>'.$fils.'</td>';
            }    
           $dados4.='</tr>';    
        }
$dados3.='</tr>';
$dados2.= '<table>';  
 
$arquivo = "export.xls";
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment;filename=\"{$arquivo}\"");
header("Cache-Control: max-age=0");
/*
   header("Content-type: application/vnd.ms-excel");  
   // Força o download do arquivo
  header("Content-type: application/force-download"); 
   // Seta o nome do arquivo
   header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
   header("Pragma: no-cache");

*/
// Envia o conteúdo do arquivo
echo $dados1;
echo $dados3;
echo $dados4;
echo $dados2;
//return $dados1;
//return $dados3;
//return $dados4;
//return $dados2;
}
$empresa=$_REQUEST['empresacod'];
$sql=$_POST['sql'];
$conn=connTemp($empresa,'');

fnExportDADOS($conn,$sql);
?>

