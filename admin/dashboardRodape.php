<?php

    require_once(__DIR__.'/../conexao.php');

    $idUser = $_SESSION['user_id'];

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

<form method="POST" action="" class="container mt-5">

    <input type="hidden" name="modulo" value="rodape">
    <input type="hidden" name="acao" value="salvar">

    <h2 class="text-light mb-4" id="rodape">
        Rodapé
    </h2>

    <!-- CONTATO -->

    <input 
        type="email"
        name="email"
        placeholder="Email"
        class="form-control mb-3 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['email'] ?? '' ?>"
    >

    <input 
        type="text"
        name="telefone"
        placeholder="Telefone"
        class="form-control mb-4 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['telefone'] ?? '' ?>"
    >

    <!-- REDE 1 -->

    <input 
        type="text"
        name="rede1_link"
        placeholder="Link da rede social 1"
        class="form-control mb-2 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede1_link'] ?? '' ?>"
    >

    <input 
        type="text"
        name="rede1_icone"
        placeholder="Ícone da rede social 1"
        class="form-control mb-4 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede1_icone'] ?? '' ?>"
    >

    <!-- REDE 2 -->

    <input 
        type="text"
        name="rede2_link"
        placeholder="Link da rede social 2"
        class="form-control mb-2 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede2_link'] ?? '' ?>"
    >

    <input 
        type="text"
        name="rede2_icone"
        placeholder="Ícone da rede social 2"
        class="form-control mb-4 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede2_icone'] ?? '' ?>"
    >

    <!-- REDE 3 -->

    <input 
        type="text"
        name="rede3_link"
        placeholder="Link da rede social 3"
        class="form-control mb-2 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede3_link'] ?? '' ?>"
    >

    <input 
        type="text"
        name="rede3_icone"
        placeholder="Ícone da rede social 3"
        class="form-control mb-4 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede3_icone'] ?? '' ?>"
    >

    <!-- REDE 4 -->

    <input 
        type="text"
        name="rede4_link"
        placeholder="Link da rede social 4"
        class="form-control mb-2 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede4_link'] ?? '' ?>"
    >

    <input 
        type="text"
        name="rede4_icone"
        placeholder="Ícone da rede social 4"
        class="form-control mb-4 bg-dark text-light border-secondary"

        value="<?= $dadosRodape['rede4_icone'] ?? '' ?>"
    >

    <div class="d-flex gap-3">

        <button 
            type="submit"
            class="btn btn-success"
        >
            Salvar
        </button>

        <?php if ($dadosRodape) : ?>

            <button 
                type="button"
                class="btn btn-danger"
                onclick="excluirRodape()"
            >
                Excluir
            </button>

        <?php endif; ?>

    </div>

</form>

<!-- FORMULÁRIO OCULTO PARA EXCLUIR -->
<form id="formExcluirRodape" method="POST" style="display: none;">
    <input type="hidden" name="modulo" value="rodape">
    <input type="hidden" name="acao" value="excluir">
</form>

<script>
    function excluirRodape() {
        if (confirm('Tem certeza que deseja excluir todos os dados do rodapé?')) {
            document.getElementById('formExcluirRodape').submit();
        }
    }
</script>

