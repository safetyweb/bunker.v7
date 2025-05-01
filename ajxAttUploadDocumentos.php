<?php 
include './_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);
$cod_busca = fnLimpaCampoZero($_POST['COD_BUSCA']);
$num_contador = fnLimpaCampoZero($_POST['NUM_CONTADOR']);
$tp_anexo = fnLimpaCampo($_POST['TP_ANEXO']);

$limit = 5;
$page = (fnLimpaCampo(@$_POST['page']) == ""?1:fnLimpaCampo(@$_POST['page']));

$ini = ($page - 1)*$limit;

$completaCont = "";

// if($tp_anexo == "COD_CONVENI"){
	if($cod_busca == 0){
		$completaCont = "AND COD_PROVISORIO = $num_contador ";
	}else{
		$completaCont = "";
	}
// }

	if($tp_anexo == "COD_ANEXO"){
		$completaCont .= "AND COD_ANEXO = $cod_busca";
	}

$sql = "SELECT * FROM ANEXO_DOC WHERE COD_CLIENTE = $cod_cliente $completaCont ORDER BY DAT_CADASTR DESC LIMIT $ini,$limit";

// fnEscreve($sql);

if ($page <= 1){
?>
<input type="hidden" value=0 class="page">
<div class="row">

	<div class="col-md-7">
		<table class="table">
    		<tbody id="relatorioConteudo">
    			<?php 

}

	    			// fnEscreve($sql);
	    			// FNeSCREVE($cod_empresa);
					$count = 0;
					$arrayquery = mysqli_query(connTemp($cod_empresa,''),$sql);
					while($qrAnexo = mysqli_fetch_assoc($arrayquery)){
						$file_ext = strtolower(end(explode('.', $qrAnexo['NOM_ORIGEM'])));
						$count++;

						$sqlHist = "SELECT A.*,
									CASE WHEN A.COD_STATUS =3 THEN
									B.DES_JUSTIFICA
									ELSE 
									A.DES_JUSTIFICA
									END AS JUSTIFICA
									FROM HISTORICO_ANEXO A
									LEFT JOIN JUSTIFICATIVA B ON A.DES_JUSTIFICA=COD_JUSTIFICA AND A.COD_STATUS=3
									WHERE A.COD_ANEXO = $qrAnexo[COD_DOCUMENTO]
									ORDER BY 1 DESC
									LIMIT 1";

						// fnEscreve($sqlHist);

						$arrayHist = mysqli_query(connTemp($cod_empresa,''),$sqlHist);

						$qrHist = mysqli_fetch_assoc($arrayHist);

						$dat_status = $qrHist[DAT_STATUS];

						$mostra_status = "";
						$cor = "";
						$badge = "badge";
						$txtBadge = "txtBadge";
						$tooltip = "";
						$mostraAprovar = "block";
						$textoReprova = "Reprovar";
						$des_doc = $qrAnexo['NOM_REFEREN'];

						if($qtd_hist == 0){
							$qtd_hist = 1;
						}

						if($dat_status == ""){
							$dat_status = $qrAnexo['DAT_CADASTR'];
						}

						if($qrHist[COD_STATUS] == 2){

							$cor = "background:#18bc9c;";
							$mostra_status = "<span class='fas fa-check'></span>";
							$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrHist[JUSTIFICA]'";
							$mostraAprovar = "none";

						}else if($qrHist[COD_STATUS] == 3){

							$cor = "background:red; color:white;";
							$mostra_status = "<span class='fas fa-info'></span>";
							$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrHist[JUSTIFICA]'";
							$textoReprova = "Alterar Justificativa";

						}else{
							$cor = "background:blue; color:white;";
							$mostra_status = "<span class='fas fa-sync'></span>";
							$tooltip = "data-toggle='tooltip' data-placement='top' data-original-title='$qrAnexo[DES_STATUS]'";
							$textoReprova = "Alterar Justificativa";

						}

						$status = "<span class='".$badge."' style='".$cor."' $tooltip><span class='".$txtBadge." ".$textRed."'>".$mostra_status."</span></span>";
						$upload = "";

						if($qrHist[COD_STATUS] != 2){
							$upload = "<a href='javascript:' onClick=\"atualizaAnexo(".$qrAnexo[COD_DOCUMENTO].");\" class='btn btn-primary btn-xs upload' style='margin-top:0px;'><i class='fa fa-cloud-upload' aria-hidden='true'></i></a>";
						}

					?>

						<tr>

							<?php if($file_ext == "jpeg" || $file_ext == "jpg" || $file_ext == "png"){ ?>
								<td><a href="https://adm.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrAnexo['NOM_ORIGEM']; ?>" class="download" target="files" onclick="openNav()"><span class="fas fa-file-search"></span>
								</a></td>
							<?php }else{ ?>
								<td><a href="https://docs.google.com/a/192.99.240.249/viewer?url=http://adm.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrAnexo['NOM_ORIGEM']; ?>&pid=explorer&efh=false&a=v&chrome=false&embedded=true" class="download" target="files" onclick="openNav()"><span class="fas fa-file-search"></span></a></td>
							<?php } ?>
							<td><a class="download" href="../media/clientes/<?php echo $cod_empresa; ?>/documentos/documento.<?php echo $cod_cliente; ?>/<?php echo $qrAnexo['NOM_ORIGEM']; ?>" download><span class="fa fa-download"></span></a></td>

							<td width="70%" id="nom_doc"><?=$qrAnexo['NOM_REFEREN']?></td>
							<td width="20%">
								<div class="row">
									<div class="col-md-6">
										<?=$status?>
									</div>
									<div class="col-md-6">
										<?=$upload?>
									</div>
								</div>
									
							</td>
							<td><small><?php echo date("d/m/Y",strtotime($qrAnexo['DAT_CADASTR'])) ?></small>&nbsp;<small><?php echo date("H:i:s",strtotime($qrAnexo['DAT_CADASTR'])) ?></small></td>
						</tr>

						<script type="text/javascript">$('[data-toggle="tooltip"]').tooltip();</script>

				<?php 
					}

