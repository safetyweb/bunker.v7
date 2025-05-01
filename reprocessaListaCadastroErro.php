<?php
        
        //echo fnDebug('true');
 
$hashLocal = mt_rand();     

if( $_SERVER['REQUEST_METHOD']=='POST' )
{
        $request = md5( implode( $_POST ) );
        
        if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
        {
                $msgRetorno = 'Essa página já foi utilizada';
                $msgTipo = 'alert-warning';
        }
        else
        {
                $_SESSION['last_request']  = $request;
                
                $opcao = $_REQUEST['opcao'];
                $hHabilitado = $_REQUEST['hHabilitado'];
                $hashForm = $_REQUEST['hashForm'];
                
                $cod_usucada = $_SESSION["SYS_COD_USUARIO"];
              
                if ($opcao != ''){                      
                        
                        //mensagem de retorno
                        switch ($opcao)
                        {
                                case 'CAD':
                                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>"; 
                                        break;
                                case 'ALT':
                                        $msgRetorno = "Registro alterado com <strong>sucesso!</strong>";                
                                        break;
                                case 'EXC':
                                        $msgRetorno = "Registro excluido com <strong>sucesso!</strong>";                
                                        break;
                                break;
                        }                       
                        $msgTipo = 'alert-success';
                }                
        }
}

    $COD_EMPRESA = fnDecode($_GET['id']);
    $COD_CLIENTE = fnDecode($_GET['idC']);
    //contemp
    $conntempVAR=connTemp($COD_EMPRESA, '');
    $connadvar=$connAdm->connAdm();
    //=============busca os dados com erro do cliente       
    $sql_cliente="SELECT * FROM CLIENTES WHERE COD_CLIENTE='$COD_CLIENTE'  AND COD_EMPRESA='$COD_EMPRESA'";
    $_rsCliente=mysqli_fetch_assoc(mysqli_query($conntempVAR, $sql_cliente));
    //validar o CPF
    $cpfcompleto=str_pad($_rsCliente['NUM_CGCECPF'], 11, '0', STR_PAD_LEFT); // Resultado: 00009   
if (fnvalidacpf($cpfcompleto) ) {
   
    
    //==Consulta na base intermediaria==========================================
    $baseinter="select * from log_cpf where cpf='".$cpfcompleto."'";
    $_baseintermediaria=mysqli_fetch_assoc(mysqli_query($connadvar, $baseinter));
    if ($_baseintermediaria['CPF']==''){
           echo 'não existe na base!'; 
    }else{
         
        $idadecalc=calc_idade($_baseintermediaria['DT_NASCIMENTO']);
        $arraydata=explode("/", $_baseintermediaria['DT_NASCIMENTO']);
        $NOM_CLIENTE=$_baseintermediaria['NOME'];
        $DAT_NASCIME=$_baseintermediaria['DT_NASCIMENTO'];
        $COD_SEXOPES=$_baseintermediaria['SEXO'];
        //$IDADE=;
        $DIA=$arraydata[0];
        $MES=$arraydata[1];
        $ANO=$arraydata[2];
    }
    //inertinto na base de log para cobrança
    $insertlog="insert into log_cpfqtd (DATA_HORA,
                                        CPF,
                                        NOME,
                                        SEXO,
                                        DT_NASCIMENTO,
                                        COD_EMPRESA,
                                        ID_LOJA,
                                        USUARIO)
                                        value(
                                        '".date('Y-m-d H:i:s')."',
                                        '".$_rsCliente['NUM_CGCECPF']."',
                                        '".$NOM_CLIENTE."',
                                        '".$COD_SEXOPES."',
                                        '".$DAT_NASCIME."',
                                        '".$COD_EMPRESA."',
                                        9999,
                                        '".$_SESSION["SYS_NOM_USUARIO"]."'
                                    )
               ";
    $inslog=mysqli_query($connadvar, $insertlog);                            
    if (!$inslog){
        echo "Não foi possivel processar o cpf! <br>";
    }else{
        if(
            ($COD_SEXOPES==1)||
            ($COD_SEXOPES=='M') || 
            ($COD_SEXOPES=='Masculino')||
            ($COD_SEXOPES=='masculino')     
            ){$sexo=1;}
            elseif (($COD_SEXOPES==2) ||
                    ($COD_SEXOPES=='F') || 
                    ($COD_SEXOPES=='feminino')||
                    ($COD_SEXOPES=='Feminino')
                    )
                    {$sexo=2;} else {$sexo=3;}  
                                                    
        //update para alterar o dados do cliente.
        $updateregistro="UPDATE clientes SET NOM_CLIENTE='$NOM_CLIENTE', 
                                             DAT_NASCIME='$DAT_NASCIME',
                                             COD_SEXOPES= '$sexo',
                                             IDADE='$idadecalc',
                                             DIA='$DIA',
                                             MES='$MES',
                                             ANO ='$ANO'
                                             WHERE  COD_CLIENTE=$COD_CLIENTE and cod_empresa=$COD_EMPRESA";
        
        mysqli_query($conntempVAR, $updateregistro); 
        //recalcular ponto
        
        $pontos="call SP_REGERA_CREDITO_CLIENTE($COD_EMPRESA,$COD_CLIENTE)";
        mysqli_query($conntempVAR, $pontos);  
        //========================================  
        echo '<i class="fas fa-check"></i> CPF ATUALIZADO';
    }

    
    
    
    
    
    
    
    
    
