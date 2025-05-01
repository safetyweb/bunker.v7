<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(1);

include_once 'BunkerApi/config.php';
include_once 'BunkerApi/BunkerMK.php';

class Integracao {
     private $bunker;
     private $conexao;
     private $ultimo_id_venda;
     
     public function __construct() {
        $this->bunker = new BunkerMK();
        $this->ultimo_id_venda =  $this->bunker->getUltimoIDVenda();
        //$this->bunker->debug($this->ultimo_id_venda);
        $this->conexao = new PDO("mysql:host=".DB_CLIETE_HOST.";dbname=san", DB_CLIETE_USUARIO, DB_CLIETE_SENHA);
                
        foreach ($this->pegarDadosVenda() as $row) {
            $DadosVenda = (object) $row;
            $this->atualizaCadastro($DadosVenda);     
            $DadosVendaItens = $this->pegarDadosVendaItens($DadosVenda->id_vendapdv);
            $retorno = $this->inserirVenda($DadosVenda, $DadosVendaItens);
      
            if($retorno->InsereVendaResponse->msgerro){
                echo '<br>id_vendapdv: '.$DadosVenda->id_vendapdv.'   Erro: '.$retorno->InsereVendaResponse->msgerro;
            }else{
                echo '<br>id_vendapdv: '.$DadosVenda->id_vendapdv;
            }
        }
    }
    
    public function inserirVenda ($DadosVenda, $DadosVendaItens){
        //$this->bunker->debug($DadosVenda);
        $venda['id_vendapdv'] = $DadosVenda->id_vendapdv;
        $venda['datahora'] = $DadosVenda->datahora;
        $venda['cartao'] = $DadosVenda->cartao;
        $venda['valortotal'] = $DadosVenda->valortotal;
        $venda['cupom'] = '';
        $venda['formapagamento'] = 'dinheiro';
        $venda['cartaoamigo'] = '';
        $venda['pontosextras'] = '';
        $venda['naopontuar'] = '';
        $venda['codvendedor'] = '312312312';
        $venda['pontostotal'] = '';

        // Inicio do loopping de produtos
        $count_item = 1;
        foreach($DadosVendaItens as $item){
            $DadosItem = (object) $item;
            $item['id_item'] = $count_item;
            $item['produto'] = utf8_encode($DadosItem->produto);
            $item['codigoproduto'] = (!isset($DadosItem->codigoproduto))? $DadosItem->codigoproduto: '0';
            $item['quantidade'] = $DadosItem->quantidade;
            $item['valor'] =  $DadosItem->valor;
            $item['naopontuar'] = '';

            $items['vendaitem'][] = $item;
            $count_item ++;
        }
        // Fim do looping
        
        $venda['items']= $items;
        //$this->bunker->debug($venda);
        $dados['venda'] = $venda;
        $retorno = $this->bunker->enviar($dados, 'InserirVenda');
        $this->bunker->updateUltimoIDVenda($DadosVenda->id_vendapdv);
        
       //$this->bunker->getXmlEnvio();
      //  $this->bunker->getXmlRetorno();
        
        return $retorno;
    }
        
