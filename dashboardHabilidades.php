<?php

require_once('conexao.php');

$idUser = $_SESSION['user_id'];

// ======================================================
// LISTAR HABILIDADES
// ======================================================

$sql = "
    SELECT *
    FROM habilidades
    WHERE usuario_id = ?
    ORDER BY ordem ASC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $idUser);

$stmt->execute();

$habilidades = $stmt->get_result();

// ======================================================
// MODO EDIÇÃO
// ======================================================

$modoEdicao = false;

if (isset($_GET['editar'])) {

    $modoEdicao = true;

}

// ======================================================
// CONTAR TOTAL
// ======================================================

$sqlCount = "
    SELECT COUNT(*) as total
    FROM habilidades
    WHERE usuario_id = ?
";

$stmtCount = $conn->prepare($sqlCount);

$stmtCount->bind_param("i", $idUser);

$stmtCount->execute();

$resultadoCount = $stmtCount->get_result()->fetch_assoc();

$totalHabilidades = $resultadoCount['total'];

?>

<div class="container py-5">

    <div class="row mb-5" id="habilidades">

        <div class="col-12 d-flex justify-content-between align-items-center">

            <h1 class="text-light">
                Habilidades
            </h1>

            <?php if (
                !$modoEdicao &&
                $totalHabilidades > 0
            ) : ?>

                <a
                    href="dashboard.php?editar=true#habilidades"
                    class="btn btn-warning"
                >
                    Editar
                </a>

            <?php endif; ?>

        </div>

    </div>

    <!-- ================================================= -->
    <!-- VISUALIZAÇÃO -->
    <!-- ================================================= -->

    <?php if (!$modoEdicao) : ?>

        <div class="row">

            <?php 
            $habilidades->data_seek(0); // Reseta a posição do cursor
            while($habilidade = $habilidades->fetch_assoc()) : 
            ?>

                <div class="col-md-3 mb-4">

                    <div class="card bg-dark border-secondary p-4 h-100">

                        <img
                            src="<?= $habilidade['imagem'] ?>"
                            class="img-fluid mb-4"
                            style="
                                width: 100px;
                                height: 100px;
                                object-fit: contain;
                                margin: auto;
                            "
                        >

                        <h5 class="text-light text-center">
                            <?= $habilidade['nome'] ?>
                        </h5>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else : ?>

        <!-- ================================================= -->
        <!-- EDIÇÃO -->
        <!-- ================================================= -->

        <form method="POST">

            <input type="hidden" name="modulo" value="habilidades">
            <input type="hidden" name="acao" value="editar">

            <div class="row">

                <?php 
                $habilidades->data_seek(0); // Reseta a posição do cursor
                while($habilidade = $habilidades->fetch_assoc()) : 
                ?>

                    <div class="col-md-3 mb-4">

                        <div class="card bg-dark border-secondary p-3 h-100 position-relative">

                            <input
                                type="hidden"
                                name="id[]"
                                value="<?= $habilidade['id'] ?>"
                            >

                            <div class="mb-3">

                                <input
                                    type="text"
                                    name="nome[]"
                                    value="<?= $habilidade['nome'] ?>"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Nome"
                                    required
                                >

                            </div>

                            <div class="mb-3">

                                <input
                                    type="text"
                                    name="imagem[]"
                                    value="<?= $habilidade['imagem'] ?>"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Imagem"
                                    required
                                >

                            </div>

                            <div class="mb-3">

                                <input
                                    type="number"
                                    name="ordem[]"
                                    value="<?= $habilidade['ordem'] ?>"
                                    class="form-control bg-dark text-light border-secondary"
                                    min="1"
                                    max="8"
                                    required
                                >

                            </div>

                            <!-- EXCLUIR - Botão que submete em formulário separado -->

                            <button
                                type="button"
                                class="btn btn-danger w-100 btn-excluir-habilidade"
                                data-id="<?= $habilidade['id'] ?>"
                                onclick="excluirHabilidade(<?= $habilidade['id'] ?>)"
                            >
                                Excluir
                            </button>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

            <!-- SALVAR GLOBAL -->

            <div class="d-flex justify-content-end mt-4">

                <button
                    type="submit"
                    class="btn btn-success px-5"
                >
                    Salvar Alterações
                </button>

            </div>

        </form>

        <!-- FORMULÁRIO OCULTO PARA EXCLUIR -->
        <form id="formExcluirHabilidade" method="POST" style="display: none;">
            <input type="hidden" name="modulo" value="habilidades">
            <input type="hidden" name="acao" value="excluir">
            <input type="hidden" name="idHabilidade" id="idHabilidadeExcluir">
        </form>

        <script>
            function excluirHabilidade(id) {
                if (confirm('Tem certeza que deseja excluir esta habilidade?')) {
                    document.getElementById('idHabilidadeExcluir').value = id;
                    document.getElementById('formExcluirHabilidade').submit();
                }
            }
        </script>

    <?php endif; ?>

    <!-- ================================================= -->
    <!-- ADICIONAR NOVA HABILIDADE -->
    <!-- ================================================= -->

    <?php if ($totalHabilidades < 8) : ?>

        <div class="row mt-5">

            <div class="col-md-6 mx-auto">

                <div class="card bg-dark border-secondary p-4">

                    <h3 class="text-light text-center mb-4">
                        Nova Habilidade
                    </h3>

                    <form method="POST">

                        <input type="hidden" name="modulo" value="habilidades">
                        <input type="hidden" name="acao" value="adicionar">

                        <div class="mb-3">

                            <input
                                type="text"
                                name="nome"
                                class="form-control bg-dark text-light border-secondary"
                                placeholder="Nome da habilidade"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <input
                                type="text"
                                name="imagem"
                                class="form-control bg-dark text-light border-secondary"
                                placeholder="Link da imagem"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn-success w-100"
                        >
                            Adicionar habilidade
                        </button>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>