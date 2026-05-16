<?php
    session_start();
    require_once('conexao.php');
    require_once('header.php');

    if (isset($_SESSION['user_id'])) {

        header("Location: dashboard.php");
        exit();

    }

    $mensagem = "";

    // DEFINE O MODO DA TELA
    $modo = "login";

    if (isset($_GET['modo'])) {

        $modo = $_GET['modo'];

    }

    // =========================
    // CADASTRO
    // =========================

    if (isset($_POST['cadastrar'])) {

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        if (empty($nome) || empty($email) || empty($senha)) {

            $mensagem = "Preencha todos os campos!";

        } else {

            // VERIFICA SE EMAIL JÁ EXISTE
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

                $mensagem = "Este email já está cadastrado!";

            } else {

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = "
                    INSERT INTO usuarios(nome, email, senha)
                    VALUES (?, ?, ?)
                ";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param("sss", $nome, $email, $senhaHash);

                if ($stmt->execute()) {

                    $mensagem = "Usuário cadastrado com sucesso!";

                    $modo = "login";

                } else {

                    $mensagem = "Erro ao cadastrar!";

                }

            }

        }

    }

    // =========================
    // LOGIN
    // =========================

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

                    header("Location: dashboard.php");
                    exit;

                } else {

                    $mensagem = "Senha ou email incorretos!";

                }

            } else {

                $mensagem = "Senha ou email incorretos!";

            }

        }

    }
?>

    <div class="container vh-100 d-flex justify-content-center align-items-center">

    <div 
        class="card border-0 p-4 text-light"
        style="
            width: 400px;
            background-color: rgb(47, 47, 47);
            border-radius: 20px;
        "
    >

        <?php if ($modo == "login") : ?>

            <h2 class="text-center mb-4 text-white">
                Login
            </h2>

            <form method="POST" action="">

                <div class="mb-3">

                    <input 
                        type="email" 
                        name="email"
                        class="form-control bg-dark text-light border-secondary"
                        placeholder="Email"
                    >

                </div>

                <div class="mb-3">

                    <input 
                        type="password" 
                        name="senha"
                        class="form-control bg-dark text-light border-secondary"
                        placeholder="Senha"
                    >

                </div>

                <button 
                    type="submit"
                    name="logar"
                    class="btn btn-primary w-100 py-2"
                >
                    Entrar
                </button>

            </form>

            <div class="text-center mt-3">

                <small class="text-light">

                    Não possui conta?

                    <a 
                        href="logins.php?modo=cadastro"
                        class="text-info text-decoration-none"
                    >
                        Cadastre-se aqui
                    </a>

                </small>

            </div>

        <?php endif; ?>

        <?php if ($modo == "cadastro") : ?>

            <h2 class="text-center mb-4 text-white">
                Cadastro
            </h2>

            <form method="POST" action="">

                <div class="mb-3">

                    <input 
                        type="text" 
                        name="nome"
                        class="form-control bg-dark text-light border-secondary"
                        placeholder="Nome"
                    >

                </div>

                <div class="mb-3">

                    <input 
                        type="email" 
                        name="email"
                        class="form-control bg-dark text-light border-secondary"
                        placeholder="Email"
                    >

                </div>

                <div class="mb-3">

                    <input 
                        type="password" 
                        name="senha"
                        class="form-control bg-dark text-light border-secondary"
                        placeholder="Senha"
                    >

                </div>

                <button 
                    type="submit"
                    name="cadastrar"
                    class="btn btn-primary w-100 py-2"
                >
                    Cadastrar
                </button>

            </form>

            <div class="text-center mt-3">

                <small class="text-light">

                    Já possui conta?

                    <a 
                        href="logins.php?modo=login"
                        class="text-info text-decoration-none"
                    >
                        Faça login aqui
                    </a>

                </small>

            </div>

        <?php endif; ?>

    </div>

</div>

    <!-- MODAL -->

    <?php if (!empty($mensagem)) : ?>

        <div 
            class="modal fade" 
            id="alertaModal" 
            tabindex="-1"
        >

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Aviso
                        </h5>

                        <button 
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        >
                        </button>

                    </div>

                    <div class="modal-body">

                        <?= $mensagem ?>

                    </div>

                    <div class="modal-footer">

                        <button 
                            type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                        >
                            OK
                        </button>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (!empty($mensagem)) : ?>

        <script>

            document.addEventListener("DOMContentLoaded", function() {

                var modal = new bootstrap.Modal(
                    document.getElementById("alertaModal")
                );

                modal.show();

            });

        </script>

    <?php endif; ?>

</body>
</html>