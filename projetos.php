<?php

$idUser = 1;

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

?>


<div class="container projetos">
            <div class="row" id="projetos">
                <h1 class="text-center mt-5">Projetos</h1>
                <div class="col-12 px-5 d-flex flex-row justify-content-between mt-5 mb-5">

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
            </div>
        </div>   
        
        
    </div>
</div>