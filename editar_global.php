<?php

/**
 * ============================================================
 * CENTRALIZADOR GLOBAL DE EDIÇÕES
 * ============================================================
 * Processa POST/DELETE de todos os 4 módulos:
 * - sobre
 * - habilidades
 * - projetos
 * - rodape
 * 
 * REDIRECIONAMENTO: Sempre volta para dashboard.php
 * MANTÉM SESSÃO: Não há risco de "headers already sent"
 */

require_once('conexao.php');

$idUser = $_SESSION['user_id'];

// ============================================================
// VERIFICA SE HÁ REQUISIÇÃO DE PROCESSAMENTO
// ============================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

$modulo = $_POST['modulo'] ?? '';

// ============================================================
// MÓDULO: SOBRE
// ============================================================

if ($modulo === 'sobre') {

    $titulo = $_POST['titulo'] ?? '';
    $par1 = $_POST['paragrafo1'] ?? '';
    $par2 = $_POST['paragrafo2'] ?? '';
    $img = $_POST['img_link'] ?? '';

    // Verifica se existe
    $sql = "SELECT * FROM sobre WHERE usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUser);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // UPDATE
        $sql = "UPDATE sobre SET titulo = ?, paragrafo1 = ?, paragrafo2 = ?, imagem = ? WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $titulo, $par1, $par2, $img, $idUser);
        $stmt->execute();
    } else {
        // INSERT
        $sql = "INSERT INTO sobre (usuario_id, titulo, paragrafo1, paragrafo2, imagem) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $idUser, $titulo, $par1, $par2, $img);
        $stmt->execute();
    }

    header("Location: dashboard.php#sobre");
    exit();
}

// ============================================================
// MÓDULO: HABILIDADES
// ============================================================

if ($modulo === 'habilidades') {

    $acao = $_POST['acao'] ?? '';

    // ========================================================
    // ADICIONAR HABILIDADE
    // ========================================================
    
    if ($acao === 'adicionar') {

        $nome = $_POST['nome'] ?? '';
        $imagem = $_POST['imagem'] ?? '';

        // Verifica limite de 8
        $sql = "SELECT COUNT(*) as total FROM habilidades WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado['total'] < 8) {
            $ordem = $resultado['total'] + 1;

            $sql = "INSERT INTO habilidades (usuario_id, nome, imagem, ordem) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issi", $idUser, $nome, $imagem, $ordem);
            $stmt->execute();
        }

        header("Location: dashboard.php#habilidades");
        exit();
    }

    // ========================================================
    // EDITAR HABILIDADES (salvar múltiplas)
    // ========================================================
    
    if ($acao === 'editar') {

        if (isset($_POST['id']) && is_array($_POST['id'])) {

            foreach ($_POST['id'] as $index => $idHabilidade) {

                $nome = $_POST['nome'][$index] ?? '';
                $imagem = $_POST['imagem'][$index] ?? '';
                $ordem = $_POST['ordem'][$index] ?? 0;

                $sql = "UPDATE habilidades SET nome = ?, imagem = ?, ordem = ? WHERE id = ? AND usuario_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssiii", $nome, $imagem, $ordem, $idHabilidade, $idUser);
                $stmt->execute();
            }

            // Reorganiza as ordens
            $sql = "SELECT id FROM habilidades WHERE usuario_id = ? ORDER BY ordem ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idUser);
            $stmt->execute();
            $resultado = $stmt->get_result();

            $novaOrdem = 1;
            while ($row = $resultado->fetch_assoc()) {
                $sqlUpdate = "UPDATE habilidades SET ordem = ? WHERE id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("ii", $novaOrdem, $row['id']);
                $stmtUpdate->execute();
                $novaOrdem++;
            }
        }

        header("Location: dashboard.php#habilidades");
        exit();
    }

    // ========================================================
    // EXCLUIR HABILIDADE
    // ========================================================
    
    if ($acao === 'excluir') {

        $idHabilidade = $_POST['idHabilidade'] ?? 0;

        $sql = "DELETE FROM habilidades WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idHabilidade, $idUser);
        $stmt->execute();

        // Reorganiza as ordens após deletar
        $sql = "SELECT id FROM habilidades WHERE usuario_id = ? ORDER BY ordem ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $novaOrdem = 1;
        while ($row = $resultado->fetch_assoc()) {
            $sqlUpdate = "UPDATE habilidades SET ordem = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ii", $novaOrdem, $row['id']);
            $stmtUpdate->execute();
            $novaOrdem++;
        }

        header("Location: dashboard.php#habilidades");
        exit();
    }
}

// ============================================================
// MÓDULO: PROJETOS
// ============================================================

