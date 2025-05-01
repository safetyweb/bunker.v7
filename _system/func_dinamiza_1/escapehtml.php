<?php
$str='<html><head></head><body>  <!-- inizio parte html da salvare -->
					<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #eeeeee;" class="__constructor">
					  <tbody><tr>
						<td width="100%" id="primary" class="main demo" align="center" valign="top" style="min-height: 1058px;">
						  <!-- inizio contentuto      -->

						  <div class="column ui-sortable">


							<!-- default element text -->
							<div class="lyrow ui-draggable" style="display: block;">
                <a href="#close" class="remove label label-danger"><i class="glyphicon-trash glyphicon"></i></a>
                <span class="drag label label-default"><i class="glyphicon glyphicon-move"></i></span>
                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>
                <div style="display:none;">
                  <div class="icon title-block"></div>
                  <label>&#x02620 x &#x1F600 hello&#x02620</label>
                </div>
                <div class="view">
                  <div class="row clearfix __constructor">
                    <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color: rgb(255, 255, 255); display: table;" data-type="title" id="5671da0a-c83e-58fe-f3dc-9a1e78658f0b">
                      <tbody>
                        <tr>
                          <td align="left" class="title" style="padding:5px 50px 5px 50px">
                            <h1 style="font-family:Arial"> Coloque seu título aqui! </h1>
                            <h4>Seu Sub Título</h4>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div><div class="lyrow ui-draggable" style="display: block;">
                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                <span class="drag label label-default"><i class="glyphicon glyphicon-move"></i></span>
                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                <div style="display:none;">
                  <div class="icon image-block"></div>
                  <label>Imagem</label>
                </div>
                <div class="view">
                  <div class="row clearfix __constructor">
                    <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color: rgb(255, 255, 255); display: table;" data-type="image" id="19407d76-10e3-b831-3b81-4ce555b4f977">
                      <tbody>
                        <tr>
                          <td align="center" style="padding:15px 50px 15px 50px;" class="image">  <!-- https://img.bunker.mk/media/imgMail.jpg -->
                            <img class="" border="0" align="one_image" style="display:block;" width="100%" alt="" src="https://img.bunker.mk/media/imgMail.jpg" tabindex="0">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div><div class="lyrow" style="display: block;">
							  <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
							  <span class="drag label label-default"><i class="glyphicon glyphicon-move"></i></span>
							  <div class="view __constructor">


								<div class="row clearfix">
								  <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" data-type="text-block" style="background-color: rgb(255, 255, 255); display: table;" id="22728886-8a47-1323-2bf7-59f8344bcde0">
									<tbody>
									  <tr>
										<td class="block-text" align="left" style="padding:10px 50px 10px 50px;font-family:Arial;font-size:13px;color:#000000;line-height:22px">
											<center>
											  <i class="fa fa-arrow-up fa-3x"></i> <br><br>
											  Clique e arraste os objetos a esquerda para cima ou para baixo desta área e com um duplo clique edite-os.
											  <br><br>
											  Exclua-me quando desejar e houver outro objeto nesta área.
											  <br><br>
											  <i class="fa fa-arrow-down fa-3x"></i>
											</center>
										</td>
									  </tr>
									</tbody>
								  </table>
								</div>
							  </div>
							</div>



						  </div>


						</td>
					  </tr>
					</tbody></table></body></html>
';

$teste= json_encode($str, JSON_UNESCAPED_UNICODE);
//$teste1= json_decode($str,JSON_UNESCAPED_UNICODE);

echo '<pre>';
print_r($teste);
echo '</pre>';
$arquivo = fopen('/srv/www/htdocs/_system/func_dinamiza/teste.txt','w');
fwrite($arquivo, $teste);
fclose($arquivo);
?>