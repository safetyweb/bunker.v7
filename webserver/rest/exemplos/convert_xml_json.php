<?php
$xmltojson="<InsereVenda>
					<fase>fase6</fase>
					<venda>
						<id_vendapdv>1-15811-93850</id_vendapdv>
						<datahora>2021-10-12 09:00:32</datahora>
						<cartao>00000000000</cartao>
						<valortotalbruto>37,4900</valortotalbruto>
						<descontototalvalor>0,00</descontototalvalor>
						<valortotalliquido>37,4900</valortotalliquido>
						<valor_resgate>0,00</valor_resgate>
						<cupomfiscal/>
						<formapagamento>CONVENIO</formapagamento>
						<indicador/>
						<codatendente>7</codatendente>
						<codvendedor>7</codvendedor>
						<idcliente/>
						<itens>
							<vendaitem>
								<id_item>2</id_item>
								<produto>MEDICAMENTO</produto>
								<codigoproduto>1763</codigoproduto>
								<quantidade>1</quantidade>
								<valorbruto>29,1400</valorbruto>
								<descontovalor>1,6400</descontovalor>
								<valorliquido>27,5000</valorliquido>
								<ean>1763</ean>
								<estoque>-467</estoque>
								<atributo1>HPC</atributo1>
								<atributo2>BONIFICADOS</atributo2>
								<atributo3>UN</atributo3>
								<atributo4>MEDICAMENTO</atributo4>
								<atributo5>DIVERSOS</atributo5>
								<envioGenerico/>
							</vendaitem>
							<vendaitem>
								<id_item>3</id_item>
								<produto>COCA COLA 2 LITROS</produto>
								<codigoproduto>75067569</codigoproduto>
								<quantidade>1</quantidade>
								<valorbruto>9,9900</valorbruto>
								<descontovalor>0,00</descontovalor>
								<valorliquido>9,9900</valorliquido>
								<ean>7894900027013</ean>
								<estoque>-26</estoque>
								<atributo1>HPC</atributo1>
								<atributo3>UN</atributo3>
								<atributo4>COCA COLA 2 LITROS</atributo4>
								<atributo5>PLUGPHARMA</atributo5>
								<envioGenerico/>
							</vendaitem>
						</itens>
					</venda>
					<dadosLogin>
						<login>trier.rtotal</login>
						<senha>trier@mk</senha>
						<idloja>97375</idloja>
						<idmaquina>22</idmaquina>
						<idcliente>251</idcliente>
						<codvendedor>16</codvendedor>
						<nomevendedor>EDUARDO JOAQUIM</nomevendedor>
					</dadosLogin>
				</InsereVenda>";
		
$doc = new DOMDocument();
$doc->loadHTML($xmltojson);
$xml = $doc->saveXML($doc->documentElement);
$xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
$json = json_encode($xml, JSON_PRETTY_PRINT);
echo $json;
?>