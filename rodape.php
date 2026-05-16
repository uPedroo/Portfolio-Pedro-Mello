<?php

    $idUser = 1;

    // ====================================
    // BUSCAR DADOS ATUAIS
    // ====================================

    $sql = "
        SELECT *
        FROM rodape
        WHERE usuario_id = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $idUser);

    $stmt->execute();

    $dadosRodape = $stmt->get_result()->fetch_assoc();


?>
        
        <div class="container-fluid p-0">

            <div class="row rodape mt-5 py-5 mx-0 px-5">

                            <!-- INÍCIO -->
            <div class="col-md-4 h-100 d-flex flex-column justify-content-start">

                <a href="index.php"><h5 class="text-light mb-4 pointer">
                    Início
                </h5></a>

                <a href="sobre.php"><p class="mb-2 pointer">Sobre</p></a>
                <a href="habilidades.php"><p class="mb-2 pointer">Habilidades</p></a>
                <a href="projetos.php"><p class="mb-0 pointer">Projetos</p></a>

            </div>

            <!-- CONTATO -->
            <div class="col-md-4 h-100 d-flex flex-column justify-content-start">

                <h5 class="text-light mb-4">
                    Contato
                </h5>

                <p class="mb-2">
                    <?= $dadosRodape['email'] ?? '' ?>
                </p>

                <p class="mb-0">
                    <?= $dadosRodape['telefone'] ?? '' ?>
                </p>

            </div>

            <!-- REDES -->
            <div class="col-md-4 h-100 d-flex flex-column justify-content-start">

                <h5 class="text-light mb-4">
                    Redes Sociais
                </h5>

                <div class="d-flex gap-3">

                    <a href="<?= $dadosRodape['rede1_link'] ?? '' ?>" target="_blank">
                        <img src="<?= $dadosRodape['rede1_icone'] ?? '' ?>" width="32">
                    </a>

                    <a href="<?= $dadosRodape['rede2_link'] ?? '' ?>" target="_blank">
                        <img src="<?= $dadosRodape['rede2_icone'] ?? '' ?>" width="32">
                    </a>

                    <a href="<?= $dadosRodape['rede3_link'] ?? '' ?>" target="_blank">
                        <img src="<?= $dadosRodape['rede3_icone'] ?? '' ?>" width="32">
                    </a>

                     <a href="<?= $dadosRodape['rede4_link'] ?? '' ?>" target="_blank">
                        <img src="<?= $dadosRodape['rede4_icone'] ?? '' ?>" width="32">
                    </a>
                </div>

            </div>

            </div>

        </div>
    </body>
</html>