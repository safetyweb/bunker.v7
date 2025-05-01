<?php
//138,142,97
include '../../_system/_functionsMain.php';
include '../../webserver/func/func_ifaro.php';
//fnDebug('true');
$contemporaria=connTemp(19,'');
$conadmf=$connAdm->connAdm ();
$cliente="SELECT * FROM clientes WHERE 
cod_empresa=19 AND dat_nascime='01/01/1980' AND COD_ALTERAC IS null and DAT_ALTERAC IS null
 ORDER BY cod_cliente DESC ";
$rs=mysqli_query($contemporaria, $cliente);
$contador=0;
while ($row = mysqli_fetch_assoc($rs)) 
{   
       ob_start();
    
    //verificar se ja existe na base de dadosqual
	$cpflocal="SELECT * FROM log_cpf WHERE CPF='".fncompletadoc($row['NUM_CGCECPF'])."'";
	$rslocal=mysqli_fetch_assoc(mysqli_query($conadmf,$cpflocal));
	if($rslocal[DT_NASCIMENTO]!='')
    {
		$ano=explode("/", $rslocal[DT_NASCIMENTO]);          

          if($dadosret['0']['sexo']=='M')
          {$sexo='1';}else{$sexo='2';} 
          $update='UPDATE clientes SET  dat_nascime="'.$rslocal[DT_NASCIMENTO].'",                                      
                                        DIA="'.$ano['0'].'",
                                        MES="'.$ano['1'].'",
                                        ANO="'.$ano['2'].'",
									   COD_ALTERAC="1",
									   DAT_ALTERAC="'.date('Y-m-d H:i:s').'"			
                  WHERE  num_cgcecpf="'.$row['NUM_CGCECPF'].'";';
          echo 'LOCAL:..'.$row['NUM_CGCECPF'].'<br>';
          mysqli_query($contemporaria, $update);
		
		   //insert into para cobrança
          $cobranca="insert INTO log_cpfqtd 
              (IP,DATA_HORA,CPF,NOME,SEXO,DT_NASCIMENTO,COD_EMPRESA,ID_LOJA,ID_MAQUINA)
             VALUES ('".$_SERVER['REMOTE_ADDR']."',
                     '".date('Y-m-d H:i:s')."',
                     '".$rslocal[CPF]."',   
                     '".$rslocal[NOME]."',
                     '".$rslocal[SEXO]."',    
                     '".$rslocal[DT_NASCIMENTO]."', 
                    19,
                      '".$row['COD_UNIVEND']."',
                      'Atualização dataqualit' )";
          mysqli_query($conadmf, $cobranca);
		
	}else{
      echo 'IFARO:..'.$row['NUM_CGCECPF'].'<br>';		
       	$dadosret=ifaro(fncompletadoc($row['NUM_CGCECPF']));
             
            $insert="INSERT INTO log_cpf (DATA_HORA,CPF,NOME,SEXO,DT_NASCIMENTO,COD_EMPRESA)
                    value
                    (
                      '".date('Y/m/d H:i:s')."',
                      '".$dadosret['0']['cpf']."',
                      '".$dadosret['0']['nome']."',
                      '".$dadosret['0']['sexo']."',
                      '".$dadosret['0']['datanascimento']."',
                       19   
                    );";   
            mysqli_query($conadmf, $insert);

             $update1='UPDATE log_cpf SET  DT_NASCIMENTO="'.$dadosret['0']['datanascimento'].'",
                                          NOME="'.$dadosret['0']['nome'].'",
                                          SEXO="'.$dadosret['0']['sexo'].'"  
                          WHERE  cpf="'.fnCompletaDoc($row['NUM_CGCECPF'],'F').'";'; 

                  mysqli_query($conadmf, $update1);
      
   //insert into para cobrança
          $cobranca="insert INTO log_cpfqtd 
              (IP,DATA_HORA,CPF,NOME,SEXO,DT_NASCIMENTO,COD_EMPRESA,ID_LOJA,ID_MAQUINA)
             VALUES ('".$_SERVER['REMOTE_ADDR']."',
                     '".date('Y-m-d H:i:s')."',
                     '".$dadosret['0']['cpf']."',   
                     '".$dadosret['0']['nome']."',
                     '".$dadosret['0']['sexo']."',    
                     '".$dadosret['0']['datanascimento']."', 
                    19,
                      '".$row['COD_UNIVEND']."',
                      'Atualização dataqualit' )";
          mysqli_query($conadmf, $cobranca);
          
   ////------------------       
   
    $dataqualit='select * from log_cpf where cpf="'.fnCompletaDoc($row['NUM_CGCECPF'],'F').'"'; 
    $dadosqual=mysqli_fetch_assoc(mysqli_query($conadmf, $dataqualit));
     
       
           $ano=explode("/", $dadosret['0']['datanascimento']);          

          if($dadosret['0']['sexo']=='M')
          {$sexo='1';}else{$sexo='2';} 
          $update='UPDATE clientes SET  dat_nascime="'.$dadosret['0']['datanascimento'].'",                                      
                                        DIA="'.$ano['0'].'",
                                        MES="'.$ano['1'].'",
                                        ANO="'.$ano['2'].'",
									   COD_ALTERAC="1",
									   DAT_ALTERAC="'.date('Y-m-d H:i:s').'"			
                  WHERE  num_cgcecpf="'.$row['NUM_CGCECPF'].'";';

          mysqli_query($contemporaria, $update);

     
    $contador++;
    echo "<br>".$contador."<br>";
	}
 //  echo  $update;
 ob_end_flush();
ob_flush();
flush();
}




$empresa=array('rededuue'=>'19',
                'multcoisas'=>'77',
                'COPLANA'=>'221',
                'Economizemarisol'=>'180'
                );

    foreach ($empresa as $key => $value) {
      
             $contemporaria1=connTemp($value,'');
                //atualizar unidade de cadastro com a unidade da primeira compra
                $busacacli="SELECT 
                                                        COD_CLIENTE,
                                                        unidedadenocadastro,
                                                        COD_VENDA,
                                                        unidadnaevenda,
                                                        DAT_CADASTR

                                                        FROM (

                                                        SELECT cl.COD_CLIENTE,
                                                        cl.COD_UNIVEND unidedadenocadastro,
                                                        vd.COD_VENDA,
                                                        vd.COD_UNIVEND unidadnaevenda,
                                                        vd.DAT_CADASTR FROM clientes cl
                                                        INNER JOIN vendas vd ON vd.COD_CLIENTE=cl.COD_CLIENTE
                                                         where cl.cod_univend='0' OR cl.cod_univend IS null 
                                                               and cl.cod_empresa=$value
                                                               ORDER BY vd.cod_cliente,vd.DAT_CADASTR
                                                        )vendaunidade

                            GROUP BY COD_CLIENTE";
                $rsunidade=mysqli_query($contemporaria1, $busacacli);
                while ($rowunidade = mysqli_fetch_assoc($rsunidade)) 
                {   
                   $alterarunidade.="UPDATE clientes SET COD_UNIVEND=".$rowunidade[unidadnaevenda]." WHERE COD_EMPRESA=$value and cod_cliente=$rowunidade[COD_CLIENTE];";
                }
                mysqli_multi_query($contemporaria1, $alterarunidade);
            
    }