    public function atualizaCadastro ($Dados){
       $cliente['cartao'] = $Dados->cartao;
       $cliente['tipocliente'] = 'PF';
       $cliente['nome'] = utf8_encode($Dados->nome);
       $cliente['cpf'] = $Dados->cpf;
       $cliente['cnpj'] = $Dados->cnpj;
       $cliente['rg']= '';
       $cliente['sexo']= $Dados->sexo;
       $cliente['datanascimento']=$Dados->datanascimento;
       $cliente['estadocivil']='';
       $cliente['email']=$Dados->I;
       $cliente['dataalteracao']='';
       $cliente['cartaotitular']='';
       $cliente['dataalteracao']='';
       $cliente['nomeportador']='';
       $cliente['grupo']='';
       $cliente['profissao']='';	
       $cliente['clientedesde']=$Dados->clientedesde;
       $cliente['endereco']=utf8_encode($Dados->endereco);
       $cliente['numero']=$Dados->numero;	
       $cliente['complemento']=utf8_encode($Dados->complemento);
       $cliente['bairro']=utf8_encode($Dados->bairro);
       $cliente['cidade']=utf8_encode($Dados->cidade);	
       $cliente['estado']=utf8_encode($Dados->estado);
       $cliente['cep']=$Dados->cep;
       $cliente['telresidencial']='';	
       $cliente['telcelular']='';
       $cliente['telcomercial']='';
       $cliente['saldo']='';
       $cliente['saldoresgate']='';
       $cliente['msgerro']='';
       $cliente['msgcampanha']='';
       $cliente['url']='';		
       $cliente['ativacampanha']='';
       $cliente['dadosextras']='';
       $cliente['bloqueado']='';
       $cliente['motivo']='';	
       $cliente['adesao']='';
       $cliente['codatendente']='';
       $cliente['senha']='';
       $cliente['urlextrato']='';
       $cliente['retornodnamais']='';

       $dados['cliente'] = $cliente;

       $retorno = $this->bunker->enviar($dados, 'AtualizaCadastro');
       //$this->bunker->debug($retorno);
       if($retorno->AtualizaCadastroResult->msgerro <> 'OK'){
           echo '<br>cartao: '.$dados['cliente']['cartao'].'   Erro: '.$retorno->AtualizaCadastroResult->msgerro;
       }

        //$bunker->getXmlEnvio();
        //$bunker->getXmlRetorno();
    }   
    
    public function pegarDadosVenda (){
        $sql = "select 
		v.ID_ServicosVendidos   as id_vendapdv
                   , DATE_FORMAT(vs.DT_Servico,'%Y-%m-%d %h:%i:%s') as datahora
                    , c.NU_CNPJ_CPF as cartao
                    , SUM(VL_Total) as valortotal
                    , c.TP_Situacao as tipocliente
                   , c.NM_Cliente as nome
                   , c.NU_CNPJ_CPF as cpf
                   , c.NU_CNPJ_CPF as cnpj
                   , 'M' as sexo
                   , '2000-01-01' as datanascimento
                   , '' as email
                   , c.NM_Endereco as endereco
                   , c.NU_Endereco as numero
                   , '' as complemento
                   , b.NM_Bairro as bairro
                   , m.NM_Municipio as cidade
                   , e.NM_Estado as estado
                   , c.NU_CEP as cep
                   , '' as telresidencial
                   , '' as telcelular
                   , '' as telcomercial
                   , c.DT_Cadastro as clientedesde
                 from servicosvendidos as v
                 inner join servicosvendas as vs on vs.ID_ServicosVendas = v.ID_ServicosVendidos
                 inner JOIN clientes as c on c.ID_Cliente = vs.ID_Cliente
                 inner join bairros as b on b.ID_Bairro = c.ID_Bairro
                 inner join municipios as m on m.ID_Municipio = b.ID_Municipio
                 inner join estados as e on e.ID_Estado = m.ID_Estado
                 where v.ID_ServicosVendidos > {$this->ultimo_id_venda}
                 GROUP BY ID_ServicosVendidos";
        $resultado = $this->conexao->query($sql);
        return $resultado;
    }
    
    public function pegarDadosVendaItens ($id_vendapdv){
        $sql = "select 
		v.ID_Servico  as codigoproduto
                , v.NU_Quantidade as quantidade
                , v.NM_Servico as produto
                , v.VL_Servico as valor
                from servicosvendidos as v
                inner join servicosvendas as vs on vs.ID_ServicosVendas = v.ID_ServicosVendidos
                where v.ID_VendaServico = {$id_vendapdv}";
        $resultado = $this->conexao->query($sql);
        return $resultado;
    }
} 
    
new Integracao();
echo '<br><br>--------------------->Finalizado<-----------------------';
exit();


