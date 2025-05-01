<?php
function ListaProfissoes($dados) {
    require_once('../../../_system/Class_conn.php');
    include '../../../wsmarka/func/function.php';
  
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dados->dadoslogin->login."', '".fnEncode($dados->dadoslogin->senha)."','','','".$dados->dadoslogin->idcliente."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    //compara os id_cliente com o cod_empresa
   if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
    {    
        //conn user
        $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
        
        
        
        
        //verifica se a empresa ta ativa  
        if($row['LOG_ATIVO']!='S')
        {
             return array('ListaProfissoesResult'=>array( 'msgerro'=> 'A empresa foi desabilitada!' ));   
        }
         //verifica se o usuario esta ativo
        if($row['LOG_ESTATUS']=='N')
        {
            return array('ListaProfissoesResult'=>array('msgerro' => 'Usuario foi desabilitado!'));  
        } 
    }else{ 
         return  array('ListaProfissoesResult'=>array( 'msgerro'=>'Usuario e senha invalido!')); 
    }           
                       $sqlProfi="select * from profissoes";           
                       $prof=mysqli_query($connAdm->connAdm(),$sqlProfi);

                  
                       while ($rsprof = mysqli_fetch_array($prof))
                       { 
                                              
                            $itn[]=array('descricao'=>$rsprof['DES_PROFISS']);
                        } 
                        mysqli_free_result($prof);
                        return array('ListaProfissoesResult'=>array('profissoes'=>
                                                 array('profissao'=>$itn)
                                    ));
                       
                     
              
       
    
     
}
