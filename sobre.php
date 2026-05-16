<?php

    $idUser = 1;

    // ====================================================
    // BUSCAR DADOS DO SOBRE
    // ====================================================

    $dadosSobre = null;

    $sql = "
        SELECT *
        FROM sobre
        WHERE usuario_id = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $idUser);

    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $dadosSobre = $resultado->fetch_assoc();

    }


?>


<div class="container">

    <div 
        class="row align-items-center min-vh-100 mt-5"
        id="sobre"
    >

        <!-- TEXTO -->
        <div class="col-md-5 px-4">

            <h1 class="mb-4">
                <?php 
                    echo $dadosSobre['titulo'] ?? 'Título Padrão';
                ?>
            </h1>

            <p class="mb-3">
                <?php 
                    echo $dadosSobre['paragrafo1'] ?? 'Descrição Padrão';
                ?>
            </p>
            </p>

            <p>
                <?php 
                    echo $dadosSobre['paragrafo2'] ?? 'Descrição Padrão';
                ?>
            </p>

        </div>

        <!-- ESPAÇAMENTO CENTRAL -->
        <div class="col-md-2"></div>

        <!-- IMAGEM -->
        <div class="col-md-5 d-flex justify-content-center">

            <img 
                src="<?= $dadosSobre['imagem'] ?? './imgs/noPfp.jpg' ?>"
                class="pfp mb-4"

                style="
                    width: 350px;
                    height: 350px;
                    object-fit: cover;
                "
            >

        </div>