<?php

    session_start();

    require_once(__DIR__.'/../conexao.php');

    // Ideia, para mostrar cada portfolio no headeradmin ter a parte visualizacao, na qual rerideciona para o index com user_id no link e usar o metodo get nas index para ir

    // ====================================
    // VERIFICA LOGIN
    // ====================================

    function verificarLogin() {

        if (!isset($_SESSION['user_id'])) {

            header("Location: logins.php");
            exit();

        }

    }

    verificarLogin();

    // ====================================
    // LOGOUT
    // ====================================

    function logout() {

        session_unset();
        session_destroy();

        header("Location: logins.php");
        exit();

    }

    if (isset($_GET['logout'])) {

        logout();

    }

    // HEADER APENAS APÓS LOGIN
    require_once('headerAdmin.php');

    // ====================================
    // PROCESSA EDIÇÕES GLOBAIS
    // ====================================
    require_once('editar_global.php');

?>

<div class="container mt-5">

    <h1 class="text-light mb-5">
        Dashboard
    </h1>

    <!-- SOBRE -->
    <section id="sobre" class="mb-5">

        <?php require_once('dashboardSobre.php'); ?>

    </section>

    <!-- HABILIDADES -->
    <section id="habilidades" class="mb-5">

        <?php require_once('dashboardHabilidades.php'); ?>

    </section>

    <!-- PROJETOS -->
    <section id="projetos" class="mb-5">

        <?php require_once('dashboardProjetos.php'); ?>

    </section>

    <!-- RODAPÉ -->
    <section id="rodape" class="mb-5">

        <?php require_once('dashboardRodape.php'); ?>

    </section>

</div>