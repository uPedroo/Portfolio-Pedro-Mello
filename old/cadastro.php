<?php
    session_start();
    require_once('conexao.php');
    
    if (isset($_POST['cadastrar'])) {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        if (empty($nome) || empty($email) || empty($senha)){
            
            $mensagem = "Preencha todos os campos!";

        } else {

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = "
                INSERT INTO usuarios(nome, email, senha)
                VALUES (?, ?, ?);
            ";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("sss", $nome, $email, $senhaHash);

            if ($stmt->execute()) {

                $mensagem = "Usuário cadastrado com sucesso!";

            } else {

                $mensagem = "Erro ao cadastrar!";

            }
        }
    }

?>

<?php if (!empty($mensagem)) : ?>

<div class="modal fade" id="alertaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Aviso</h5>

                <button 
                    type="button" 
                    class="btn-close" 
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <?= $mensagem ?>
            </div>

            <div class="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-primary" 
                    data-bs-dismiss="modal">

                    OK

                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        var modal = new bootstrap.Modal(
            document.getElementById("alertaModal")
        );

        modal.show();

    });
</script>

<?php endif; ?>