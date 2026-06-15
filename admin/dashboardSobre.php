<?php 

    require_once(__DIR__.'/../conexao.php');

    $idUser = $_SESSION['user_id'];

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

    // ====================================================
    // DEFINE MODO EDIÇÃO
    // ====================================================

    $modoEdicao = false;

    if (
        isset($_GET['editar']) ||
        !$dadosSobre
    ) {

        $modoEdicao = true;

    }

?>

<div class="container py-5" id="sobre">

    <h1 class="text-light text-center mb-5">
        Sobre
    </h1>

    <?php if ($modoEdicao): ?>

        <!-- FORMULÁRIO EDIÇÃO -->

        <form method="POST" action="">

            <input type="hidden" name="modulo" value="sobre">

            <div class="row align-items-center">

                <!-- TEXTO -->

                <div class="col-md-5 px-4">

                    <div class="mb-4">

                        <input 
                            type="text"
                            name="titulo"
                            required
                            maxlength="60"

                            value="<?= $dadosSobre['titulo'] ?? '' ?>"

                            class="form-control bg-dark text-light border-secondary"

                            placeholder="Título"
                        >

                        <small class="text-secondary">
                            Máximo 60 caracteres
                        </small>

                    </div>

                    <div class="mb-4">

                        <textarea
                            name="paragrafo1"
                            required
                            maxlength="400"

                            class="form-control bg-dark text-light border-secondary"

                            style="height: 150px;"
                            placeholder="Primeiro parágrafo"
                        ><?= $dadosSobre['paragrafo1'] ?? '' ?></textarea>

                        <small class="text-secondary">
                            Máximo 400 caracteres
                        </small>

                    </div>

                    <div class="mb-4">

                        <textarea
                            name="paragrafo2"
                            required
                            maxlength="400"

                            class="form-control bg-dark text-light border-secondary"

                            style="height: 150px;"
                            placeholder="Segundo parágrafo"
                        ><?= $dadosSobre['paragrafo2'] ?? '' ?></textarea>

                        <small class="text-secondary">
                            Máximo 400 caracteres
                        </small>

                    </div>

                </div>

                <div class="col-md-2"></div>

                <!-- IMAGEM -->

                <div class="col-md-5 d-flex flex-column align-items-center">

                    <img 
                        src="<?= $dadosSobre['imagem'] ?? './imgs/noPfp.jpg' ?>"
                        class="pfp mb-4"

                        style="
                            width: 350px;
                            height: 350px;
                            object-fit: cover;
                        "
                    >

                    <input 
                        type="text"
                        name="img_link"

                        required
                        maxlength="370"

                        value="<?= $dadosSobre['imagem'] ?? '' ?>"

                        class="form-control bg-dark text-light border-secondary"

                        placeholder="Link da imagem"
                    >

                    <small class="text-secondary mt-2">
                        Máximo 370 caracteres
                    </small>

                </div>

                <!-- BOTÃO -->

                <div class="col-12 d-flex justify-content-end mt-5">

                    <button 
                        type="submit"
                        class="btn btn-success px-5 py-2"
                    >
                        Salvar
                    </button>

                </div>

            </div>

        </form>

    <?php else: ?>

        <!-- VISUALIZAÇÃO -->

        <div class="row align-items-center">

            <div class="col-md-5">

                <h1 class="text-light mb-5">
                    <?= $dadosSobre['titulo'] ?>
                </h1>

                <p class="text-light fs-5 mb-4">
                    <?= nl2br($dadosSobre['paragrafo1']) ?>
                </p>

                <p class="text-light fs-5">
                    <?= nl2br($dadosSobre['paragrafo2']) ?>
                </p>

            </div>

            <div class="col-md-2"></div>

            <div class="col-md-5 d-flex justify-content-center">

                <img 
                    src="<?= $dadosSobre['imagem'] ?>"
                    class="pfp"

                    style="
                        width: 350px;
                        height: 350px;
                        object-fit: cover;
                    "
                >

            </div>

            <!-- BOTÃO EDITAR -->

            <div class="col-12 d-flex justify-content-end mt-5">

                <a 
                    href="dashboard.php?editar=true#sobre"
                    class="btn btn-warning px-5 py-2"
                >
                    Editar
                </a>

            </div>

        </div>

    <?php endif; ?>

</div>