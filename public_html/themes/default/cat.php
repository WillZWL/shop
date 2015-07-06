<?php $this->load->view('/default/header') ?>
    <?php if ($productList) : ?>
        <?php $this->load->view('/default/product/grid.php', ['product' => $productList]); ?>
    <?php endif; ?>
<?php $this->load->view('/default/footer') ?>
