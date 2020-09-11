<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $desa->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $desa->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Desa'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Kecamatan'), ['controller' => 'Kecamatan', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Kecamatan'), ['controller' => 'Kecamatan', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="desa form large-9 medium-8 columns content">
    <?= $this->Form->create($desa) ?>
    <fieldset>
        <legend><?= __('Edit Desa') ?></legend>
        <?php
            echo $this->Form->input('kode_daerah');
            echo $this->Form->input('nama_daerah');
            echo $this->Form->input('kecamatan_id', ['options' => $kecamatan]);
            echo $this->Form->input('dibuat_oleh');
            echo $this->Form->input('tgl_dibuat', ['empty' => true]);
            echo $this->Form->input('diubah_oleh');
            echo $this->Form->input('tgl_diubah', ['empty' => true]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
