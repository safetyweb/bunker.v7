<?php
include './_system/_functionsMain.php';

// Diogo viado ---------------------------------
$connboardtemp = $Cdashboard->connUser();
$conadmtmp=$connAdm->connAdm ();
$dat_ini = date('Y-m');
$dat_fim = date('Y-m', strtotime('-1 month', strtotime($dat_ini)));
$mes = date('M', strtotime($dat_fim));

$arrMeses = array(
  'Jan' => 'Janeiro',
  'Feb' => 'Fevereiro',
  'Mar' => 'Março',
  'Apr' => 'Abril',
  'May' => 'Maio',
  'Jun' => 'Junho',
  'Jul' => 'Julho',
  'Aug' => 'Agosto',
  'Sep' => 'Setembro',
  'Oct' => 'Outubro',
  'Nov' => 'Novembro',
  'Dec' => 'Dezembro'
);

$indicadorMes = "Confira os indicadores de ".$arrMeses[$mes];
$contador=1;

// verificar se há dados na tabela DASH_CONSULTOR onde o ANO_MES = '$dat_fim'
// os emails vem da USUARIOS, mas os caras que vão receber estão na USUARIOS_RESTRITOS. Tem JOIN
// São dois lotes, cadastrados na mesma tabela. Só muda o tipo: TIP_RESTRIC = 'ADM e TIP_RESTRIC = 'RES'
//and cod_empresa=7
$sql="select * from dash_consultor WHERE ANO_MES='$dat_fim'";
$rw= mysqli_query($connboardtemp, $sql);
while ($rs= mysqli_fetch_assoc($rw)){

    $sqlemail="SELECT 
                        res.COD_EMPRESA, 
                         res.TIP_RESTRIC,
                       -- res.COD_USUARIO,
                        GROUP_CONCAT( DISTINCT 	res.COD_USUARIO SEPARATOR ',')COD_USUARIO,  
                        GROUP_CONCAT( DISTINCT 	us.DES_EMAILUS SEPARATOR ';') DES_EMAILUS 
                from usuarios_restritos res 
                INNER JOIN usuarios us ON us.COD_USUARIO=res.COD_USUARIO
                WHERE 
                 res.COD_EMPRESA =$rs[COD_EMPRESA] AND
                us.LOG_ESTATUS='S' 
                 and us.DES_EMAILUS  REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9._-]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9].[a-zA-Z]{2,63}$'  
                GROUP BY  res.TIP_RESTRIC";
    
    $rwus= mysqli_query($conadmtmp, $sqlemail);
    while($rsus= mysqli_fetch_assoc($rwus))
    {
        $cod_user.=$rsus[COD_USUARIO].',';
        if($rwus->num_rows ==$contador)
        {        
            $cod_user=rtrim($cod_user,',');
            $update="UPDATE email_marka SET COD_USUARIO='$cod_user' WHERE COD_EMPRESA = $rsus[COD_EMPRESA] AND ANO_MES = '$dat_fim'";
            $UP=mysqli_query($connboardtemp, $update);
            if(!$UP)
            {
               echo 'erro: '. $update.'<br>';
            }   
             echo 'OK: '. $update.'<br>';
                   
        }
        $empresa= fnEncode($rsus[COD_EMPRESA]); 
        $lines = file("http://adm.bunker.mk/templateEmail/template_email3.do?id=$empresa&tp=".$rsus[TIP_RESTRIC]);
        foreach ($lines as $line_num => $line) {
               //$htmle.=htmlspecialchars($line);
               $htmle.=$line;
        }
        
        //$teste= json_encode($htmle, JSON_UNESCAPED_UNICODE);
        //$teste1= json_decode($str,JSON_UNESCAPED_UNICODE);
          //  include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
            include './externo/email/envio_sac.php';

            // echo $qrEmp[NOM_FANTASI];

            //   $email['email6'] = $rsus[DES_EMAILUS];
              $email['email6']='diogo_tank@hotmail.com;ricardoaugusto6693@gmail.com';
              $email['email5'] ='rone.all@gmail.com'; 
  
            $retorno = fnsacmail(
                  $email,
                  'Suporte Marka',
                  $htmle,
                  $indicadorMes,
                  "Marka Fidelização",
                  $connAdm->connAdm(),
                  connTemp(7,""),7);


       
       $contador++;
   }
}
?>