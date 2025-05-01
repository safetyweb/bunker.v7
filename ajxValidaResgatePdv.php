<?php 

include "_system/_functionsMain.php"; 
include './totem/funWS/Validadescontos.php';

@$cod_empresa=$_GET['COD_EMPRESA'];
@$VAL_RESGATE=$_REQUEST['VAL_RESGATE'];

if($VAL_RESGATE == '0,00'){
    echo '52';
}else{

    @$cpf=$_GET['c1'];
    @$VAL_LIQUIDO=$_REQUEST['total_de_produtos'];
    @$COD_USUARIO=$_REQUEST['COD_USUARIO'];
    @$COD_UNIVEND=$_REQUEST['COD_LOJA'];

    $sql = "select LOG_USUARIO, DES_SENHAUS,COD_UNIVEND from usuarios where cod_empresa = ".$cod_empresa." AND COD_TPUSUARIO=10 and DAT_EXCLUSA is null ";
    //fnEscreve($sql);

    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
    $qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
    $log_usuario = $qrBuscaUsuario['LOG_USUARIO'];								
    $des_senhaus = $qrBuscaUsuario['DES_SENHAUS'];

    $sql = "SELECT NOM_USUARIO, COD_EXTERNO FROM USUARIOS WHERE COD_USUARIO = $COD_USUARIO";
    $qrVend = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

    $arrayCampos=array( '0'=>$log_usuario,
                        '1'=> fnDecode($des_senhaus),
                        '2'=>$COD_UNIVEND,
                        '3'=>'999000',
                        '4'=>$cod_empresa,
                        '5'=>$qrVend['COD_EXTERNO'],
                        '6'=> $qrVend['NOM_USUARIO']
                        );

    // echo '<pre>';
    // print_r($arrayCampos);
    // echo '</pre>';

    $dadosVenda = array('cpfcnpj' => $cpf,
                 'valortotalliquido' => $VAL_LIQUIDO,
                 'valor_resgate' => $VAL_RESGATE
               );
                                                                             
    $retornoResgate = fnValidadesconto($dadosVenda,$arrayCampos);

    if($retornoResgate['coderro'] == 52){
    	
    	echo $retornoResgate['coderro'];

    }else{

    	echo $retornoResgate['msgerro'];
    	
    }

}

?>

		  
		  