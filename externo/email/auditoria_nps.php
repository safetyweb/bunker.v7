<?php
include '../../_system/_functionsMain.php';

function fnenviarelatorio($email,$nome,$texto,$Subject,$FromName,$anexos,$conAdm,$conntemp,$cod_empresa) {         

        //busca de envendo e configuração smtp
        $confSmtp="SELECT * from SENHAS_SMTP WHERE cod_empresa=$cod_empresa and LOG_ATIVO='S' ORDER BY RAND()*".date('s')." LIMIT 1";

        $rsSmtp=mysqli_query($conAdm, $confSmtp);
        while ($resultSmtp = mysqli_fetch_assoc($rsSmtp)) {
          
                $DES_PORT=$resultSmtp['DES_PORT'];
                $DES_CERTIFICADO=$resultSmtp['DES_CERTIFICADO'];
                $TIP_DEBUG=$resultSmtp['TIP_DEBUG'];
                $DES_EMAIL=$resultSmtp['DES_EMAIL'];
                $DES_SENHA=$resultSmtp['DES_SENHA'];
                $DES_SMTP=$resultSmtp['DES_SMTP'];
             
              
                // Inicia a classe PHPMailer 
                $mail = new PHPMailer();         
                // Método de envio 
                $mail->IsSMTP(); 
                // Enviar por SMTP 
                //$mail->Host = "smtp.gmail.com"; 
                $mail->Host = "$DES_SMTP";
                // Você pode alterar este parametro para o endereço de SMTP do seu provedor 
                $mail->Port = $DES_PORT; 
                $mail->SMTPSecure = "$DES_CERTIFICADO"; 
                // Usar autenticação SMTP (obrigatório) 
                $mail->SMTPAuth = true; 
                // Usuário do servidor SMTP (endereço de email) 
                // obs: Use a mesma senha da sua conta de email 
                $mail->Username = "$DES_EMAIL"; 
                $mail->Password = "$DES_SENHA"; 
                // Configurações de compatibilidade para autenticação em TLS 
                //$mail->SMTPOptions = array( 'TLS' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) ); 
                $mail->SMTPDebug = 0;
               //$mail->SMTPDebug = 3;
                $mail->From = "$DES_EMAIL"; 
                $mail->FromName = "$FromName"; 
                $mailarray=explode(';', $email['email']);              
                    foreach ($mailarray as $dados)
                    {                      
                        $mail->AddAddress("$dados");
                      
                    }  
              
                //$mail->AddCC('rone.all@gmail.com', 'rone'); 
                // $mail->AddBCC('roberto@gmail.com', 'Roberto');  
                $mail->IsHTML(true);  
                // Charset (opcional) 
                $mail->CharSet = 'UTF-8';  
                // Assunto da mensagem 
                $mail->Subject = "$Subject";  
                // Corpo do email 
                $mail->Body = "$texto";  
                // Opcional: Anexos 
				foreach($anexos as $anexo){
					$file = "/srv/www/htdocs/relatorios/pdf/$anexo";
					if (file_exists($file)){
						$mail->AddAttachment($file, $anexo);
					}else{
						echo "Erro ao anexar arquivo $file";
						exit;
					}
				}
                // Envia o e-mail 
                $enviado = $mail->Send();


                // Exibe uma mensagem de resultado 
                if ($enviado) 
                { 
                    $msg= "Seu email foi enviado com sucesso!";                  
                    
                } else {                                              
                   $msg= $mail->ErrorInfo;                   

                }
        }
        
       
        /*return array('sql'=> $confSmtp,
                     'SQLLOG'=>$logOK,   
                     'msg'=>@$msg,
                     'COnn'=>$conntemp,
                     'array'=>$mailarray);*/
}
// select das configurações


$conadm=$connAdm->connAdm();

