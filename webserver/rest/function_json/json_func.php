<?php
function fnbusca($array){
    
     //consulta cliente 
   ///////////////////////////////////////////////////////////////////////////////////////////////////
        
                     if(trim($array['cpf'])!=""){$CPF=' and NUM_CGCECPF="'.$array['cpf'].'"';}else{$CPF='';}
                     if(trim($array['cartao'])!=""){$cartao=' and NUM_CARTAO="'.$array['cartao'].'"';} else{$cartao='';}
                     if(trim($array['cpf'])==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];} 
                     $sqlconsultaBase="SELECT count(COD_CLIENTE)as contador, clientes.* FROM clientes where  COD_EMPRESA='".$array['empresa']."' $cartao $CPF"; 
                     $row1 = mysqli_fetch_assoc(mysqli_query($array['ConnB'],$sqlconsultaBase)); 
            
                //PERGUNTA SE O DADOS DO CLIENTES VEM DA BASE OU IFARO
                   if($row1['contador']>=1)
                   {
                     //VOU CARREGAR DA BASE DE DADOS  
                       
                       $nome= $row1['NOM_CLIENTE'];
                       $cpf=$row1['NUM_CGCECPF'];
                       $cnpj=$row1['NUM_CGCECPF'];
                       $NUM_RGPESSO=$row1['NUM_RGPESSO'];
                       $sexo=$row1['COD_SEXOPES'];
                       $dt_nascime=$row1['DAT_NASCIME'];
                       $COD_CLIENTE=$row1['COD_CLIENTE'];
                       $cartao1=$row1['NUM_CARTAO'];
                       $TIP_CLIENTE=$row1['TIP_CLIENTE'];
                       $NOM_CLIENTE=$row1['NOM_CLIENTE'];
                       $COD_ESTACIV=$row1['COD_ESTACIV'];
                       $NUM_TELEFON=$row1['NUM_TELEFON'];
                       $DES_EMAILUS=$row1['DES_EMAILUS'];
                       $COD_PROFISS=$row1['COD_PROFISS'];
                       $DAT_CADASTR=$row1['DAT_CADASTR'];
                       $DES_ENDEREC=$row1['DES_ENDEREC'];
                       $NUM_ENDEREC=$row1['NUM_ENDEREC'];
                       $DES_BAIRROC=$row1['DES_BAIRROC'];
                       $DES_COMPLEM=$row1['DES_COMPLEM'];
                       $NOM_CIDADEC=$row1['NOM_CIDADEC'];
                       $COD_ESTADOF=$row1['COD_ESTADOF'];
                       $NUM_CEPOZOF=$row1['NUM_CEPOZOF'];
                       $NUM_CARTAO=$row1['NUM_CARTAO'];
                       $DAT_ALTERAC=$row1['DAT_ALTERAC'];
                       $NUM_CELULAR=$row1['NUM_CELULAR'];
                       
                       

                       $msg='Cliente localizado na base de dados!';
                       $cod_msg='14';
                       fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpf,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                    }else{
                    //AQUI VOU CARREGAR IFARO    

                       if($array['consultaativa'] == 'S'){

                                   if ( valida_cpf($array['cpf']) ) 
                                    {
                                       //busco no log cadastrado da ifaro
                                       //================================================
                                       
                                       $sqlifaro="select count(CPF) as TEM,log_cpf.* from log_cpf where CPF = '".$array['cpf']."'";
                                       $resultifaro=mysqli_query($array['conn'], $sqlifaro);
                                       $rowifaro=mysqli_fetch_assoc($resultifaro);
                                        if($rowifaro['TEM'] != 0)
                                        {
                                            $nome=$rowifaro['NOME'];
                                            $cpf=$rowifaro['CPF'];
                                            if($rowifaro['SEXO']=='M'){$sexo='1';}else{$sexo='2';}  
                                            $dt_nascime=$rowifaro['DT_NASCIMENTO'];
                                            //if($rowifaro['COD_EMPRESA']!=$array['empresa'])
                                           // {    
                                            $intermediaria1="INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                  VALUES 
                                                                                  ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '$cpf','$nome' ,'$sexo', '$dt_nascime', '".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."');";
                                            mysqli_query($array['conn'],$intermediaria1);
                                            //}              
                                            
                                            $msg='Consulta Interna OK!';
                                            $cod_msg='0';
                                            fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                                       
                                            
                                        }else{    
                                                    //buscar na base de erro se o cpf ja existe.
                                                    $sql="select * from cpf_invalido where CPF='".$array['cpf']."'";
                                                    $retornocpf=mysqli_fetch_assoc(mysqli_query($array['conn'], $sql));
                                                    if($retornocpf['CPF']=='')
                                                    {    
                                                         //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                                                         include '../../wsmarka/func/func_ifaro.php';  
                                                         $resultIfaro=ifaro($array['cpf']);
                                                         $resultIfaro=array_filter($resultIfaro);
                                                          if ($resultIfaro['msg']==1){
                                                            //grava log de erro 
                                                            $insertlog="INSERT INTO cpf_invalido (IP,CPF,COD_EMPRESA)VALUES ('".$_SERVER['REMOTE_ADDR']."','".$array['cpf']."','".$array['empresa']."')";
                                                            mysqli_query($array['conn'], $insertlog);  
                                                            $msg='Nenhum cadastro encontrado';
                                                            fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                                            return  array( 'msgerro' => $msg,
                                                                           'coderro'=>'13');
                                                            exit(); 

                                                          }else{	

                                                                  $nome=$resultIfaro['nome'];
                                                                  $cpf=$resultIfaro['cpf'];
                                                                  if($resultIfaro['sexo']=='M'){$sexo='1';}else{$sexo='2';}  


                                                                if($resultIfaro['coderro']=='250')
                                                                {
                                                                    $msg=$resultIfaro['msg'];
                                                                    $cod_msg='250';
                                                                    $dt_nascime=$resultIfaro['datanascimento'];

                                                                    $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,Time_consulta,msg,SEXO,DT_NASCIMENTO) values
                                                                                              ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','0','0','".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."','".$resultIfaro['timeCo']."','".$resultIfaro['msg']."','".$resultIfaro['sexo']."','".$resultIfaro['datanascimento']."')";
                                                                     mysqli_query($array['conn'],$sql);
                                                                }else{  
                                                                        $dt_nascime=$resultIfaro['datanascimento'];
                                                                        $sql="insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,Time_consulta,msg,SEXO,DT_NASCIMENTO) value
                                                                                                  ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."','".$resultIfaro['cpf']."','".$resultIfaro['nome']."','".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."','".$resultIfaro['timeCo']."','".$resultIfaro['msg']."','".$resultIfaro['sexo']."','".$resultIfaro['datanascimento']."')";
                                                                        mysqli_query($array['conn'],$sql);
                                                                        $intermediaria="INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                                        VALUES 
                                                                                                        ('".date("Y-m-d H:i:s")."','".$_SERVER['REMOTE_ADDR']."', '".$resultIfaro['cpf']."','".$resultIfaro['nome']."' ,'".$resultIfaro['sexo']."', '".$resultIfaro['datanascimento']."', '".$array['empresa']."','".$array['login']."','".$array['idloja']."','".$array['idmaquina']."');";
                                                                        mysqli_query($array['conn'],$intermediaria);
                                                                        $msg=$resultIfaro['msg'];
                                                                        $cod_msg='12';
                                                                }
                                                                 fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor','CONSULTA IFARO',$array['LOG_WS']);
                                                                }
                                                    }elseif ($retornocpf['CPF']==$array['cpf']) {
                                                            $msg='Nenhum cadastro encontrado';
                                                            fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                                            return  array( 'msgerro' => $msg,
                                                                           'coderro'=>'13');    
                        
                                                    }    
                                                            
                                          //===================================================================                  
                                        }
                                        
                                    }else{
                                        $msg='Nenhum cadastro encontrado';
                                        fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$array['cpf'],$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$msg,$array['LOG_WS']);
                                        return  array( 'msgerro' => $msg,
                                                       'coderro'=>'13');
                                                               
                                    }    
                        } 
                   }  
           
