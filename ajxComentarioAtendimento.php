<?php include "_system/_functionsMain.php"; 

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$cod_empresa = fnLimpacampo(fnDecode($_GET['ajx3']));

$cod_atendimento = fnDecode($buscaAjx1);
$mod = fnDecode($buscaAjx2);

//setando locale da data
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

if($mod == 1440) $ANDtipo = " "; else $ANDtipo = " AND TP_COMENTARIO = 1 ";

					$sql = "SELECT SC.*, SS.ABV_STATUS FROM ATENDIMENTO_COMENTARIO SC
							LEFT JOIN ATENDIMENTO_STATUS SS ON SS.COD_STATUS = SC.COD_STATUS
							WHERE SC.COD_ATENDIMENTO = $cod_atendimento
							$ANDtipo
							ORDER BY SC.DAT_CADASTRO DESC
								";

						// fnEscreve($sql);

						$arrayQueryComment = mysqli_query(connTemp($cod_empresa,''),$sql);

						while($qrComment = mysqli_fetch_assoc($arrayQueryComment)){
							$interno = "";
								//fnEscreve('entrou while');
							$mes = strtoupper(strftime('%B', strtotime($qrComment["DAT_CADASTRO"])));
							$mes = substr("$mes", 0, 3);

							$sqlUsuarios = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrComment[COD_USUARIO]";
							//fnEscreve($sqlUsuarios);
							$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsuarios));

							if($qrComment['TP_COMENTARIO'] == 2){
								$interno = " <span class='f12'> (INTERNO) </span>";
							}

							?>
								<div class="cd-timeline__container">
									<div class="cd-timeline__block<?php echo $qrComment['COD_COR']; ?>">
										<div class="cd-timeline__img"></div>
										<div class="cd-timeline__content">
											<h2><?=$qrNomUsu['NOM_USUARIO'].$interno?></h2>
											<div class="push5"></div>
											<textarea class="editor2 form-control input-sm"><?php echo $qrComment['DES_COMENTARIO']; ?></textarea>
											<span class="cd-timeline__date"><?php echo strftime('%d ', strtotime($qrComment["DAT_CADASTRO"]))."".$mes; ?>
												<br>
												<span class="hora"><?php echo date("H:i", strtotime($qrComment["DAT_CADASTRO"])); ?></span>
												<br>
												<span><small><b><?=$qrComment['ABV_STATUS']?></b></small></span>
											</span>
										</div>
									</div>
								</div>

							<?php } 
							?>
							<script>
								// TextArea
								$(".editor2").jqte(
								{
									sup: false,
									sub: false,
									outdent: false,
									indent: false,
									left: false,
					        		center: false,
					        		color: false,
					        		right: false,
					        		strike: false,
					        		source: false,
							        link:false,
							        unlink: false,		        
							        remove: false,
							    	rule: false,
							    	fsize: false,
							    	format: false,
							    	b: false,
							    	i: false,
							    	u: false,
							    	ol: false,
							    	ul: false,
							    	toolbar: false
							    });
							</script>
