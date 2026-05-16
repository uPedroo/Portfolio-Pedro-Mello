<?php

    require_once('conexao.php');

    $idUser = $_SESSION['user_id'];

    // =========================
    // LISTAR PROJETOS
    // =========================

    $sql = "
        SELECT *
        FROM projetos
        WHERE usuario_id = ?
        ORDER BY ordem ASC
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $idUser);

    $stmt->execute();

    $projetos = $stmt->get_result();

    // =========================
    // MODO EDIÇÃO
    // =========================

    $modoEdicao = false;

    if (isset($_GET['editar'])) {

        $modoEdicao = true;

    }

    // =========================
    // CONTAR TOTAL
    // =========================

    $sqlCount = "
        SELECT COUNT(*) as total
        FROM projetos
        WHERE usuario_id = ?
    ";

    $stmtCount = $conn->prepare($sqlCount);

    $stmtCount->bind_param("i", $idUser);

    $stmtCount->execute();

    $resultadoCount = $stmtCount->get_result()->fetch_assoc();

    $totalProjetos = $resultadoCount['total'];

?>

<div class="container">

    <!-- TÍTULO -->
    <div class="row">

        <div class="col-12 d-flex justify-content-between align-items-center">

            <h1 class="text-center mt-5 mb-5 text-light" id="projetos">
                Projetos
            </h1>

            <?php if (
                !$modoEdicao &&
                $totalProjetos > 0
            ) : ?>

                <a
                    href="dashboard.php?editar=true#projetos"
                    class="btn btn-warning"
                >
                    Editar
                </a>

            <?php endif; ?>

        </div>

    </div>

    <!-- ================================= -->
    <!-- VISUALIZAÇÃO -->
    <!-- ================================= -->

    <?php if (!$modoEdicao) : ?>

        <!-- GRID PROJETOS -->
        <div class="row">

            <?php 
            $projetos->data_seek(0); // Reseta cursor
            while($projeto = $projetos->fetch_assoc()) : 
            ?>

                <div class="col-md-4 mb-4">

                    <div class="card bg-dark border-secondary h-100 shadow">

                        <img 
                            src="<?= $projeto['imagem'] ?>"
                            class="card-img-top"
                            style="
                                height: 220px;
                                object-fit: cover;
                            "
                        >

                        <div class="card-body d-flex flex-column">

                            <h4 class="text-light">
                                <?= $projeto['titulo'] ?>
                            </h4>

                            <p class="text-light">
                                <?= $projeto['descricao'] ?>
                            </p>

                            <a 
                                href="<?= $projeto['link'] ?>"
                                target="_blank"
                                class="btn btn-primary mt-auto"
                            >
                                Ver Projeto
                            </a>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else : ?>

        <!-- ================================= -->
        <!-- EDIÇÃO -->
        <!-- ================================= -->

        <form method="POST">

            <input type="hidden" name="modulo" value="projetos">
            <input type="hidden" name="acao" value="editar">

            <div class="row">

                <?php 
                $projetos->data_seek(0); // Reseta cursor
                while($projeto = $projetos->fetch_assoc()) : 
                ?>

                    <div class="col-md-6 mb-4">

                        <div class="card bg-dark border-secondary p-4">

                            <input
                                type="hidden"
                                name="id[]"
                                value="<?= $projeto['id'] ?>"
                            >

                            <div class="mb-3">

                                <label class="text-light form-label">Título</label>

                                <input
                                    type="text"
                                    name="titulo[]"
                                    value="<?= $projeto['titulo'] ?>"
                                    class="form-control bg-dark text-light border-secondary"
                                    required
                                >

                            </div>

                            <div class="mb-3">

                                <label class="text-light form-label">Descrição</label>

                                <textarea
                                    name="descricao[]"
                                    class="form-control bg-dark text-light border-secondary"
                                    rows="3"
                                    required
                                ><?= $projeto['descricao'] ?></textarea>

                            </div>

                            <div class="mb-3">

                                <label class="text-light form-label">Imagem (Link)</label>

                                <input
                                    type="text"
                                    name="imagem[]"
                                    value="<?= $projeto['imagem'] ?>"
                                    class="form-control bg-dark text-light border-secondary"
                                    required
                                >

                            </div>

                            <div class="mb-3">

                                <label class="text-light form-label">Link do Projeto</label>

                                <input
                                    type="text"
                                    name="link[]"
                                    value="<?= $projeto['link'] ?>"
                                    class="form-control bg-dark text-light border-secondary"
                                    required
                                >

                            </div>

                            <div class="mb-3">

                                <label class="text-light form-label">Ordem</label>

                                <input
                                    type="number"
                                    name="ordem[]"
                                    value="<?= $projeto['ordem'] ?>"
                                    class="form-control bg-dark text-light border-secondary"
                                    min="1"
                                    max="6"
                                    required
                                >

                            </div>

                            <!-- EXCLUIR -->

                            <button
                                type="button"
                                class="btn btn-danger w-100 btn-excluir-projeto"
                                data-id="<?= $projeto['id'] ?>"
                                onclick="excluirProjeto(<?= $projeto['id'] ?>)"
                            >
                                Excluir
                            </button>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

            <!-- SALVAR GLOBAL -->

            <div class="d-flex justify-content-end mt-4 mb-5">

                <button
                    type="submit"
                    class="btn btn-success px-5"
                >
                    Salvar Alterações
                </button>

            </div>

        </form>

        <!-- FORMULÁRIO OCULTO PARA EXCLUIR -->
        <form id="formExcluirProjeto" method="POST" style="display: none;">
            <input type="hidden" name="modulo" value="projetos">
            <input type="hidden" name="acao" value="excluir">
            <input type="hidden" name="idProjeto" id="idProjetoExcluir">
        </form>

        <script>
            function excluirProjeto(id) {
                if (confirm('Tem certeza que deseja excluir este projeto?')) {
                    document.getElementById('idProjetoExcluir').value = id;
                    document.getElementById('formExcluirProjeto').submit();
                }
            }
        </script>

    <?php endif; ?>

    <!-- ================================= -->
    <!-- ADICIONAR NOVO PROJETO -->
    <!-- ================================= -->

    <?php if ($totalProjetos < 6) : ?>

        <div class="row mt-5 mb-5">

            <div class="col-md-8 mx-auto">

                <div class="card bg-dark border-secondary p-4 shadow">

                    <h3 class="text-light text-center mb-4">
                        Novo Projeto
                    </h3>

                    <form method="POST" action="">

                        <input type="hidden" name="modulo" value="projetos">
                        <input type="hidden" name="acao" value="adicionar">

                        <!-- TÍTULO -->
                        <div class="mb-3">

                            <input
                                type="text"
                                name="titulo"
                                class="form-control bg-dark text-light border-secondary"
                                placeholder="Título do projeto"
                                required
                            >

                        </div>

                        <!-- DESCRIÇÃO -->
                        <div class="mb-3">

                            <textarea
                                name="descricao"
                                class="form-control bg-dark text-light border-secondary"
                                placeholder="Descrição do projeto"
                                rows="4"
                                required
                            ></textarea>

                        </div>

                        <!-- IMAGEM -->
                        <div class="mb-3">

                            <input
                                type="text"
                                name="imagem"
                                class="form-control bg-dark text-light border-secondary"
                                placeholder="Link da imagem"
                                required
                            >

                        </div>

                        <!-- LINK -->
                        <div class="mb-3">

                            <input
                                type="text"
                                name="link"
                                class="form-control bg-dark text-light border-secondary"
                                placeholder="Link do projeto"
                                required
                            >

                        </div>

                        <!-- BOTÃO -->
                        <button
                            type="submit"
                            class="btn btn-success w-100"
                        >
                            Salvar Projeto
                        </button>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>