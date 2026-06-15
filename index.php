<?php

    require_once('conexao.php');
    require_once('header.php');
    echo '<main>';
    require_once('sobre.php');
    require_once('habilidades.php');
    require_once('projetos.php');
    require_once('rodape.php');
    echo '</main>';

    function logout() {

        session_unset();
        session_destroy();


    }

    logout();


?>