if ($modulo === 'projetos') {

    $acao = $_POST['acao'] ?? '';

    // ========================================================
    // ADICIONAR PROJETO
    // ========================================================
    
    if ($acao === 'adicionar') {

        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $imagem = $_POST['imagem'] ?? '';
        $link = $_POST['link'] ?? '';

        // Verifica limite de 6
        $sql = "SELECT COUNT(*) as total FROM projetos WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado['total'] < 6) {
            $ordem = $resultado['total'] + 1;

            $sql = "INSERT INTO projetos (usuario_id, titulo, descricao, imagem, link, ordem) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssi", $idUser, $titulo, $descricao, $imagem, $link, $ordem);
            $stmt->execute();
        }

        header("Location: dashboard.php#projetos");
        exit();
    }

    // ========================================================
    // EDITAR PROJETOS (salvar múltiplos)
    // ========================================================
    
    if ($acao === 'editar') {

        if (isset($_POST['id']) && is_array($_POST['id'])) {

            foreach ($_POST['id'] as $index => $idProjeto) {

                $titulo = $_POST['titulo'][$index] ?? '';
                $descricao = $_POST['descricao'][$index] ?? '';
                $imagem = $_POST['imagem'][$index] ?? '';
                $link = $_POST['link'][$index] ?? '';
                $ordem = $_POST['ordem'][$index] ?? 0;

                $sql = "UPDATE projetos SET titulo = ?, descricao = ?, imagem = ?, link = ?, ordem = ? WHERE id = ? AND usuario_id = ?";
                $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssiii", $titulo, $descricao, $imagem, $link, $ordem, $idProjeto, $idUser);
                $stmt->execute();
            }

            // Reorganiza as ordens
            $sql = "SELECT id FROM projetos WHERE usuario_id = ? ORDER BY ordem ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $idUser);
            $stmt->execute();
            $resultado = $stmt->get_result();

            $novaOrdem = 1;
            while ($row = $resultado->fetch_assoc()) {
                $sqlUpdate = "UPDATE projetos SET ordem = ? WHERE id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("ii", $novaOrdem, $row['id']);
                $stmtUpdate->execute();
                $novaOrdem++;
            }
        }

        header("Location: dashboard.php#projetos");
        exit();
    }

    // ========================================================
    // EXCLUIR PROJETO
    // ========================================================
    
    if ($acao === 'excluir') {

        $idProjeto = $_POST['idProjeto'] ?? 0;

        $sql = "DELETE FROM projetos WHERE id = ? AND usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idProjeto, $idUser);
        $stmt->execute();

        // Reorganiza as ordens após deletar
        $sql = "SELECT id FROM projetos WHERE usuario_id = ? ORDER BY ordem ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $novaOrdem = 1;
        while ($row = $resultado->fetch_assoc()) {
            $sqlUpdate = "UPDATE projetos SET ordem = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ii", $novaOrdem, $row['id']);
            $stmtUpdate->execute();
            $novaOrdem++;
        }

        header("Location: dashboard.php#projetos");
        exit();
    }
}

// ============================================================
// MÓDULO: RODAPÉ
// ============================================================

if ($modulo === 'rodape') {

    $acao = $_POST['acao'] ?? '';

    // ========================================================
    // SALVAR/EDITAR RODAPÉ
    // ========================================================
    
    if ($acao === 'salvar') {

        $email = $_POST['email'] ?? '';
        $telefone = $_POST['telefone'] ?? '';

        $rede1_link = $_POST['rede1_link'] ?? '';
        $rede1_icone = $_POST['rede1_icone'] ?? '';

        $rede2_link = $_POST['rede2_link'] ?? '';
        $rede2_icone = $_POST['rede2_icone'] ?? '';

        $rede3_link = $_POST['rede3_link'] ?? '';
        $rede3_icone = $_POST['rede3_icone'] ?? '';

        $rede4_link = $_POST['rede4_link'] ?? '';
        $rede4_icone = $_POST['rede4_icone'] ?? '';

        // Verifica se existe
        $sql = "SELECT * FROM rodape WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // UPDATE
            $sql = "UPDATE rodape SET email = ?, telefone = ?, rede1_link = ?, rede1_icone = ?, rede2_link = ?, rede2_icone = ?, rede3_link = ?, rede3_icone = ?, rede4_link = ?, rede4_icone = ? WHERE usuario_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sssssssssssi",
                $email,
                $telefone,
                $rede1_link,
                $rede1_icone,
                $rede2_link,
                $rede2_icone,
                $rede3_link,
                $rede3_icone,
                $rede4_link,
                $rede4_icone,
                $idUser
            );
            $stmt->execute();
        } else {
            // INSERT
            $sql = "INSERT INTO rodape (usuario_id, email, telefone, rede1_link, rede1_icone, rede2_link, rede2_icone, rede3_link, rede3_icone, rede4_link, rede4_icone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "issssssssss",
                $idUser,
                $email,
                $telefone,
                $rede1_link,
                $rede1_icone,
                $rede2_link,
                $rede2_icone,
                $rede3_link,
                $rede3_icone,
                $rede4_link,
                $rede4_icone
            );
            $stmt->execute();
        }

        header("Location: dashboard.php#rodape");
        exit();
    }

    // ========================================================
    // EXCLUIR RODAPÉ
    // ========================================================
    
    if ($acao === 'excluir') {

        $sql = "DELETE FROM rodape WHERE usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUser);
        $stmt->execute();

        header("Location: dashboard.php#rodape");
        exit();
    }
}

?>
