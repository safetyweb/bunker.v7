<?php
include '../_system/_functionsMain.php';
 $connadmtemp=$connAdm->connAdm();

 @$login  = fnLimpaCampo($_GET['login']);
 @$senha  = fnEncode(fnLimpaCampo($_GET['senha']));
 @$idloja = fnLimpaCampo($_GET['loja']);
 @$COD_EMPRESA = fnLimpaCampo($_GET['idcliente']);
 @$Nome_cliente = fnLimpaCampo(fnAcentos($_GET['NOME']));
if($Nome_cliente=='')
{
    $json = array("msg" =>"Por favor digite um nome!",
                    "cod_msg"=>'7'
                   );
    header('Content-type: application/json');
    echo  json_encode($json);
    exit();
}  
if(strlen($Nome_cliente)< 2)
{    
     $json = array("msg" =>"Nao pode conter menos de 2 letras!",
                    "cod_msg"=>'8'
                   );
    header('Content-type: application/json');
    echo  json_encode($json);
    exit();
}



 $sql_aut="SELECT * FROM usuarios WHERE COD_EMPRESA=$COD_EMPRESA AND LOG_USUARIO='$login' AND DES_SENHAUS='$senha'";
 $resultsql=mysqli_fetch_assoc(mysqli_query($connadmtemp, $sql_aut));
 
    if(isset($resultsql['COD_USUARIO']))
    {
        if($resultsql['COD_EXCLUSA']=='0')
        {  
            
            $blacklist='blabla,teatse,TESTER,asd,teste,test,fail,falha,falhou,nome,name,nada,vazio,@,!,#,$,%,¨,&,*,(,),+,=,{,[,},],_,©';
            $arrayblacklist=explode(',', $blacklist);
        
            foreach ( $arrayblacklist as $key) {
                $pos = strpos($Nome_cliente, $key);
                if ($pos !== false) { 
                  $json = array("msg" =>"Nome com caracter invalido!",
                                  "cod_msg"=>'9'
                                      );
                        header('Content-type: application/json');
                        echo  json_encode($json);
                        exit();
                }
            }
            
            foreach ($arrayblacklist as $dados)
            {
               if($dados==$Nome_cliente)
               {
                    $json = array("msg" =>"Por favor digite um nome valido!",
                                  "cod_msg"=>'4','teste'=>$dados
                                      );
                        header('Content-type: application/json');
                        echo  json_encode($json);
                        exit();

               }    
            }   
                       
            $sqlclientes="SELECT * FROM log_cpf WHERE NOME LIKE '$Nome_cliente%' LIMIT 51";
            $rwclientes= mysqli_query($connadmtemp, $sqlclientes);
            if($rwclientes->num_rows > 0)
            {
                while ($rsclientes= mysqli_fetch_assoc($rwclientes))
                {
                    if($rsclientes['SEXO']=='M' || $rsclientes['SEXO']=='m')
                    {
                      $SEXO[SEXO]['M'][]=$rsclientes['SEXO'];

                    }elseif($rsclientes['SEXO']=='F' || $rsclientes['SEXO']=='f')
                    {
                       $SEXO[SEXO]['F'][]=$rsclientes['SEXO'];
                    }    
                }
                $sexoM=count($SEXO[SEXO]['M']);
                $sexoF=count($SEXO[SEXO]['F']);
                $sexoperM=($sexoM * '1.96');
                $sexoperF=($sexoF * '1.96');
                if($sexoperM >= $sexoperF)
                {
                    $sexoperC=$sexoperM;
                    $sexoPES=$SEXO[SEXO]['M'][0];
                }else{
                    $sexoperC=$sexoperF;
                    $sexoPES=$SEXO[SEXO]['F'][0];
                }    

                $json = array("msg" =>"Opa,achamos o nome! ;-)",
                              "cod_msg"=>'3',
                              "PorcentagemAcerto"=>$sexoperC.'%',
                              "Sexo_maiorPorcentagem"=> $sexoPES
                                      );
                header('Content-type: application/json');
                echo  json_encode($json);
                exit();
            }else{
                
                $Nome_cliente=substr("$Nome_cliente", 0, 3);
                $sqlclientes="SELECT * FROM log_cpf WHERE NOME LIKE '$Nome_cliente%' LIMIT 51";
               
                $rwclientes= mysqli_query($connadmtemp, $sqlclientes);
                while ($rsclientes= mysqli_fetch_assoc($rwclientes))
                {
                    if($rsclientes['SEXO']=='M' || $rsclientes['SEXO']=='m')
                    {
                      $SEXO[SEXO]['M'][]=$rsclientes['SEXO'];

                    }elseif($rsclientes['SEXO']=='F' || $rsclientes['SEXO']=='f')
                    {
                       $SEXO[SEXO]['F'][]=$rsclientes['SEXO'];
                    }    
                }
                $sexoM=count($SEXO[SEXO]['M']);
                $sexoF=count($SEXO[SEXO]['F']);
                $sexoperM=($sexoM * '1.96');
                $sexoperF=($sexoF * '1.96');
                if($sexoperM >= $sexoperF)
                {
                    $sexoperC=$sexoperM;
                    $sexoPES=$SEXO[SEXO]['M'][0];
                }else{
                    $sexoperC=$sexoperF;
                    $sexoPES=$SEXO[SEXO]['F'][0];
                }    

                $json = array("msg" =>"Opa,achamos o nome! ;-)",
                              "cod_msg"=>'3',
                              "PorcentagemAcerto"=>$sexoperC.'%',
                              "Sexo_maiorPorcentagem"=> $sexoPES
                                      );
                header('Content-type: application/json');
                echo  json_encode($json);
                exit();
           
            }
        }else{
            
            $json = array("msg" =>"Usuario Excluido!",
                          "cod_msg"=>'2'
                                  );
            header('Content-type: application/json');
            echo  json_encode($json); 
            exit();
        }    
        
        
    } else{
        
        $json = array("msg" =>"Falha na autenticação",
                      "cod_msg"=>'1'
                              );
       header('Content-type: application/json');
        echo  json_encode($json); 
        exit();
    }
