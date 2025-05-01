<?php
   
            $cod_campanha.='1,2,3,';
            
      
        $cod_campanha1 = implode(",", array_unique(explode(",", rtrim($cod_campanha,','))));
		echo '<pre>';
		print_r($cod_campanha1);
		echo '</pre>';
		echo $cod_campanha1;
?>
