<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Desa'), ['action' => 'edit', $desa->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Desa'), ['action' => 'delete', $desa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $desa->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Desa'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Desa'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Kecamatan'), ['controller' => 'Kecamatan', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Kecamatan'), ['controller' => 'Kecamatan', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="desa view large-9 medium-8 columns content">
    <h3><?= h($desa->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Kode Daerah') ?></th>
            <td><?= h($desa->kode_daerah) ?></td>
        </tr>
        <tr>
            <th><?= __('Nama Daerah') ?></th>
            <td><?= h($desa->nama_daerah) ?></td>
        </tr>
        <tr>
            <th><?= __('Kecamatan') ?></th>
            <td><?= $desa->has('kecamatan') ? $this->Html->link($desa->kecamatan->id, ['controller' => 'Kecamatan', 'action' => 'view', $desa->kecamatan->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Dibuat Oleh') ?></th>
            <td><?= h($desa->dibuat_oleh) ?></td>
        </tr>
        <tr>
            <th><?= __('Diubah Oleh') ?></th>
            <td><?= h($desa->diubah_oleh) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($desa->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Tgl Dibuat') ?></th>
            <td><?= h($desa->tgl_dibuat) ?></td>
        </tr>
        <tr>
            <th><?= __('Tgl Diubah') ?></th>
            <td><?= h($desa->tgl_diubah) ?></td>
        </tr>
    </table>
</div>
