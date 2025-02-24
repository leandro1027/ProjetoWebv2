<?php
// Defina a variável $porPagina
$porPagina = 10; // Quantidade de notícias por página

// Função para truncar texto sem cortar palavras
function truncateText($text, $length) {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' ')) . '...';
    }
    return $text;
}

// Verifica se há uma categoria ou notícia específica na URL
$url = explode('/', $_GET['url']);
if (!isset($url[2])) {
    // Exibe a lista de notícias
?>
    <section class="header-noticias">
        <div class="center">
            <h2><i class="fa-solid fa-bell"></i> Acompanhe as últimas notícias do mundo dos games!</h2>
        </div>
    </section>

    <section class="container-portal">
        <div class="center">
            <div class="sidebar">
                <div class="box-content-sidebar">
                    <h3><i class="fas fa-search"></i> Pesquisar: </h3>
                    <form method="post" action="">
                        <input type="text" name="busca" placeholder="Digite..." required>
                        <input type="submit" name="acao" value="Pesquisar">
                    </form>
                </div>

                <div class="box-content-sidebar">
                    <h3><i class="fas fa-list"></i> Selecione a Categoria: </h3>
                    <form method="get" action="">
                        <select name="categoria" onchange="this.form.submit()">
                            <option value="" selected>Todas as categorias</option>
                            <?php
                            $categorias = MySql::conectar()->prepare("SELECT * FROM `tb_admin.categorias` ORDER BY order_id DESC");
                            $categorias->execute();
                            $categorias = $categorias->fetchAll();
                            foreach ($categorias as $value) {
                                $selected = (@$_GET['categoria'] == $value['slug']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($value['slug'], ENT_QUOTES, 'UTF-8') . '" ' . $selected . '>' . htmlspecialchars($value['nome'], ENT_QUOTES, 'UTF-8') . '</option>';
                            }
                            ?>
                        </select>
                    </form>
                </div>
            </div>

            <div class="conteudo-portal">
                <div class="header-conteudo-portal">
                    <?php
                    // Verifica se uma categoria foi selecionada
                    $categoriaSelecionada = null;
                    if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
                        $categoriaSlug = htmlspecialchars($_GET['categoria'], ENT_QUOTES, 'UTF-8');
                        $categoriaSelecionada = Painel::get('tb_admin.categorias', 'slug = ?', array($categoriaSlug));
                        if ($categoriaSelecionada) {
                            echo '<h2>Visualizando Posts em <span>' . htmlspecialchars($categoriaSelecionada['nome'], ENT_QUOTES, 'UTF-8') . '</span></h2>';
                        }
                    } else {
                        echo '<h2>Visualizando Todos os Posts</h2>';
                    }

                    // Verifica se o formulário de pesquisa foi enviado
                    $busca = '';
                    if (isset($_POST['acao']) && isset($_POST['busca'])) {
                        $busca = htmlspecialchars($_POST['busca'], ENT_QUOTES, 'UTF-8');
                        echo '<h2><i class="fa fa-check"></i> Resultados para: ' . $busca . '</h2>';
                    }

                    // Consulta para buscar notícias
                    $query = "SELECT * FROM `tb_admin.noticias`";
                    $where = [];
                    $params = [];

                    // Filtro por categoria
                    if ($categoriaSelecionada) {
                        $where[] = "categoria_id = ?";
                        $params[] = $categoriaSelecionada['id'];
                    }

                    // Filtro por pesquisa
                    if (!empty($busca)) {
                        $where[] = "titulo LIKE ?";
                        $params[] = "%$busca%";
                    }

                    // Combina os filtros na query
                    if (!empty($where)) {
                        $query .= " WHERE " . implode(" AND ", $where);
                    }

                    $query .= " ORDER BY order_id DESC";

                    // Paginação
                    if (isset($_GET['pagina'])) {
                        $pagina = (int)$_GET['pagina'];
                        $queryPagina = ($pagina - 1) * $porPagina;
                        $query .= " LIMIT $queryPagina, $porPagina";
                    } else {
                        $query .= " LIMIT 0, $porPagina";
                    }

                    $noticias = MySql::conectar()->prepare($query);
                    $noticias->execute($params);
                    $noticias = $noticias->fetchAll();

                    if (empty($noticias)) {
                        echo '<p>Nenhuma notícia encontrada.</p>';
                    } else {
                        foreach ($noticias as $value) {
                            $categoriaNome = Painel::get('tb_admin.categorias', 'id = ?', array($value['categoria_id']))['nome'];
                            echo '<div class="box-single-conteudo">
                                    <h2>' . htmlspecialchars($value['titulo'], ENT_QUOTES, 'UTF-8') . '</h2>
                                    <p>' . truncateText(strip_tags($value['conteudo']), 400) . '</p>
                                    <a href="' . INCLUDE_PATH . 'noticias/' . htmlspecialchars($categoriaNome, ENT_QUOTES, 'UTF-8') . '/' . htmlspecialchars($value['slug'], ENT_QUOTES, 'UTF-8') . '">Leia mais</a>
                                  </div>';
                        }
                    }
                    ?>
                </div>

                <div class="paginator">
                    <?php
                    $totalPaginas = ceil(count(Painel::getAll('tb_admin.noticias')) / $porPagina);
                    for ($i = 1; $i <= $totalPaginas; $i++) {
                        if ($i == @$pagina) {
                            echo '<a class="page-selected" href="' . INCLUDE_PATH_PAINEL . 'noticias?pagina=' . $i . '">' . $i . '</a>';
                        } else {
                            echo '<a href="' . INCLUDE_PATH_PAINEL . 'noticias?pagina=' . $i . '">' . $i . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
<?php
} else {
    // Exibe uma notícia específica
    include('noticias-single.php');
}
?>

