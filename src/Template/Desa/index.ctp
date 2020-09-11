<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Desa'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Kecamatan'), ['controller' => 'Kecamatan', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Kecamatan'), ['controller' => 'Kecamatan', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="desa index large-9 medium-8 columns content">
    <h3><?= __('Desa') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id') ?></th>
                <th><?= $this->Paginator->sort('kode_daerah') ?></th>
                <th><?= $this->Paginator->sort('nama_daerah') ?></th>
                <th><?= $this->Paginator->sort('kecamatan_id') ?></th>
                <th><?= $this->Paginator->sort('dibuat_oleh') ?></th>
                <th><?= $this->Paginator->sort('tgl_dibuat') ?></th>
                <th><?= $this->Paginator->sort('diubah_oleh') ?></th>
                <th><?= $this->Paginator->sort('tgl_diubah') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($desa as $desa): ?>
            <tr>
                <td><?= $this->Number->format($desa->id) ?></td>
                <td><?= h($desa->kode_daerah) ?></td>
                <td><?= h($desa->nama_daerah) ?></td>
                <td><?= $desa->has('kecamatan') ? $this->Html->link($desa->kecamatan->id, ['controller' => 'Kecamatan', 'action' => 'view', $desa->kecamatan->id]) : '' ?></td>
                <td><?= h($desa->dibuat_oleh) ?></td>
                <td><?= h($desa->tgl_dibuat) ?></td>
                <td><?= h($desa->diubah_oleh) ?></td>
                <td><?= h($desa->tgl_diubah) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $desa->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $desa->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $desa->id], ['confirm' => __('Are you sure you want to delete # {0}?', $desa->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>
