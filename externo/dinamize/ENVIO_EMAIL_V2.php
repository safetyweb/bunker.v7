<?php
require '../../_system/_functionsMain.php';
require '../../_system/func_dinamiza/Function_dinamiza.php';
$datahoraatual = date('Y-m-d H:i:s');

/*
 agenda_mail
 Enviar a cada evento   = 99
 1 vez no dia           = 1 VEZ AO DIA
 1 vez na semana        = 1 POR SEMANA
 1 vez ao mês           = UM VEZ POR MES
 */
$conadmmysql=$connAdm->connAdm();

$datahoraatual=date('Y-m-d H:i:s');
$horaatual=date('H:i:s');
$numerotentativa=3;
$MINLISTA=1;
$PERMITENEGATIVO='S';
$CONFIRMACAO='N';

//busca de pareceiros comunicação
$sqlpacero="SELECT * FROM senhas_parceiro apar
	    INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
	    WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S' and cod_empresa=77";
$parcerorw=mysqli_query($conadmmysql, $sqlpacero);
while ($parcerors=mysqli_fetch_assoc($parcerorw))
{     
      
    $sql = "SELECT TIP_RETORNO FROM EMPRESAS WHERE COD_EMPRESA =". $parcerors['COD_EMPRESA'];
    //fnEscreve($sql);
    $arrayQuery = mysqli_query($conadmmysql,$sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
    $tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

    if($tip_retorno == 1){$casasDec = 0;}else{$casasDec = 2;}
    //verifica se a empresa esta ativa
    $empresaativa="SELECT cod_empresa,LOG_ATIVO FROM empresas WHERE cod_empresa=".$parcerors['COD_EMPRESA'];
    $empresaatiivarw=mysqli_query($conadmmysql, $empresaativa);
    while($empresaativars= mysqli_fetch_assoc($empresaatiivarw))
    {
        //verifica se a empresa está ativa
        if($empresaativars['LOG_ATIVO']=='S')
        {
            echo '<br>Entrou na lista da empresa<br>';
            $contempmysql=connTemp($parcerors['COD_EMPRESA'],'');
            echo 'ABRE conexao teporaria linha : 39<br>';
           
            //iniciar a configurações de gatilho
            //verificar as configurações do gatilho está ativo
            $gatilho="SELECT * FROM gatilho_email gt
                     INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA
                     INNER  JOIN email_parametros  p ON gt.COD_EMPRESA=p.cod_empresa
                                                     AND gt.COD_CAMPANHA=p.cod_campanha
                    WHERE gt.LOG_STATUS ='S' and gt.TIP_GATILHO IN ('cadastro','resgate','venda')
                    AND gt.cod_empresa=".$parcerors['COD_EMPRESA']."
                    group by gt.COD_CAMPANHA ORDER BY p.COD_LISTA DESC     
                     ";
            $gatilhorw=mysqli_query($contempmysql, $gatilho);
            while ($gatilhors= mysqli_fetch_assoc($gatilhorw))
            {
                echo 'Entrou nos gatilhos linha : 47<br>';
                $lista = $gatilhors['COD_LISTA'];
                $COD_PERSONAS=$gatilhors['COD_PERSONAS'];
                $PCT_RESERVA=$gatilhors['PCT_RESERVA'];

                //verificar se a campanha esta dentro do prazo
                $datetimeINI=$gatilhors['DAT_INI'].' '.$gatilhors['HOR_INI'];
                $datetimeFIM=$gatilhors['DAT_FIM'].' '.$gatilhors['HOR_FIM'];
                if(strtotime($datetimeINI) <= strtotime($datahoraatual) && 
                   strtotime($datetimeFIM) >= strtotime($datahoraatual))
                {
                    echo 'Esta dentro da validade linha : 56<br>';
                    if($gatilhors['LOG_PROCESSA']=='S')
                    {
                       echo 'Campanha está ativa para processamento linha : 59 <br>';
                        
                       //inicio da validação de periodo
                        if($gatilhors['TIP_MOMENTO']!='99')
                        {    
                            if($gatilhors['TIP_MOMENTO']=='1')
                            {                                     
                               $momentoenvio=$gatilhors['HOR_ESPECIF'].':00:00'; 
                              
                            }else{
                                if($gatilhors['DES_PERIODO']=='99')
                                {
                                   $momentoenvio=date('H:i:s');
                                }else{
                                $momentoenvio=$gatilhors['TIP_MOMENTO'].':00:00';                                
                                }    
                                
                            } 
                        }else{
                            $momentoenvio=date('H:i:s');
                        }   
                         //$momentoenvio=date('Y-m-d H:i:s');
                        //verificar se vai entrar na rotina de disparo
                        
                        $horamenor1= date('H:i:s', strtotime('-1 minute', strtotime($momentoenvio)));
                        $horamaior1= date('H:i:s', strtotime('+4 minute', strtotime($momentoenvio)));
                        
                        if(strtotime($horamenor1) <= strtotime($horaatual) && 
                           strtotime($horamaior1)>= strtotime($horaatual))
                        {
                            //iniciar autenticação na dinamize
                            $atenticacaoDInamize=autenticacao_dinamiza($parcerors['DES_USUARIO'],$parcerors['DES_AUTHKEY'],$parcerors['DES_CLIEXT']);
                            $senha_dinamize=$atenticacaoDInamize['body']['auth-token'];
                            
                            echo'entrou na rotina de tempo de disparo linha : 84 <br>';
                            if($gatilhors['TIP_GATILHO']=='cadastro')
                            {    
                               echo '<br>Enviando para cadastro linha : 108<br>';
                                     $tampletevariavel="SELECT   CP.DES_CAMPANHA, 
                                                                            CP.DAT_INI, 
                                                                            CP.HOR_INI,
                                                                            CP.COD_EXT_CAMPANHA, 
                                                                            TE.COD_EXT_TEMPLATE,
                                                                            TE.DES_ASSUNTO,
                                                                            MDE.DES_TEMPLATE AS HTML 
                                                FROM CAMPANHA CP
                                                INNER JOIN mensagem_email ECA ON ECA.COD_CAMPANHA = CP.COD_CAMPANHA
                                                INNER JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ECA.COD_TEMPLATE_EMAIL
                                                INNER JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
                                                WHERE CP.COD_EMPRESA = '".$parcerors['COD_EMPRESA']."'
                                                AND CP.COD_CAMPANHA = '".$gatilhors['COD_CAMPANHA']."'
                                                AND ECA.LOG_PRINCIPAL='S'";
                                                $html=mysqli_fetch_assoc(mysqli_query($contempmysql, $tampletevariavel));                                               
                                                
                                                //gera lista de variaveis
                                                $tagsPersonaliza='{{cmp1}},{{cmp2}},'.procpalavrasV2($html['DES_ASSUNTO'].$html['HTML'],$connAdm->connAdm(),$parcerors['COD_EMPRESA']);
                                                $tagsPersonaliza= explode(',', $tagsPersonaliza);
                                                $contador='0';
                                                foreach ($tagsPersonaliza as $key) {

                                                    $sqlExt = "SELECT VD.COD_EXTERNO, VR.KEY_BANCOVAR,VD.COD_EXTERNO FROM VARIAVEIS_DINAMIZE VD 
                                                                       INNER JOIN VARIAVEIS VR ON VR.COD_BANCOVAR = VD.COD_BANCOVAR
                                                                       WHERE VD.COD_EMPRESA = $parcerors[COD_EMPRESA] AND VD.DES_EXTERNO = '$key'";
                                                    $qrExterno = mysqli_fetch_assoc(mysqli_query($conadmmysql,$sqlExt));
                                                    $tagsDinamize .= '{"Position":"'.$contador.'", "Field":"'.$qrExterno[COD_EXTERNO].'", "Rule":"3"},';
                                                                switch($qrExterno['KEY_BANCOVAR']){

                                                                        case '<#NOME>';
                                                                                $selectCliente .= "SUBSTRING_INDEX(SUBSTRING_INDEX(concat(Upper(SUBSTR(C.NOM_CLIENTE, 1,1)), lower(SUBSTR(C.NOM_CLIENTE, 2,LENGTH(C.NOM_CLIENTE)))), ' ', 1), ' ', -1) AS NOM_CLIENTE, ";
                                                                        break;
                                                                        case '<#CARTAO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#ESTADOCIVIL>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#SEXO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#PROFISSAO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#NASCIMENTO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#ENDERECO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#NUMERO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#BAIRRO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#CIDADE>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#ESTADO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#CEP>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#COMPLEMENTO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#TELEFONE>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#CELULAR>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#SALDO>';

                                                                                $selectCliente .= "FORMAT(IFNULL((
                                                                                                                SELECT IFNULL((
                                                                                                                SELECT  SUM(val_saldo)
                                                                                                                FROM creditosdebitos f
                                                                                                                WHERE f.cod_cliente = cred.cod_cliente AND 
                                                                                                                      f.tip_credito = 'C' AND 
                                                                                                                      f.cod_statuscred = 1 AND 
                                                                                                                                f.tip_campanha = cred.tip_campanha AND 
                                                                                                                                ((f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (f.log_expira = 'N'))),0)+ IFNULL((
                                                                                                                SELECT SUM(val_saldo)
                                                                                                                FROM creditosdebitos_bkp g
                                                                                                                WHERE g.cod_cliente = cred.cod_cliente AND g.tip_credito = 'C' AND g.cod_statuscred = 1 AND g.tip_campanha = cred.tip_campanha AND ((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (g.log_expira = 'N'))),0)
                                                                                                                FROM creditosdebitos cred
                                                                                                                WHERE cred.cod_cliente=C.cod_CLIENTE
                                                                                                                GROUP BY cred.cod_cliente),0),$casasDec,'pt_BR') AS CREDITO_DISPONIVEL, ";
                                                                        break;
                                                                        case '<#PRIMEIRACOMPRA>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#ULTIMACOMPRA>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#TOTALCOMPRAS>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#CODIGO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#CUPOMSORTEIO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#CUPOM_INDICACAO>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#NUMEROLOJA>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#BAIRROLOJA>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#NOMELOJA>';
                                                                                $selectCliente .= "C.COD_UNIVEND,";
                                                                        break;
                                                                        case '<#ENDERECOLOJA>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#TELEFONELOJA>';
                                                                                $selectCliente .= "";
                                                                        break;
                                                                        case '<#ANIVERSARIO>';
                                                                                $selectCliente .= "C.DAT_NASCIME,";
                                                                        break;
                                                                        case '<#DATAEXPIRA>';
                                                                                $selectCliente .= "(SELECT 
                                                                                                                        MIN(DAT_EXPIRA) AS DAT_EXPIRA
                                                                                                                        FROM creditosdebitos 
                                                                                                                            WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
                                                                        break;
                                                                        default:
                                                                                $selectCliente .= "C.DES_EMAILUS,";
                                                                        break;
                                                                }
                                                                $contador++;
                                                }                                                
                                                // verificação das variaveis pra montar o select/arquivos de envio
                                                $selectCliente .= "C.COD_CLIENTE";
                                                $tagsDinamize = rtrim($tagsDinamize,',');	
                                               
					          $sqlcli_cad = "SELECT $selectCliente
							FROM clientes C 							
							WHERE C.COD_EMPRESA = $gatilhors[COD_EMPRESA] 							
                                                        AND C.COD_CLIENTE in (SELECT COD_CLIENTE FROM email_fila WHERE                                                                                                  
                                                                                TIP_GATILHO='".$gatilhors['TIP_GATILHO']."' AND
                                                                                TIP_FILA='2' AND    
                                                                                COD_EMPRESA=".$parcerors['COD_EMPRESA']." AND 
                                                                                COD_CAMPANHA=".$gatilhors['COD_CAMPANHA']." group by COD_CLIENTE)
                                                           ";   
                                                    $rwsql=mysqli_query($contempmysql, $sqlcli_cad);
                                                    $CLIE_CAD= mysqli_fetch_all($rwsql,MYSQLI_ASSOC);                                                  
                                                    while($headers=mysqli_fetch_field($rwsql))
                                                    {
                                                        $headers1[campos][$headers->name]=$headers->name; 
                                                    }
                                                  $arrayfull = array_merge($arrayheders,$arraydados);                                                  
                                                  $nomeRel = $parcerors['COD_EMPRESA'].'_'.date("YmdHis")."_".$gatilhors['des_campanha']."cadastro.csv";                                                
                                                  $caminhoRelat = '/srv/www/htdocs/_system/func_dinamiza/lista_envio/';
                                                  gerandorcvs($caminhoRelat,$nomeRel,";",$CLIE_CAD,$headers1);                                                 
                                                  $arquivodinamize= $caminhoRelat.$nomeRel;               
                                                   //enviar a lista para o dinamize
                                                 
                                                   $retornoContatos = contatos_dinamiza ("$senha_dinamize","$arquivodinamize","$tagsDinamize");
                                                   if($retornoContatos[code_detail]=='Sucesso')
                                                   {
                                                        $cod_mailing_ext = $retornoContatos[body][code];
                                                        $retornoSegmento = FiltroSegmentos("$senha_dinamize", 
                                                                                            $cod_mailing_ext, 
                                                                                            $parcerors['COD_EMPRESA']."_".$gatilhors['COD_CAMPANHA']."_".$gatilhors['des_campanha'] , 
                                                                                            $cod_mailing_ext);

							$cod_ext_segmento = $retornoSegmento[body][code];
                                                               //criar segmento                                                 
                                                               //start envio



                                                               $sqlControle = "INSERT INTO EMAIL_LOTE(
                                                                                                     COD_EXT_SEGMENTO,
                                                                                                     DAT_AGENDAMENTO,     
                                                                                                      COD_CAMPANHA,
                                                                                                      COD_EMPRESA,
                                                                                                      COD_LOTE,                                                                                         
                                                                                                      COD_STATUSUP,
                                                                                                      NOM_ARQUIVO,
                                                                                                      DES_PATHARQ,
                                                                                                      COD_USUCADA,                                                                                                            
                                                                                                      QTD_LISTA,
                                                                                                      COD_PERSONAS,
                                                                                                      COD_LISTA,
                                                                                                      COD_MAILING_EXT,
                                                                                                      ID_CONTROLEIBOPE,
                                                                                                      LOG_ENVIO
                                                                                                  )VALUES(
                                                                                                      '$cod_ext_segmento',
                                                                                                      '".date('Y-m-d H:i:s')."',
                                                                                                      ".$gatilhors['COD_CAMPANHA'].",
                                                                                                      ".$parcerors['COD_EMPRESA'].",
                                                                                                      ".$rscod_lot['cod_lote'].",
                                                                                                      '3',
                                                                                                      '$nomeRel',
                                                                                                      '".$caminhoarquivo.$nomeRel."',
                                                                                                      9999,                                                                                                               
                                                                                                      '".$linhas."',
                                                                                                      '".$COD_PERSONAS."',    
                                                                                                      '".$lista."',
                                                                                                      '".$cod_mailing_ext."',
                                                                                                      '1',
                                                                                                      'S'
                                                                                                      )";                                                       
                                                             // mysqli_query($contempmysql, $sqlControle);    
                                                             echo '<br>'.$sqlControle.'<br>';                  
                                                     //verificar as quantidade inicia programada                                                             

                                                    //fim do IF contador

                                                     //geração de arquivo
                                                     //criação do segmento
                                                     //enviar arquivos
                                                     // startar envio
                                                     //controle de saldo

                                                             echo '<pre>';
                                                             print_r($rsemail_fila);
                                                             echo '</pre>';

                                                   }           
                                   
                                    //fim do looping de cadastro
                            }else{
                                // Os outro registros
                            } 

                        }else{
                            Echo 'Nao tem evento para disporo<br>';
                        }
                    }else{
                        Echo 'Campanha INATIVA';
                    }    
                }else{
                    echo 'Campanha fora da validade<br>';
                }
            }
            
        }else{
            echo 'Empresa Desabilitada';
        }    
        
    }     
}
mysqli_close($conadmmysql);
mysqli_close($contempmysql);
echo 'FIM \n\r<br>';
echo 'time do schecule'.$horamaior .'>='. date('Y-m-d H:i').'<br>';
echo date('Y-m-d').' '.trim($gatilhors['HOR_ESPECIF'].':00');