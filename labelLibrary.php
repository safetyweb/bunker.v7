<?php
//inicialização
// $aba1024 = "";
// $aba1053 = "";
// $aba1054 = "";
// $aba1072 = "";
// $aba1067 = "";
// $aba1081 = "";
// $aba1112 = "";
// $aba1253 = "";
// $aba1665 = "";
// $aba1757 = "";

switch ($cod_empresa) {
    case 136: //damaris
        $labelNome = "Nome do Apoiador";
        $abaNome = "Apoiador";
        $dadoConsulta = "NOM_APOIADOR";
        $NomTela = "Busca Apoiador";
        $envolvidos = "Apoiadores";
        $pref = 'S';
        break;    
    case 224: //hiper glass
        $labelNome = "Nome do Colaborador";
        $abaNome = "Colaborador";
        $NomTela = "Busca Colaborador";
        $envolvidos = "Colaboradores";
        $pref = 'S';
        break;   
    case 311: //prefeitura tatui
        $labelNome = "Nome do Munícipe";
        $abaNome = "Munícipe";
        $dadoConsulta = "NOM_MUNICIPE";
        $NomTela = "Busca Munícipe";
        $envolvidos = "Munícipes";
        $pref = 'S';
        break;
    case 327: //Santa Casa
        $labelNome = "Nome do Colaborador";
        $abaNome = "Colaborador";
        $dadoConsulta = "NOM_COLABORADOR";
        $NomTela = "Busca Colaborador";
        $envolvidos = "Colaboradores";
        $pref = 'S';
        break; 
    default:
        $labelNome = "Nome do Cliente";
        $abaNome = "Cliente";
        $dadoConsulta = "NOM_CLIENTE";
        $NomTela = "Busca Cliente";
        $envolvidos = "Clientes";
        $pref = 'N';
		break;
}


?>

