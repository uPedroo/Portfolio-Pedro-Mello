<?php

$idUser = 1;

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


?>

<div class="container">
    <div class="row" id="habilidades">
        <h1 class="text-center mt-5">Habilidades</h1>
        
            <div class="col-8 d-flex flex-row justify-content-between mt-5 mb-5 mx-auto">
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
        </div>
        
    </div>
</div>