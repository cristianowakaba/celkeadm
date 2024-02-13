<?php
if (!defined('C8L6K7E')) {
    /*  header("Location:/"); */
    die("Erro: Página não encontrada!<br>");
}
if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>

<!-- Inicio do conteudo do administrativo -->
<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Situação da Página</span>
            <div class="top-list-right">
                <?php
                echo "<a href='" . URLADM . "add-sits-pages/index' class='btn-success'>Cadastrar</a>";
                ?>
            </div>

        </div>
        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Nome</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listSitsPages'] as $sitPages) {
                    extract($sitPages);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td>
                            <?php echo "<span style='color: $color'>$name</span>"; ?>
                        </td>

                        <td class="list-body-content">

                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id ?>" class="dropdown-action-item">

                                    <?php
                                    echo "<a href='" . URLADM . "view-sits-pages/index/$id'>Visualizar</a>";
                                    echo "<a href='" . URLADM . "edit-sits-pages/index/$id'>Editar</a>";
                                    echo "<a href='" . URLADM . "delete-sits-pages/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <?php echo $this->data['pagination']; ?>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->