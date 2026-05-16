<?php
    session_start();
    require_once('conexao.php');

    if (isset($_POST['logar'])) {

        $email = $_POST['email'];
        $senha = $_POST['senha'];

        if (empty($email) || empty($senha)) {

            $mensagem = "Preencha todos os campos!";

        } else {

            $sql = "
                SELECT * 
                FROM usuarios 
                WHERE email = ?
            ";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("s", $email);

            $stmt->execute();

            $resultado = $stmt->get_result();

            $user = $resultado->fetch_assoc();

            if ($user) {

                if (password_verify($senha, $user['senha'])) {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nome'] = $user['nome'];

                    header("Location: index.php");
                    exit;

                } else {

                    $mensagem = "Senha ou email incorretas!";

                }

            } else {

                $mensagem = "Senha ou email incorretas!";

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