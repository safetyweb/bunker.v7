<?php 

include '_system/_functionsMain.php'; 	


$cod_empresa = @$_POST["cod_empresa"];
$cod_univend = @$_POST["cod_univend"];
$count = $cod_univend."000";

//oper. e vendedores
$tipoUsuario = "8,7,11,2";
/*
$sql = "
SELECT 
	count(*) as CONTADOR
FROM
	usuarios
WHERE
		usuarios.COD_EMPRESA = $cod_empresa 
		AND usuarios.COD_TPUSUARIO IN ($tipoUsuario)
		AND usuarios.DAT_EXCLUSA IS NULL
ORDER BY usuarios.NOM_USUARIO";	

//fnEscreve($sql);
$retorno = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
*/

$sql = "select * from usuarios 
left join tipousuario on usuarios.COD_TPUSUARIO = tipousuario.COD_TPUSUARIO
where usuarios.COD_EMPRESA = $cod_empresa 
AND usuarios.COD_TPUSUARIO IN ($tipoUsuario)
AND usuarios.COD_UNIVEND IN ($cod_univend)
AND usuarios.DAT_EXCLUSA is null order by usuarios.NOM_USUARIO ";


//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());



$sql = "select * from turnostrabalho 
where turnostrabalho.COD_EMPRESA = $cod_empresa";
$arrayTurno = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
$turnos = array();
$turnos[0] = "Turno não definido";
while ($qrTurno = mysqli_fetch_assoc($arrayTurno)){
	$turnos[$qrTurno["COD_TURNO"]] = $qrTurno["NOM_TURNO"];
}

