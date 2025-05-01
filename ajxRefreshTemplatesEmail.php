<?php include "_system/_functionsMain.php"; 
$buscaAjx1 = fnLimpacampo($_REQUEST['ajx1']);
$cod_campanha = fnLimpacampo(fnDecode($_REQUEST['COD_CAMPANHA']));


$sql = "SELECT * FROM TEMPLATE_EMAIL WHERE cod_empresa = $buscaAjx1 ORDER BY NOM_TEMPLATE";
		
$arrayQuery = mysqli_query(connTemp($buscaAjx1,''),$sql);

$count=0;
while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
	$count++;	

	if($qrBuscaModulos['COD_EXT_TEMPLATE'] != ""){
		$sincronia = "<span class='fas fa-check text-success' style='padding: 5px 5px;'></span>";
	}else{
		$sincronia = "<span class='fas fa-times text-danger' style='padding: 5px 5px;'></span>";
	}

	?>

		<tr>
           <td><?php echo $qrBuscaModulos['NOM_TEMPLATE']; ?></td>
           <td><small><?php echo fnDataFull($qrBuscaModulos['DAT_CADASTR']); ?></td>
           <td class='text-center'>
                 <?=$sincronia?>
           </td>
           <td class="text-center">
              <small>
                <div class="btn-group dropdown dropleft">
                  <button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ações &nbsp;
                    <span class="fas fa-caret-down"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                    <li class="text-info"><a data-url="action.php?mod=<?php echo fnEncode(1409)?>&id=<?php echo fnEncode($buscaAjx1)?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Template do Email" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"sm")} catch(err) {}'><i class='fas fa-pencil'></i> Editar </a></li>
                    <li class="text-success"><a data-url='action.php?mod=<?php echo fnEncode(1478)?>&id=<?php echo fnEncode($buscaAjx1)?>&idT=<?php echo fnEncode($qrBuscaModulos['COD_TEMPLATE'])?>&idc=<?=fnEncode($cod_campanha)?>&pop=true' data-title="Modelo do Email" onclick='try {parent.abreModalPai($(this).attr("data-url"),$(this).attr("data-title"),"lg")} catch(err) {}'><i class='fas fa-external-link-square'></i> Acessar </a></li>
                    <!-- <li class="divider"></li> -->
                    <!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
                  </ul>
                </div>
              </small>
           </td>
        </tr>
<?php 
}
											
?>			