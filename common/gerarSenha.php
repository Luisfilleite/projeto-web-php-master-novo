<?php
function gerarSenha($comprimentoSenha = 8, $habilitarMaiusculas = True, $habilitarNumeros = True, $habilitarSimbolos = True){
    
    $bancoMinusculas = 'abcdefghijklmnopqrstuvwxyz';
    $bancoMaiusculas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $bancoNumeros = '123456789';
    $bancoSimbolos = '!@#$%*-';

    $senhaGerada = '';
    $bancoCaracteres = '';

    $bancoCaracteres .= $bancoMinusculas;
    if($habilitarMaiusculas){
        $bancoCaracteres.= $bancoMaiusculas;
    }
    if($habilitarNumeros){
        $bancoCaracteres .= $bancoNumeros;
    }
    if($habilitarSimbolos){
        $bancoCaracteres .= $bancoSimbolos;
    }

    $comprimentoTotal = strlen($bancoCaracteres); /*strlen calcula o numero de caracteres de uma string*/
    for($n = 1; $n <= $comprimentoSenha; $n++){
        $caractereAleatorio = mt_rand(0, $comprimentoTotal);
        $senhaGerada .= $bancoCaracteres[$caractereAleatorio - 1];
    }
    return $senhaGerada;
}
echo gerarSenha(8);
?>