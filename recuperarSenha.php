<?php
    require 'common/PHPMailer.php';
    require 'common/SMTP.php';
    require 'common/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;


    if (isset($_POST['mailUsuario'])) {
      include "conectaBanco.php";

      $mailUsuario = $_POST['mailUsuario'];


      $sqlUsuario = "SELECT codigoUsuario, nomeUsuario FROM usuarios WHERE mailUsuario=:mailUsuario LIMIT 1";
      
      $sqlUsuarioST = $conexao->prepare($sqlUsuario);  
      
      $sqlUsuarioST->bindValue(':mailUsuario', $mailUsuario);

      $sqlUsuarioST->execute();

      $quantidadeUsuarios = $sqlUsuarioST->rowCount();

      if ($quantidadeUsuarios == 1) {
        $resultadoUsuario = $sqlUsuarioST->fetchALL();

        list($codigousuario, $nomeUsuario) = $resultadoUsuario[0];
        
        $nomeCompletousuario = explode(' ', $nomeUsuario);
        $nomeUsuario = $nomeCompletoUsuario[0];
        
        include "common/gerarSenha.php";

        $novaSenha = gerarSenha(8);
        $novaSenhaMD5 = md5($novaSenha);

        $sqlAlterarSenha = "UPDATE usuarios SET senhaUsuario=:novaSenhaMD5 WHERE codigoUsuario=:codigoUsuario";

        $sqlAlterarSenhaST = $conexao->prepare($sqlAlterarSenha);

        $sqlAlterarSenhaST->bindValue(':novaSenhaMD5', $novaSenhaMD5);
        $sqlAlterarSenhaST->bindValue(':codigoUsuario', $codigoUsuario);

        if ($sqlAlterarSenhaST->execute()){

          include 'common/constantes.php';
          //parametros para acessar o servidor de email
          $mail = new PHPMailer();
          $mail->IsSMTP();
          $mail->SMTPDebug = SMTP::DEBUG_SERVER;
          $mail-> SMTPAuth = true;
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Host = 'smtp.gmail.com';
          $mail->Port = 465;
          $mail->Username = GUSER;
          $mail->Password = GPWD;

          //parametros da mensagem
          $mensagem = "Olá $nomeUsuario!<br/><br/>
                      Recebemos sua solicitação de alteração de senha no nosso sistema.<br/><br/>
                      Sua nova senha é: <span style=\"font-weight: bold; color #FF0000\">$novaSenha</span><br/><br/>
                      Para sua segurança troque esse senha quando acessar o sistama.<br/><br/> 
                      Atenciosamente,<br/>
                      Equipe de desenvolvimento."; 
          $mail->isHTML(true);
          $mail->Charset = "UTF8";
          $mail->SetFrom(GUSER, GNAME);
          $mail->AddAddress($mailUsuario);
          $mail->Subject = 'recuperação de senha';
          $mail->Body = $mensagem;
          
          // mandar o e-mail
          if ($mail->send()){
            header("Location: index.php?codMsg=008");
          }else {// erro ao gerar a nova senha}
          header("Location: index.php?codMsg=007");
          echo "Erro";
          }
        } else {//erro ao gerar a nova senha
          //header("Location: index.php?codMsg=006");
        }
      } else {// usuário não cadastrado
        //header("Location: index.php?codMsg=005");
      }
    } else {// e-mail do usuário não informado
      //header("Location: index.php?codMsg=004");
    }
?>