//quais empresas enviaram alguma coisa
$sqlenvioconf='SELECT * FROM  alerta_email WHERE cod_tipo=2';
$rsenvioconf= mysqli_query($conadm, $sqlenvioconf);
// echo $sqlenvioconf;
while ($confenvio= mysqli_fetch_assoc($rsenvioconf))
{    
		echo 'OK';    
            /*
            [COD_ALERTA] => 5
            [COD_EMPRESA] => 58
            [COD_CAMPANHA] => 32
            [DAT_CREATE] => 2019-07-28 20:56:22
            [COD_TIPO] => 
             */
            $cod_empresa= $confenvio['COD_EMPRESA'];

            $contempenvio= connTemp($confenvio['COD_EMPRESA'], '');

            $data_verifica = date("Y-m-d", strtotime('-1 day'));
			/**/
			//$data_verifica = "2020-10-09";
			//$cod_empresa = 7;
			/**/
			list($y,$m,$d) = explode("-",$data_verifica);
			$data_verifica_br = $d."/".$m."/".$y;


            $sqlVerifica = "SELECT DP.COD_PESQUISA, PQ.DES_PESQUISA FROM DADOS_PESQUISA DP
                            INNER JOIN PESQUISA PQ ON PQ.COD_PESQUISA = DP.COD_PESQUISA
                            WHERE DP.COD_EMPRESA = $cod_empresa 
                            AND DP.DT_HORAINICIAL BETWEEN '$data_verifica 00:00:00' AND '$data_verifica 23:59:59'
							AND PQ.LOG_PRINCIPAL='S'
                            GROUP BY DP.COD_PESQUISA";

            $arrayVerifica = mysqli_query(connTemp($cod_empresa,''),$sqlVerifica);
            $countVerifica = mysqli_num_rows($arrayVerifica);

            if($countVerifica > 0){

              // BUSCANDO O NOME_FANTASI DA EMPRESA
              $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
              $arrayEmp = mysqli_query($connAdm->connAdm(), $sqlEmp);
              $qrEmp = mysqli_fetch_assoc($arrayEmp);
              $nom_fantasi = $qrEmp['NOM_FANTASI'];

              //pegar configuração de tempo
              $sqlconfigtime="SELECT UNP.*, US.NOM_USUARIO, US.LOG_USUARIO, US.DES_EMAILUS FROM USUARIOS_NPS UNP
                              INNER JOIN USUARIOS US ON US.COD_USUARIO = UNP.COD_USUARIO
                              WHERE UNP.COD_EMPRESA = $cod_empresa
                              ORDER BY US.NOM_USUARIO";

              $rsdadosconf=mysqli_query($contempenvio, $sqlconfigtime);

              while ($dadosconf=mysqli_fetch_assoc($rsdadosconf))
              {

                $emailaud.=$dadosconf['DES_EMAILUS'].';'; 

              }

              $email['email'] = ltrim(rtrim($emailaud,';'),';');

              if($countVerifica == 1){

                $qrVerifica = mysqli_fetch_assoc($arrayVerifica);

                $link = "https://adm.bunker.mk/action.php?mod=".fnEncode(1274)."&id=".fnEncode($cod_empresa)."&idP=".fnEncode($qrVerifica['COD_PESQUISA'])."&dtI=".fnEncode($data_verifica);
                $texto_pesq = " ";

				  $cod_pesquisa .= $qrVerifica['COD_PESQUISA'];
				  $anexo[$cod_pesquisa] = "relatorio_nps_$cod_pesquisa_".$cod_pesquisa."_".$data_verifica;
				  $url = "https://adm.bunker.mk/relatorios/pdfRelPesquisasDiario.php?id=".fnEncode($cod_empresa)."&idP=".fnEncode($cod_pesquisa)."&DAT_INI=".$data_verifica_br."&DAT_FIM=".$data_verifica_br."&save=true&filename=".$anexo[$cod_pesquisa];
				  $anexo[$cod_pesquisa] = $anexo[$cod_pesquisa].".pdf";
				  $ch = curl_init();
				  curl_setopt($ch, CURLOPT_URL, $url);
				  curl_setopt($ch, CURLOPT_HEADER, 0);
				  curl_exec($ch);
				  curl_close($ch);

              }else{

				$anexo = array();
                while($qrVerifica = mysqli_fetch_assoc($arrayVerifica)){
				  $cod_pesquisa .= $qrVerifica['COD_PESQUISA'];
				  $anexo[$cod_pesquisa] = "relatorio_nps_$cod_pesquisa_".$cod_pesquisa."_".$data_verifica;
				  $url = "https://adm.bunker.mk/relatorios/pdfRelPesquisasDiario.php?id=".fnEncode($cod_empresa)."&idP=".fnEncode($cod_pesquisa)."&DAT_INI=".$data_verifica_br."&DAT_FIM=".$data_verifica_br."&save=true&filename=".$anexo[$cod_pesquisa];
				  $anexo[$cod_pesquisa] = $anexo[$cod_pesquisa].".pdf";
				  $ch = curl_init();
				  curl_setopt($ch, CURLOPT_URL, $url);
				  curl_setopt($ch, CURLOPT_HEADER, 0);
				  curl_exec($ch);
				  curl_close($ch);
                }

              }

              // $email['email'] = "mayco_rolbuche@hotmail.com;rone.all@gmail.com";

              $texto_envio = "          
              <h3 style='font-size: 18px;'>Pesquisa NPS ".$nom_fantasi." - ".fnDataShort($data_verifica)."</h3>
              <span style='font-size: 14px;'>Você tem pesquisas".$texto_pesq."visitadas em <b>".fnDataShort($data_verifica)."</b>. Acesse o relatório para verificar o detalhe das visitas.</span>
              <div style='clear: both; height: 5px;'/>
              <span style='font-size: 14px;'>
              <div style='clear: both; height: 15px;'/>           
              <div style='background-color: #3498DB; padding: 8px 8px 8px 8px; border-radius: 5px; width: 150px; text-align: center; '>
              <a href='".$link."'  
              target='_blank'
              style='font-size: 15px; color: white; text-decoration: none;'
              >&nbsp; Acessar Relatório &nbsp;</a> </div> ";
                 echo "Enviando...";
                 fnenviarelatorio($email,'teste',"<HTML>".$texto_envio."</HTML>","Pesquisa NPS ".$nom_fantasi." - ".fnDataShort($data_verifica),'Suporte Bunker',$anexo,$connAdm->connAdm(),$contempenvio,3);
                 echo "Chegou o email";
        
            }else{
                echo 'Sem envio...';
            }
         
}