while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery))
{														  
$count++;
$sqlMeta = "SELECT * FROM CONTROLE_METAS WHERE COD_ATENDENTE = 0".$qrListaUsuario['COD_USUARIO']." AND COD_EMPRESA = $cod_empresa";
//fnEscreve($sqlMeta);
$arrayQueryMeta = mysqli_query(connTemp($cod_empresa,""),$sqlMeta) or die(mysqli_error());
$qrUser = mysqli_fetch_assoc($arrayQueryMeta);
$log_ativo = $qrUser['LOG_ATIVO'];
if($qrUser['VAL_METAPROD'] == '' || !isset($qrUser['VAL_METAPROD'])) $val_metaprod = 0; else $val_metaprod = $qrUser['VAL_METAPROD'];
if($qrUser['VAL_METADEST'] == '' || !isset($qrUser['VAL_METADEST'])) $val_metadest = 0; else $val_metadest = $qrUser['VAL_METADEST'];
if($qrUser['VAL_ALERTMIN'] == '' || !isset($qrUser['VAL_ALERTMIN'])) $val_alertmin = 0; else $val_alertmin = $qrUser['VAL_ALERTMIN'];
if($qrUser['QTD_FIDELIZ'] == '' || !isset($qrUser['QTD_FIDELIZ'])) $qtd_fideliz = 0; else $qtd_fideliz = $qrUser['QTD_FIDELIZ'];

//fnEscreve($val_metadest);

if($log_ativo == 'S') $check = "checked"; else $check = "";
?>	<tr data-univend='<?=$cod_univend?>' cod-usuario='<?php echo $qrListaUsuario['COD_USUARIO']; ?>'>
	  <td>
		<label class='switch switch-small'>
		<input type='checkbox' name='LOG_ATIVO_<?php echo $count; ?>' id='LOG_ATIVO_<?php echo $count; ?>' class='switch' value='S' onchange="verificaTabela('<?php echo $count ?>')" <?php echo $check; ?>>
		<span></span>
		</label>
	  </td>	
	  <td>
		<?php
		$qrListaUsuario['COD_TURNO'] = ($qrListaUsuario['COD_TURNO'] == ""?0:$qrListaUsuario['COD_TURNO']);
		?>
		<small><?php echo $qrListaUsuario['COD_EXTERNO']; ?> </small>
		&nbsp; <strong><?php echo $qrListaUsuario['NOM_USUARIO']; ?></strong>
		&nbsp; <i class="text-danger far fa-clock" aria-hidden="true"></i>
		<a href="javascript:" class="editable" 
			data-type='select' 
			data-title='Editar'
			data-pk="<?php echo $qrListaUsuario['COD_USUARIO']; ?>" 
			data-name="COD_TURNO" 
			data-univend="<?php echo $qrListaUsuario['COD_UNIVEND']; ?>" 
			data-count="<?php echo $count; ?>" 
			data-source='<?=json_encode($turnos)?>'
			data-value="<?=$qrListaUsuario['COD_TURNO']?>"
			data-empresa="<?php echo $qrListaUsuario['COD_EMPRESA']; ?>">
				<?=@$turnos[$qrListaUsuario['COD_TURNO']]?>
		</a>
	</td>
	  </td>
	  <td class='text-center edit-int'>
		<a href="#" class="editable" 
			data-type='text' 
			data-title='Editar'
			data-pk="<?php echo $qrListaUsuario['COD_USUARIO']; ?>" 
			data-name="VAL_METAPROD" 
			data-univend="<?php echo $qrListaUsuario['COD_UNIVEND']; ?>" 
			data-count="<?php echo $count; ?>" 
			data-empresa="<?php echo $qrListaUsuario['COD_EMPRESA']; ?>"><?php echo fnValor($val_metaprod,0); ?>
			
		</a>
	  </td>
	  <!-- <td class='text-center edit-decimal'> -->
	  <td class='text-center edit-int'>
		<a href="#" class="editable" 
			data-type='text' 
			data-title='Editar'
			data-pk="<?php echo $qrListaUsuario['COD_USUARIO']; ?>" 
			data-name="VAL_METADEST" 
			data-univend="<?php echo $qrListaUsuario['COD_UNIVEND']; ?>" 
			data-count="<?php echo $count; ?>" 
			data-empresa="<?php echo $qrListaUsuario['COD_EMPRESA']; ?>"><?php echo fnValor($val_metadest,0); ?>
			
		</a>
	  </td>
	  <!-- <td class='text-center edit-decimal'> -->
	  <td class='text-center edit-int'>
		<a href="#" class="editable" 
			data-type='text' 
			data-title='Editar'
			data-pk="<?php echo $qrListaUsuario['COD_USUARIO']; ?>" 
			data-name="VAL_ALERTMIN" 
			data-univend="<?php echo $qrListaUsuario['COD_UNIVEND']; ?>" 
			data-count="<?php echo $count; ?>" 
			data-empresa="<?php echo $qrListaUsuario['COD_EMPRESA']; ?>"><?php echo fnValor($val_alertmin,0); ?>
			
		</a>
	  </td>
	  <!-- <td class='text-center edit-decimal'> -->
	  <td class='text-center edit-int'>
		<a href="#" class="editable" 
			data-type='text' 
			data-title='Editar'
			data-pk="<?php echo $qrListaUsuario['COD_USUARIO']; ?>" 
			data-name="QTD_FIDELIZ" 
			data-univend="<?php echo $qrListaUsuario['COD_UNIVEND']; ?>" 
			data-count="<?php echo $count; ?>" 
			data-empresa="<?php echo $qrListaUsuario['COD_EMPRESA']; ?>"><?php echo fnValor($qtd_fideliz,0); ?>
			
		</a>
	  </td>
	</tr>
	<input type='hidden' id='ret_COD_USUARIO_<?php echo $count; ?>' name='COD_USUARIO_<?php echo $count; ?>' value='<?php echo $qrListaUsuario['COD_USUARIO']; ?>'>
	<input type='hidden' id='ret_COD_EMPRESA_<?php echo $count; ?>' name='COD_EMPRESA_<?php echo $count; ?>' value='<?php echo $qrListaUsuario['COD_EMPRESA']; ?>'>
	<input type='hidden' id='ret_COD_UNIVEND_<?php echo $count; ?>' name='COD_UNIVEND_<?php echo $count; ?>' value='<?php echo $qrListaUsuario['COD_UNIVEND']; ?>'>
<?php
}