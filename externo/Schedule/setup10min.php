<?php
require '../../_system/_functionsMain.php';
ob_start();
date_default_timezone_set('Etc/GMT+3');
 //carga ma lista blacklist sms
$horaatual=date('H:i:s');
$horaatual1=date('d H:i');
echo '<br>'.$horaatual.'<br>'; 
//dinamize
     $emailv ="SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                                INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'          
				WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S'";
    $rwmv=mysqli_query($connAdm->connAdm(), $emailv);
    while($rsmv= mysqli_fetch_assoc($rwmv)){   
        $vpmroc="SELECT * FROM controle_envio where COD_COMUNICACAO=1 and  cod_empresa=".$rsmv[COD_EMPRESA];
        $rwmvproc=mysqli_query($connAdm->connAdm(), $vpmroc);
        if($rwmvproc->num_rows <= 0)
        {  
            $inemail="INSERT INTO controle_envio (COD_EMPRESA, LOG_ATIVO, COD_COMUNICACAO) VALUES (".$rsmv['COD_EMPRESA'].", 1, 1);";
            mysqli_query($connAdm->connAdm(), $inemail);

            $curl = curl_init();
              curl_setopt_array($curl, array(
              CURLOPT_URL => 'http://externo.bunker.mk/dinamize/ENVIO_EMAIL.php',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 100,
              CURLOPT_TIMEOUT => 60000,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "",
              CURLOPT_HTTPHEADER => array(
                "Postman-Token: ba34fc31-389a-447e-84e2-4d12b0e2982d",
                "cache-control: no-cache"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
              echo $response;
            }
            //delete o processo
            $procdm="DELETE FROM controle_envio WHERE COD_COMUNICACAO=1 and  cod_empresa=".$rsmv[COD_EMPRESA];
            mysqli_query($connAdm->connAdm(), $procdm);
       }
    }    
echo "fim";