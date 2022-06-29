<?php
session_start();
    if (isset($_POST['mailUsuario']) && isset($_POST['senhaUsuario'])) {
        include 'conectaBanco.php';

        $mailUsuario = $_POST['mailUsuario'];
        $senhaUsuario = $_POST['senhaUsuario'];
        $senhaUsuario = MD5($senhaUsuario);

        $sqlUsuario = "SELECT codigoUsuario, nomeUsuario FROM usuarios WHERE mailUsuario = :mailUsuario
                        AND senhaUsuario = :senhaUsuario LIMIT 1";

        $sqlUsuarioST = $conexao->prepare($sqlUsuario);
        $sqlUsuarioST->bindVAlue(':mailUsuario', $mailUsuario);
        $sqlUsuarioST->bindVAlue(':senhaUsuario', $senhaUsuario);
        $sqlUsuarioST->execute();

        $quantidadeUsuarios = $sqlUsuarioST->rowCount();

        if ($quantidadeUsuarios == 1) {
            $resultadoUsuarios = $sqlUsuarioST->fetchAll();
            list($codigoUsuario, $nomeCompletoUsuario) = $resultadoUsuarios[0];

            $_SESSION['verificaUsuarioLogado'] = True;
            $_SESSION['codigoUsuarioLogado'] = $codigoUsuario;
            $nomeUsuario = explode(' ', $nomeCompletoUsuario);
            $_SESSION['nomeUsuarioLogado'] = $nomeUsuario[0];

            header('Location: main.php');
        }
        else { // usuários ou senha incorretos
            header('Location: index.php?codMsg=002');
        }
    }
    else { // usuários e senha não informado
        header('Location: index.php?codMsg=001');
    }
?>