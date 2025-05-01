<?php
function fnformatadatevenda($data){
    
   if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4} \d{2}\:\d{2}\:\d{2}$/', $data)) {
                $data = str_replace("/", "-",$data);
                $strcount= date('Y-m-d H:i:s', strtotime($data));    
                return $strcount;
   }elseif (preg_match('/^\d{1,2}\-\d{1,2}\-\d{4} \d{2}\:\d{2}\:\d{2}$/', $data)) {
           $strcount= date('Y-m-d H:i:s', strtotime($data)); 
           return $strcount;  
    }elseif (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2} \d{2}\:\d{2}\:\d{2}$/', $data)){
        
           return $data;
        }       
}
function fnlogmsg($conn,$USUARIO,$EMPRESA,$CPF,$loja,$idmaquina,$codvendedor,$nomevendedor,$pagina,$msgerro,$ativo)
{
  if($ativo=='S')
  {    
    $sql="insert into ws_log (ip,porta,USUARIO,EMPRESA,CPF,loja,idmaquina,codvendedor,nomevendedor,pagina,msgerro)values
                      ('".$_SERVER['REMOTE_ADDR']."',
                       '".$_SERVER['REMOTE_PORT']."',
                       '".$USUARIO."',
                       '".$EMPRESA."',
                       '".$CPF."',  
                       '".$loja."',
                       '".$idmaquina."',
                       '".$codvendedor."',
                       '".$nomevendedor."',
                       '".$pagina."',    
                       '".$msgerro."'    
                     )";  

    mysqli_query($conn, $sql);
    //return $sql;
 }
}
function fncalculaValor($arrayiten,$dec){
 
    if (count($arrayiten->venda->items->vendaitem[0]->quantidade)==1){
            $vltotal=fnFormatvalor($arrayiten->venda->valortotal,$dec);
            $valor=fnFormatvalor($arrayiten->venda->items->vendaitem[0]->valor,$dec);
            //$vl=$valor * $quantidade;
            if(trim($vltotal) == trim($valor))            
           // if(trim($vltotal) == trim(fnFormatvalor($vl,$dec)))
            {
                $retorno = 1; 
                return $retorno;
                
            }else{ return $vl;}
            
    }else{
          $vltotal=fnFormatvalor($arrayiten->venda->valortotal,$dec);
        
        foreach ($arrayiten->venda->items->vendaitem as $key => $chave)
        {
            $cod[]=fnFormatvalor($chave->valor,$dec);
           
        }  
        $sum=array_sum($cod);
        if(trim($vltotal) == trim(fnFormatvalor($sum,$dec)))
         {
             $retorno = 1; 
                return $retorno;
            
         } else {
             
             return 0;
             
         }
    }
}
function fngravalogxml($array){
     $inserarray='INSERT INTO '.$array['tables'].' (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,NUM_CGCECPF,DES_VENDA,COD_PDV,CUPOM)values
                    ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                     "'.$array['cod_usuario'].'","'.$array['login'].'","'.$array['cod_empresa'].'","'.$array['idloja'].'","'.$array['idmaquina'].'","'.$array['cpf'].'","'.$array['xml'].'","'.$array['pdv'].'","'.$array['cupom'].'")';
        $arraP=mysqli_query($array['conn'],$inserarray);
   $COD_LOG= mysqli_insert_id($array['conn']);
   return $COD_LOG;
}
function Grava_log_msgxml($conn,$table,$id_log,$MSG,$retorno){
    $msg1='INSERT INTO '.$table.' (ID,DATA_HORA,MSG,origem_retorno)values('.$id_log.',"'.date("Y-m-d H:i:s").'","'.$MSG.'","'.$retorno.'")';
    mysqli_query($conn,$msg1);  
    //return $msg1;
       
}
function fnformatadate($data){
    
   if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $data)) {
              
                return $data;
   }elseif (preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $data)) {
           $data = str_replace("-", "/",$data);
           return $data;  
    }else{
          if(count(explode("/",$data)) > 1){
              return implode("/",array_reverse(explode("/",$data)));
          }elseif(count(explode("-",$data)) > 1){
            return implode("/",array_reverse(explode("-",$data)));
              }
        }       
}
function fnidade($data_nasc) {

        $data_nasc=explode("/",$data_nasc);

        $data=date("d/m/Y");

        $data=explode("/",$data);

        $anos=$data[2]-$data_nasc[2];

        if ($data_nasc[1] > $data[1]) {

        return $anos-1;

        } if ($data_nasc[1] == $data[1]) {

        if ($data_nasc[2] <= $data[2]) {

        return $anos;



        } else {

        return $anos-1;



        }

        } if ($data_nasc[1] < $data[1]) {

        return $anos;

        }

}