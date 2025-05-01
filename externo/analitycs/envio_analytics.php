<?php
include '../../_system/_functionsMain.php';
include_once '../email/envio_sac.php';
// Diogo viado ---------------------------------
$connboardtemp = $Cdashboard->connUser();
$conadmtmp = $connAdm->connAdm();
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

$indicadorMes = "Confira os indicadores de " . $arrMeses[$mes];


// verificar se há dados na tabela DASH_CONSULTOR onde o ANO_MES = '$dat_fim'
// os emails vem da USUARIOS, mas os caras que vão receber estão na USUARIOS_RESTRITOS. Tem JOIN
// São dois lotes, cadastrados na mesma tabela. Só muda o tipo: TIP_RESTRIC = 'ADM e TIP_RESTRIC = 'RES'
//and cod_empresa=7
$sql = "select * from empresas WHERE  LOG_ATIVO='S'";
$rw = mysqli_query($conadmtmp, $sql);
while ($rs = mysqli_fetch_assoc($rw)) {

      $dashV = "SELECT * FROM tb_fechamento_cliente  WHERE cod_empresa=$rs[COD_EMPRESA] and MESANO='$dat_fim' ORDER BY ID_FECHAMENTO DESC  LIMIT 1";
      $rwdashV = mysqli_query(connTemp($rs['COD_EMPRESA'], ""), $dashV);
      if ($rwdashV->num_rows > 0) {

            $empresa = fnEncode($rs['COD_EMPRESA']);
            $sqlemail = "SELECT 
                          res.COD_EMPRESA, 
                           res.TIP_RESTRIC,
                        CONCAT('22529;',GROUP_CONCAT(DISTINCT res.COD_USUARIO SEPARATOR ',' )) AS COD_USUARIO,  
                        CONCAT('katia@markafidelizacao.com.br;', GROUP_CONCAT(DISTINCT us.DES_EMAILUS SEPARATOR ';')) AS DES_EMAILUS 
                  from usuarios_restritos res 
                  INNER JOIN usuarios us ON us.COD_USUARIO=res.COD_USUARIO
                  WHERE 
                   res.COD_EMPRESA =$rs[COD_EMPRESA] AND
                   res.tip_restric != 'SLD' and    
                   us.LOG_ESTATUS='S' and
                    us.DES_EMAILUS  REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9._-]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9].[a-zA-Z]{2,63}$'  
                  GROUP BY  res.TIP_RESTRIC";
            $rwus = mysqli_query($conadmtmp, $sqlemail);
            while ($rsus = mysqli_fetch_assoc($rwus)) {
                  $cod_user .= $rsus['COD_USUARIO'] . ',';

                  $lines = file("https://adm.bunker.mk/templateEmail/template_email3.do?id=$empresa&tp=" . $rsus['TIP_RESTRIC']);
                  foreach ($lines as $line_num => $line) {
                        $htmle .= $line;
                  }
                  unset($email['email6']);
                  $email['email6'] = $rsus['DES_EMAILUS'];
                  //  $email['email6']='diogo_tank@hotmail.com;ricardoaugusto6693@gmail.com;rone.all@gmail.com';
                  //  $email['email5'] ='rone.all@gmail.com';


                  $retorno = fnsacmail(
                        $email,
                        'Suporte Marka_' . $rs['COD_EMPRESA'],
                        $htmle,
                        $indicadorMes,
                        "Marka Fidelização_" . $rs['COD_EMPRESA'],
                        $connAdm->connAdm(),
                        connTemp(7, ""),
                        7
                  );
                  unset($htmle);
            }
            $cod_user = rtrim($cod_user, ',');
            if ($cod_user != '') {

                  $update = "UPDATE email_marka SET COD_USUARIO='$cod_user' WHERE COD_EMPRESA = $rs[COD_EMPRESA] AND ANO_MES = '$dat_fim'";
                  $UP = mysqli_query($connboardtemp, $update);
                  if (!$UP) {
                        echo 'erro: ' . $update . '<br>';
                  }
                  echo 'OK: ' . $update . '<br>';
                  unset($cod_user);
            }
      }
}
