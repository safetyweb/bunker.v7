<?php
//138,142,97
include '../_system/_functionsMain.php';
include '../webserver/func/func_ifaro.php';
//fnDebug('true');
$contemporaria=connTemp(85,'');
$conadmf=$connAdm->connAdm ();
// DAT_NASCIME='31/12/1969' AND 
$cliente="SELECT * FROM clientes WHERE cod_empresa=85 AND date(dat_cadastr) ='2022-03-29' and cod_univend='96252'";
$rs=mysqli_query($contemporaria, $cliente);
$contador=0;
while ($row = mysqli_fetch_assoc($rs)) 
{   
       ob_start();
   
            $dadosret=ifaro(fncompletadoc($row['NUM_CGCECPF']));
            echo '<pre>';
            print_r($dadosret);
            echo '</pre>';
            
            $insert="INSERT INTO log_cpf (DATA_HORA,CPF,NOME,SEXO,DT_NASCIMENTO,COD_EMPRESA)
                    value
                    (
                      '".date('Y/m/d H:i:s')."',
                      '".$dadosret['0']['cpf']."',
                      '".$dadosret['0']['nome']."',
                      '".$dadosret['0']['sexo']."',
                      '".$dadosret['0']['datanascimento']."',
                       103   
                    );";   
            mysqli_query($conadmf, $insert);

             $update1='UPDATE log_cpf SET  DT_NASCIMENTO="'.$dadosret['0']['datanascimento'].'",
                                          NOME="'.$dadosret['0']['nome'].'",
                                          SEXO="'.$dadosret['0']['sexo'].'"  
                          WHERE  cpf="'.fnCompletaDoc($row['NUM_CGCECPF'],'F').'";'; 

                  mysqli_query($conadmf, $update1);
          echo $update1; 
   //insert into para cobrança
          $cobranca="insert INTO log_cpfqtd 
              (IP,DATA_HORA,CPF,NOME,SEXO,DT_NASCIMENTO,COD_EMPRESA,ID_LOJA,ID_MAQUINA)
             VALUES ('".$_SERVER['REMOTE_ADDR']."',
                     '".date('Y-m-d H:i:s')."',
                     '".$dadosret['0']['cpf']."',   
                     '".$dadosret['0']['nome']."',
                     '".$dadosret['0']['sexo']."',    
                     '".$dadosret['0']['datanascimento']."', 
                    103,
                      '".$row['COD_UNIVEND']."',
                      'Atualização dataqualit' )";
          mysqli_query($conadmf, $cobranca);
          
   ////------------------       
   
    $dataqualit='select * from log_cpf where cpf="'.fnCompletaDoc($row['NUM_CGCECPF'],'F').'"'; 
    $dadosqual=mysqli_fetch_assoc(mysqli_query($conadmf, $dataqualit));
     
       
           $ano=explode("/", $dadosret['0']['datanascimento']);          

          if($dadosret['0']['sexo']=='M')
          {$sexo='1';}else{$sexo='2';} 
          $update='UPDATE clientes SET  NOM_CLIENTE="'.$dadosret['0']['nome'].'",
                                        dat_nascime="'.$dadosret['0']['datanascimento'].'",                                      
                                        COD_SEXOPES="'.$sexo.'",
                                        DIA="'.$ano['0'].'",
                                        MES="'.$ano['1'].'",
                                        ANO="'.$ano['2'].'"    
                  WHERE  num_cgcecpf="'.$row['NUM_CGCECPF'].'";'; 

          mysqli_query($contemporaria, $update);

     
    $contador++;
    echo "<br>".$contador."<br>";
 //  echo  $update;
 ob_end_flush();
ob_flush();
flush();
}