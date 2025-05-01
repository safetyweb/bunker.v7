<?php

	switch ($tip_notifica) {

		case 'SAC':

			$sqlInsert = "";

			$cod_usuarios = $cod_consultores.",".$cod_usuarios_env.",".$cod_usuario.",".$cod_usures;
			$cod_usuarios = array_unique(explode(',', $cod_usuarios));

			foreach ($cod_usuarios as $cod_usuario) {
				if($cod_usuario != 0 && $cod_usuario != ""){
					$sqlInsert .= "(
									$cod_empresa,
									$cod_usuario,
									'SAC',
									'$cod_chamado',
									'$nom_chamado',
									'Atualização de status'
							   	   ), ";
				}
			}

			$sqlInsert = rtrim(trim($sqlInsert),',');

			if($sqlInsert != ""){
				$sql = "INSERT INTO NOTIFICACOES(
										COD_EMPRESA,
										COD_USUARIO,
										TIP_ORIGEM,
										COD_IDENTIFICACAO, 
										DES_NOTIFICA, 
										DES_MOTIVO
									) VALUES $sqlInsert";
				// fnEscreve($sql);
				mysqli_query($connAdm->connAdm(),$sql);
			}

		break;
		
		default:
			# code...
		break;

	}

?>