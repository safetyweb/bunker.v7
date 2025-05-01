<?php

	$sqlCamp = "SELECT 

				 case when 
				               sms.TIP_GATILHO='tokenCad' 
							  AND LOG_PROCESSA_SMS='S' 
							  AND LOG_PROCESSA_WHATSAPP='N' then 'celular' 
						when 	  
					         	wts.TIP_GATILHO='tokenCad' 
							  AND LOG_PROCESSA_WHATSAPP='S' then 'whatsapp' 
							  ELSE 'whatsapp'  END CAMPO
					
				 
				 
				 from campanha c 
				 left JOIN  gatilho_sms sms ON sms.COD_CAMPANHA= c.COD_CAMPANHA and sms.TIP_GATILHO='tokenCad' AND LOG_PROCESSA_SMS='S'
				 left JOIN  GATILHO_WHATSAPP wts ON wts.COD_CAMPANHA= c.COD_CAMPANHA and wts.TIP_GATILHO='tokenCad' AND LOG_PROCESSA_WHATSAPP='S'
				 WHERE c.COD_EMPRESA='$cod_empresa' AND (sms.TIP_GATILHO is NOT NULL or  wts.TIP_GATILHO is NOT NULL)
				GROUP BY CAMPO";

	// echo($sqlCamp);

	$arrCamp = mysqli_query(connTemp($cod_empresa,''),$sqlCamp);
	$qrCamp = mysqli_fetch_assoc($arrCamp);
	$campo = $qrCamp[CAMPO];

?>