<?php
function fncalculaValor($arrayiten,$dec){
 
    if (count($arrayiten->venda->items->vendaitem->quantidade)==1){
            $vltotal=fnFormatvalor($arrayiten->venda->valortotal,$dec);
            $valor=fnFormatvalor($arrayiten->venda->items->vendaitem->valor,$dec);
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
        if(trim($vltotal) == trim($sum))
         {
             $retorno = 1; 
                return $retorno;
            
         } else {
             return 0;
             
         }
    }
}