if ($page <= 1){
				?>
    		</tbody>
    	</table>
	</div>

</div>

<div class="row load"></div>

<div class="row button">

	<div class="col-md-3" style="margin-right: -43px;">
		<div class="collapse-chevron">
			<a class="collapsed btn btn-sm btn-default" href="javascript:" onClick="refreshUpload(false);" style="width: 90%;">
		    	<span class="fa fa-chevron-down" aria-hidden="true"></span>&nbsp;
		    	Carregar + Anexos
			</a>
		</div>
	</div>

</div>


<style type="text/css">
	
/* The Overlay (background) */
.overlay {
  /* Height & width depends on how you want to reveal the overlay (see JS below) */    
  height: 100%;
  width: 100%;
  position: fixed; /* Stay in place */
  left: 0;
  top: 0;
  background-color: rgba(0,0,0, 0.9); /* Black w/opacity */
  overflow-x: hidden; /* Disable horizontal scroll */
  transition: 0.5s; /* 0.5 second transition effect to slide in or slide down the overlay (height or width, depending on reveal) */
  display: none;
  z-index: 9999;
}

/* Position the content inside the overlay */
.overlay-content {
  position: relative;
  top: 0; /* 5% from the top */
  width: 80%; /* 100% width */
  text-align: center; /* Centered text/links */
  margin-left: auto;
  margin-right: auto;
}

/* Position the close button (top right corner) */
.overlay .closebtn {
  position: absolute;
  top: 60px;
  right: 45px;
  font-size: 60px;
}

.modal-dialog2{
    width: 100vw;
    height: 100vh;
   
}

.modal-content2{
	width: 100vw;
	height: 100vh;
	border-radius: 0;
}

.badge{
    display: table;
    border-radius: 30px 30px 30px 30px;
    width: 20px;
    height: 20px;
    text-align: center;
    color:white;
    font-size:8px;
    margin-right: auto;
    margin-left: auto;
    /*cursor: help;*/
}

.txtBadge{
	display: table-cell;
	vertical-align: middle;
}

</style>

<!-- The overlay -->
<div id="myNav" class="overlay">

  <!-- Button to close the overlay navigation -->
  <div class="push50"></div>
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

  <!-- Overlay content -->
  <div class="overlay-content">
   	<iframe name="files" id="files" src='' width='100%' height='100%' frameborder='0'></iframe>
  </div>

</div>


<script type="text/javascript">
/* Open */
function openNav() {
  $('#myNav').show();
	try {
		parent.$('.modal-dialog').attr("class", 'modal-dialog2');
		parent.$('.modal-content').attr("class", 'modal-content2');
	} catch(err) {}
}

/* Close */
function closeNav() {
	$('#myNav').hide();
	try { 
	  	parent.$('.modal-dialog2').attr("class", 'modal-dialog');
		parent.$('.modal-content2').attr("class", 'modal-content');
	} catch(err) {}
  $('#files').attr('src', '');
}

function atualizaAnexo(id_doc){
	$('#fileinput').trigger('click');
	$("#fileinput").fileinput("destroy").fileinput({
			allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'doc', 'docx', 'pdf', 'zip'],
			uploadUrl: "ajxSubDocConvenio.php?idd="+id_doc+"&tipo=<?=$tp_anexo?>&ido="+$("#COD_OBJETOANEXO").val()+"&tpc=<?=fnEncode($tp_cont)?>&cod_empresa=<?=$cod_empresa?>&cod_cliente=<?=$cod_cliente?>&num_contador=<?=$num_contador?>&orig=doc",
			uploadAsync: true,
			//deleteUrl: "/site/file-delete",
			showUpload: false, // hide upload button
			showRemove: false, // hide remove button
			overwriteInitial: false, // append files to initial preview
			minFileCount: 1,
			maxFileCount: 5,
			initialPreviewAsData: true,
			previewFileIcon: '',
			allowedPreviewTypes: null, // disable preview of standard types
			allowedPreviewMimeTypes: ['image/jpeg', 'text/javascript'], // allow content to be shown only for certain mime types 
			previewFileIconSettings: {}
	}).on("filebatchselected", function(event, files) {
			$("#fileinput").fileinput("upload");
	}).on('filebatchuploadcomplete', function(event, file, previewId, index, reader) {
			// refreshUpload();
	});
}

</script>

<?php
}
if ($count < $limit){
?>
	<script>
		$("#relatorioConteudo2 .button").hide();
	</script>
<?php
}
?>