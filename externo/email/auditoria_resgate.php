<?php
//date_default_timezone_set('Etc/GMT+3');
include '../../_system/_functionsMain.php';
$horaatual=date('H:i:s');
function fnauditoria($email,$nome,$texto,$Subject,$FromName,$conAdm,$conntemp,$cod_empresa) {         

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
             //   $mail->AddCC('diogo_tank@hotmail.com', 'diogo'); 
                // $mail->AddBCC('roberto@gmail.com', 'Roberto');  
                $mail->IsHTML(true);  
                // Charset (opcional) 
                $mail->CharSet = 'UTF-8';  
                // Assunto da mensagem 
                $mail->Subject = "$Subject";  
                // Corpo do email 
                $mail->Body = "$texto";  
                // Opcional: Anexos 
                // $mail->AddAttachment("/home/usuario/public_html/documento.pdf", "documento.pdf");  
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
        
       
   /*    return array('sql'=> $confSmtp,
                     'SQLLOG'=>$logOK,   
                     'msg'=>@$msg,
                     'COnn'=>$conntemp,
                     'array'=>$mailarray);*/
}
// select das configurações


$conadm=$connAdm->connAdm();
//and cod_empresa in (58,125)
//quais empresas enviaram alguma coisa
$sqlenvioconf='SELECT * FROM  alerta_email em
INNER JOIN empresas emp ON emp.cod_empresa=em.COD_EMPRESA AND emp.LOG_ATIVO="S"
WHERE em.cod_tipo=1   order by em.COD_EMPRESA ASC ';
$rsenvioconf= mysqli_query($conadm, $sqlenvioconf);
while ($confenvio= mysqli_fetch_assoc($rsenvioconf))
{ 
unset($email);    
unset($TEXTO);       
            /*
            [COD_ALERTA] => 5
            [COD_EMPRESA] => 58
            [COD_CAMPANHA] => 32
            [DAT_CREATE] => 2019-07-28 20:56:22
            [COD_TIPO] => 
             */

            $cod_empresa= $confenvio['COD_EMPRESA'];
			$NOM_FANTASI= $confenvio['NOM_FANTASI'];
			
            $contempenvio= connTemp($confenvio['COD_EMPRESA'], '');


            //pegar configuração de tempo
            $sqlconfigtime="SELECT * FROM campanharesgate   WHERE 
                          cod_empresa='".$cod_empresa."' AND 
                          QTD_ALERTREG > 0 and  COD_CAMPANHA= '".$confenvio['COD_CAMPANHA']."'";

            $rsdadosconf=mysqli_query($contempenvio, $sqlconfigtime);
            while ($dadosconf=mysqli_fetch_assoc($rsdadosconf))
            {        
                //echo '<pre>';
                //print_r($dadosconf);
                //echo '</pre>';
               $HORA=$dadosconf['HOR_RELINFO'].':00:00';
               //$HORA='08:41:00';
               $periodo=$dadosconf['TIP_RELINFO'];
			   
                //pegar pelo codigo [COD_MAILUSU] os email a ser enviados.
             
                unset($tabconteudo);
                $Email_envio="SELECT GROUP_CONCAT( DISTINCT DES_EMAILUS  SEPARATOR ';') DES_EMAILUS FROM usuarios WHERE COD_EMPRESA=$confenvio[COD_EMPRESA] AND COD_USUARIO IN ($dadosconf[COD_MAILUSU])";  
                $rsemail=mysqli_fetch_assoc(mysqli_query($conadm, $Email_envio));
                $email['email']= @$rsemail['DES_EMAILUS'];
              //  $email['email']='diogo_tank@hotmail.com';
              //  $email['email']='coordenacaoti@markafidelizacao.com.br';
               //=======

                //antifraude
                 $dados_antifraude = "SELECT
                                            A.COD_EMPRESA,
                                            A.cod_cliente, 
                                            A.nom_cliente, 
                                            A.num_cartao, 
                                            A.log_funciona, 
                                            Min(B.dat_cadastr)             AS DAT_CADASTR, 
                                            Sum(val_totprodu)              AS VAL_TOTPRODU, 
                                            Sum(B.val_totvenda)            AS VAL_TOTVENDA, 
                                            (SELECT Sum(val_credito) 
                                             FROM   creditosdebitos 
                                             WHERE  cod_cliente = A.cod_cliente 
                                                    AND cod_statuscred = 3 
                                                    AND tip_credito = 'C') AS VAL_CREDITOS, 
                                            Sum(val_resgate)               AS VAL_RESGATE, 
                                            Count(*)                       AS QTD_VENDAS, 
                                            d.nom_fantasi 
                                     FROM   clientes A, 
                                            vendas B 
                                            LEFT JOIN unidadevenda d ON d.cod_univend = b.cod_univend 
                                     WHERE  A.cod_cliente = B.cod_cliente 
                                            AND B.cod_statuscred = 3 
                                            AND A.cod_empresa = '".$confenvio['COD_EMPRESA']."'
                                            AND cod_avulso != 1 

                                     GROUP  BY A.nom_cliente 
                                     ORDER  BY B.dat_cadastr DESC";
                $rs_antifraude=mysqli_query($contempenvio, $dados_antifraude);
                @$tabl1= '

                                <br/>
                                <h2>'.$cod_empresa.'_'.$NOM_FANTASI.'</h2>
								<h3>Vendas Anti-fraude</h3>
								<table style="width:800; border: 1px solid black;">
                        <tr>
                          <th style="border-bottom: 1px solid black;">Nome Cliente</th>
                          <th style="border-bottom: 1px solid black;">CPF</th> 
                          <th style="border-bottom: 1px solid black;">*</th>
                          <th style="border-bottom: 1px solid black;">DAT.Venda</th>
                          <th style="border-bottom: 1px solid black;">Vl.TotalProduto</th>
                          <th style="border-bottom: 1px solid black;">Vl.TotalVenda</th>
                          <th style="border-bottom: 1px solid black;">Vl.Credito</th>
                          <th style="border-bottom: 1px solid black;">Vl.Resgate</th>
                          <th style="border-bottom: 1px solid black;">Qtd.Vendas</th>
                          <th style="border-bottom: 1px solid black;">Nome Loja</th>
                        </tr>  
                     ';
                while ($antifraude= mysqli_fetch_assoc($rs_antifraude))
                { 
                    @$tabconteudo.= '<tr>
                                                  <td style="border-bottom: 1px solid black;" align="left">'.@$antifraude['nom_cliente'].'</td>
                                                  <td style="border-bottom: 1px solid black;">'.@$antifraude['num_cartao'].'</td> 
                                                  <td style="border-bottom: 1px solid black;">'.@$antifraude['log_funciona'].'</td>
                                                  <td style="border-bottom: 1px solid black;">'.@$antifraude['DAT_CADASTR'].'</td>
                                                  <td style="border-bottom: 1px solid black;">'.fnValor(@$antifraude['VAL_TOTPRODU'],2).'</td>
                                                  <td style="border-bottom: 1px solid black;">'.fnValor(@$antifraude['VAL_TOTVENDA'],2).'</td>
                                                  <td style="border-bottom: 1px solid black;">'.fnValor(@$antifraude['VAL_CREDITOS'],2).'</td>
                                                  <td style="border-bottom: 1px solid black;">'.fnValor(@$antifraude['VAL_RESGATE'],2).'</td>
                                                  <td style="border-bottom: 1px solid black;">'.$antifraude['QTD_VENDAS'].'</th>
                                                  <td style="border-bottom: 1px solid black;" align="left">'.@$antifraude['nom_fantasi'].'</td>
                           </tr>';

                }        

                $tabl2= '</table>';
                  //dados para enviar o email       
                  $dados_auditoria ='SELECT C.NOM_CLIENTE,C.NUM_CARTAO,a.DAT_CADASTR,a.VAL_RESGATE,a.QTD_RESGATE
                                        FROM  auditoria_fraude a
                                        INNER join clientes C ON C.cod_cliente=a.COD_CLIENTE
                                        WHERE a.cod_empresa="'.$confenvio['COD_EMPRESA'].'"';
                $rs_auditoria=mysqli_query($contempenvio, $dados_auditoria);
                if(mysqli_num_rows($rs_auditoria)>='1')
                {   
                    unset($tabl3);
                    unset($tabconteudo1);
                    unset($tabl4);
                        $tabl3 ='
												<h2>'.$cod_empresa.'_'.$NOM_FANTASI.'</h2>
                                                <h3>Alerta de Resgates</h3>
												<table style="width:800; border: 1px solid black;">
                                <tr>
                                  <th style="border-bottom: 1px solid black;">Nome Cliente</th>
                                  <th style="border-bottom: 1px solid black;">CPF</th>                  
                                  <th style="border-bottom: 1px solid black;">DAT.Venda</th>                 
                                  <th style="border-bottom: 1px solid black;">Vl.Resgate</th>
                                  <th style="border-bottom: 1px solid black;">Qtd.Resgate</th>                                 

                                </tr>  
                             ';

                        while ($auditoria= mysqli_fetch_assoc($rs_auditoria))
                        {
                            @$tabconteudo1.='
                                    <tr>
                                      <td style="border-bottom: 1px solid black;" align="left">'.$auditoria['NOM_CLIENTE'].'</td>
                                      <td style="border-bottom: 1px solid black;">'.$auditoria['NUM_CARTAO'].'</td>                  
                                      <td style="border-bottom: 1px solid black;">'.$auditoria['DAT_CADASTR'].'</td>                 
                                      <td style="border-bottom: 1px solid black;">'.fnValor($auditoria['VAL_RESGATE'],2).'</td>
                                      <td style="border-bottom: 1px solid black;">'.$auditoria['QTD_RESGATE'].'</td>  
                                    </tr>  
                             ';
                        } 
                        
                        $tabl4 ='</table>';
                }else{
                    unset($tabl3);
                    unset($tabconteudo1);
                    unset($tabl4);
                }
                                @$estilo ='

                                                <style>
                                                body {font-size: 15px; font-height: normal;}

                                                table, th, td {
                                                  border: 1px solid black;
                                                }

                                                </style>
                                ';
                
                                $TEXTO = @$estilo.@$tabl1.@$tabconteudo.@$tabl2."&nbsp;<br>".@$tabl3.@$tabconteudo1.@$tabl4;
            } 
	
	
if(@$_GET[id]=='1')
{	
    if($TEXTO!='')
    {   
          unset($email);
 	      $email['email']='diogo_tank@hotmail.com;coordenacaoti@markafidelizacao.com.br';
          $periodo=$dadosconf['TIP_RELINFO'];
          $HORA= $HORA.':00:00';

		 echo 'executou as '.$HORA.'------------------------------';   
                 
		 $evio=fnauditoria($email,'teste',"<HTML>".$TEXTO."</HTML>",'Auditoria Bunker','Suporte Bunker',$connAdm->connAdm(),$contempenvio,3);
		 echo '<pre>';
		 print_r($evio);
		 echo '</pre>';
		 echo "<html>".$TEXTO."</html>";
    }
}
        $meses = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
        $diasdasemana = array (1 => "Segunda-Feira",2 => "Terça-Feira",3 => "Quarta-Feira",4 => "Quinta-Feira",5 => "Sexta-Feira",6 => "Sábado",0 => "Domingo");
         $hoje = getdate();
         $dia = $hoje["mday"];
         $mes = $hoje["mon"];
         $nomemes = $meses[$mes];
         $ano = $hoje["year"];
         $diadasemana = $hoje["wday"];
         $nomediadasemana = $diasdasemana[$diadasemana]; 



        if($periodo=='DIA')
        {
             echo date('H:i:s').'<br>'.$HORA.'-'.$cod_empresa.'-'.$periodo.'<br>';
			  
			$horamenor1= date('H:i:s', strtotime('-5 minute', strtotime($HORA)));
			$horamaior1= date('H:i:s', strtotime('+20 minute', strtotime($HORA))); 
            echo '<br>'.$horamenor1.'<='.$horaatual.'&&'.$horamaior1.'>='. $horaatual.'<br>';
			              
			if(strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1)>= strtotime($horaatual))
			{ 
				 echo 'executou as '.$HORA.'------------------------------';   
                                 $cod='0';
                                        if($TEXTO!='')
                                        {    
					  fnauditoria($email,'teste',"<HTML>".$TEXTO."</HTML>",'Auditoria Bunker','Suporte Bunker',$connAdm->connAdm(),$contempenvio,3);
                                       
					  $cod='1';
					  echo "<html>".$TEXTO."</html>";
                                        } 
			} else {
				echo'<br> fora do time <br>';
			   
			}
        } 

        if($periodo=='SEM')
        {
			
            if($diasdasemana[$diadasemana]=="Sexta-Feira")
            {   
	
			   echo '<br>'.$HORA.'-'.$cod_empresa.'-'.$periodo.'<br>';
			  
					$horamenor1= date('H:i:s', strtotime('-5 minute', strtotime($HORA)));
					$horamaior1= date('H:i:s', strtotime('+20 minute', strtotime($HORA)));    
					if(strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1)>= strtotime($horaatual))
					{ 
						 echo 'executou as '.$HORA.'------------------------------';  
                                                   $cod='0';
                                                        if($TEXTO!='')
                                                        { 
                                                            fnauditoria($email,'teste',"<HTML>".$TEXTO."</HTML>",'Auditoria Bunker','Suporte Bunker',$connAdm->connAdm(),$contempenvio,3);
                                                            $cod='1';
                                                            echo "<html>".$TEXTO."</html>";
                                                        }
					} else {
						echo'<br> fora do time <br>';
					   
					}
            }else{
                echo "<br>nao é Sexta-Feira<br>";
            }    
        }    
     
        
        if($periodo=='MES')
        {
            if($dia=="01")
            {  
                 echo '<br>'.$HORA.'-'.$cod_empresa.'-'.$periodo.'<br>';
			  
					$horamenor1= date('H:i:s', strtotime('-5 minute', strtotime($HORA)));
					$horamaior1= date('H:i:s', strtotime('+20 minute', strtotime($HORA)));    
					if(strtotime($horamenor1) <= strtotime($horaatual) && strtotime($horamaior1)>= strtotime($horaatual))
					{ 
						 echo 'executou as '.$HORA.'------------------------------'; 
                                                   $cod='0';
							if($TEXTO!='')
                                                        { 
                                                            fnauditoria($email,'teste',"<HTML>".$TEXTO."</HTML>",'Auditoria Bunker','Suporte Bunker',$connAdm->connAdm(),$contempenvio,3);
                                                            $cod='1';
							   echo "<html>".$TEXTO."</html>";
                                                        } 
					} else {
						echo'<br> fora do time <br>';
					   
					}
            }else{
              echo "nao é dia 01";  
            } 

        }    
        //inserindo dados em outra base de dados.
        if(@$cod=='1')
        {    
                $insert="insert into audit_fraude_Process() SELECT *  FROM  auditoria_fraude a
                         WHERE a.cod_empresa='".$cod_empresa."'";
                $ins=mysqli_query($contempenvio, $insert);
                if (!@$ins)
                    {
                        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                        try {mysqli_query($contempenvio,$insert);} 
                        catch (mysqli_sql_exception $e) {$msgsql= $e; 
                        $msg="Error description audit_fraude_Process: $msgsql";
                        }
                   echo '<br>'.$insert.'<br>'     ;

                }else{

                  $delet= "DELETE FROM auditoria_fraude WHERE cod_empresa='".$cod_empresa."'"; 
                  $rwdel=mysqli_query($contempenvio, $delet);
                    if (!$rwdel)
                    {
                         echo '<br>'.$delet.'<br>';
                    }
                }   
        }      
unset($cod);
        echo '====FIM====';
}