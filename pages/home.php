<?php
$slides = MySql::conectar()->prepare("SELECT * FROM `tb_admin.slides`");
$slides->execute();
$slides = $slides->fetchAll();
?>
<!--banner-principal-->
<section class="banner-principal">

    <?php foreach($slides as $key => $value) {
    ?>
    <div style="background-image:url('<?php echo INCLUDE_PATH_PAINEL; ?>uploads/<?php echo $value['slide']; ?>')"
        class="banner-single"></div>
    <!--banner single-->
    <?php } ?>

    <div class="overlay"></div>
    <!--Overlay-->
    <div class="center">
        <form action="">
            <h2>Digite seu Email para receber as novidades do mundo dos games!</h2>
            <input type="email" name="email" id="email" required>
            <input type="submit" name="enviar" value="Enviar">
        </form>
    </div>
    <!--bullets-->
    <div class="bullets">
    </div>
    <!--bullets-->

</section>
<!--banner-principal-->

<!-- Descrição do Autor -->
<section class="descricao-autor">
    <div class="center">
        <div class="w50">
            <img src="<?php echo INCLUDE_PATH; ?>assets/img/img.jpg" alt="Imagem do autor">
        </div>
        <div class="w50">
            <h2 class="text-center"><?php echo $infoSite['nome_autor']; ?></h2>
            <p class="text-center"><?php echo $infoSite['descricao']; ?></p>
        </div>
    </div>
</section>
<!-- Fim da descrição do autor -->


<!--especialidades-->
<section class="especialidades">
    <div class="center">
        <h2 class="title">Aqui você encontra</h2>
        <div class="w33 left box-especialidades">
            <h3><i class="<?php echo $infoSite['icone1']?>"></i></h3>
            <p><?php echo $infoSite['descricao1']?></p>
        </div>
        <div class="w33 left box-especialidades">
            <h3><i class="<?php echo $infoSite['icone2']?>"></i></h3>
            <p><?php echo $infoSite['descricao2']?></p>
        </div>
        <div class="w33 left box-especialidades">
            <h3><i class="<?php echo $infoSite['icone3']?>"></i></h3>
            <p><?php echo $infoSite['descricao3']?></p>
        </div>
        <div class="clear"></div>
        <!--clear float-->
    </div>
    <!--center-->
</section>
<!--especialidades-->

<!--extras-->
<section class="extras">
    <div class="center">
        <div id="depoimentos" class="w50 left depoimentos-container">
            <h2 class="title">Ultimos lançamentos</h2>
            <?php
                $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.depoimentos` ORDER BY order_id DESC LIMIT 3");
                $sql->execute();
                $depoimentos = $sql->fetchAll();
                foreach ($depoimentos as $key => $value) {?>
            <div class="depoimento-single">
                <p class="depoimento-descricao">
                    <?php echo $value['depoimento']; ?>
                </p>
                <p class="nome-autor"><?php echo $value['nome']; ?> - <?php echo $value['data']; ?></p>
            </div>
            <!--depoimentos-single-->
            <?php } ?>
            <div class="clear"></div>
            <!--clear float-->
        </div>
        <!--depoimentos-->
        <div id="servicos" class="w50 left servicos-container">
            <h2 class="title">Ultimas Competições</h2>
            <div class="servicos">
                <?php
                    $sql = MySql::conectar()->prepare("SELECT * FROM `tb_admin.servicos` ORDER BY order_id DESC LIMIT 3");
                    $sql->execute();
                    $depoimentos = $sql->fetchAll();
                    ?>
                <ul>
                    <?php foreach ($depoimentos as $key => $value) {?>
                    <li>
                        <?php echo $value['servico'];?>
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
        <!--servicos-->
        <div class="clear"></div>
        <!--clear float-->
    </div>
    <!--center-->
</section>
<!--extras-->