///////////////////////////////////////////////////////////////////////////////////////////////////////////////  
        $arraydadosBase=array(  'COD_CLIENTE'=>$row1['COD_CLIENTE'],
                                'nome' => $nome, 
                                'cartao'=>$cartao1,
                                'cpf' => $cpf,
                                'rg'=>$NUM_RGPESSO,
                                'tipocliente'=>$TIP_CLIENTE,
                                'cnpj'=>$cnpj,
                                'nomeportador'=>$NOM_CLIENTE,
                                'grupo'=>'',
                                'sexo'=>$sexo,
                                'datanascimento'=>$dt_nascime,
                                'estadocivil'=>$COD_ESTACIV,
                                'telresidencial'=>$NUM_TELEFON,
                                'telcomercial'=>'',
                                'telcelular'=>$NUM_CELULAR,
                                'email'=>$DES_EMAILUS,
                                'profissao'=>$COD_PROFISS,
                                'clientedesde'=>$DAT_CADASTR,
                                'endereco'=>$DES_ENDEREC,
                                'numero'=>$NUM_ENDEREC,
                                'bairro'=>$DES_BAIRROC,
                                'complemento'=>$DES_COMPLEM,
                                'cidade'=>$NOM_CIDADEC,
                                'estado'=>$COD_ESTADOF,
                                'cep'=>$NUM_CEPOZOF,
                                'cartaotitular'=>$NUM_CARTAO,
                                'bloqueado'=>'',
                                'motivo'=>'',
                                'dataalteracao'=>$DAT_ALTERAC,
                                'adesao'=>'',
                                'codatendente'=>'',
                                'senha'=>'',
                                'fontedados'=>'',
                                'retornoGenerico'=>'',
                                'coderro'=> $cod_msg,
                                'msgerro' => $msg
                                ); 
    
                             
return $arraydadosBase; 
              
//return $sqlconsultaBase;
       
}
function gravajsonPOST(){
    foreach ($_REQUEST as $nome_campo => $valor_campo) {
        //Exibi o campo e o valor contido
        if(is_array($valor_campo)){
            $return[]=array($nome_campo=>$valor_campo);
    
                     
        }else{
          $return[]=array($nome_campo=>$valor_campo);
        
        }
    
    }
    
    return $return;

} 