//fim do valida CPF    
} else  {
   echo " CPF ".$_rsCliente['NUM_CGCECPF']." é invalido!";
}
//função de consulta 
// 1 - Primeiro consulta na base intermediaria. log_cpf,log_cpfqtd
// SELECT ID,CPF,NOME,SEXO,DT_NASCIMENTO,COD_EMPRESA FROM log_cpf WHERE cpf='01734200014' 
// 2 - verificar se a data de nascimento e sexo estão preenchidos na base intermediaria.
// 3 - Caso não tenha na base intermediaria ou alguns dados do item 2 estiver vazio ir na ifaro
// 3 - se for na ifaro inserir na base intermediaria e no log para cobrança.
// 4 - item 2 se tiver na ifaro preencher os dados na base intermediaria.
//===================================================================
//função de atualização
// 1 - pegar os dados ja preenchido na base do cliente carregar em uma array.
// 2 - jogar os dados para a função da webservice de atualização de cadastro com os dados corretos.
// 3 - executar a procedure do adilson para classificar e pontuar as vendas.

              
//fnMostraForm();
//fnEscreve($cod_checkli);

?>
        
<?php if ($popUp != "true"){  ?>                                                        
<div class="push30"></div> 
<?php } ?>

<div class="row">                               

        <div class="col-md12 margin-bottom-30">
                <!-- Portlet -->
                <?php if ($popUp != "true"){  ?>                                                        
                <div class="portlet portlet-bordered">
                <?php } else { ?>
                <div class="portlet" style="padding: 0 20px 20px 20px;" >
                <?php } ?>
                
                        <?php if ($popUp != "true"){  ?>
                        <div class="portlet-title">
                                <div class="caption">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                        <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
                                </div>
                                <?php include "atalhosPortlet.php"; ?>
                        </div>
                        <?php } ?>

                        <div class="portlet-body">
                                
                                <?php if ($msgRetorno <> '') { ?>       
                                <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                 <?php echo $msgRetorno; ?>
                                </div>
                                <?php } ?>      
                        
                                <?php if ($popUp != "true"){ $abaFormalizacao = 1089; include "abasFormalizacaoEmp.php"; } ?>
                                
                                <div class="push30"></div> 
                                
                                
                                        <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
                                        
                                                                                                
                                        
                                        </form>

                                                                                                                                
                                
                                <div class="push"></div>
                                
                                </div>                                                          
                        
                        </div>
                </div>
                <!-- fim Portlet -->
        </div>
        
</div>                                  
        
<div class="push20"></div>      
