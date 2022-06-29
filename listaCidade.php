<?php
    include "conectaBanco.php";
    /* get url */
    /*_GET contem todas as variaveis passadas pela url usando dentro do vetor _GET, _GET é uma variavel de sessao*/
    if (isset($_GET['codigoEstado'])){
        $codigoEstado = $_GET['codigoEstado'];
        /*echo "Url passada foi $codigoEstado";*/
        /* para evitar sql injection não podemos colocar diretamente o valor da url no sql query */
        $sqlCidades = "SELECT codigoCidade, nomeCidade FROM cidades WHERE codigoEstado=:codigoEstado";
        /* o objeto de conexão tem o metodo prepare para preparar a string que será executada*/
        $sqlCidadesST = $conexao->prepare($sqlCidades);
        /*juntamos o valor da string da url com a nossa string utilizando o bindValue*/
        $sqlCidadesST->bindValue(':codigoEstado', $codigoEstado);

        $sqlCidadesST->execute();

        $resultadoCidades = $sqlCidadesST->fetchAll();

        echo "<option value=\"\">Escolha a sua cidade </option>\n";
        foreach ($resultadoCidades as list($codigoCidade, $nomeCidade)){
            echo "<option value=\"$codigoCidade\">$nomeCidade</option>\n";
        }
    }

?>