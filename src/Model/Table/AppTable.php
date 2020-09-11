<?php
/**
 * Created by PhpStorm.
 * User: core
 * Date: 22/11/16
 * Time: 23:31
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Datasource\ConnectionManager;
use Cake\Log\LogTrait;
use ArrayObject;
use App\Service\AuthService;
use Cake\ORM\TableRegistry;

class AppTable extends Table
{
    use LogTrait;

    protected $userSession;
    protected $instansiSession;
    protected $unitSession;
    protected $jenisIzinIds;
    protected $filteredBeforeFind = true;
    protected $filteredBeforeSave = true;
    protected $strictUpdate = false; // if true then instansi_id must be the same
    protected $strictDelete = false; // if true then instansi_id must be the same

    /**
     * @param boolean $filteredBeforeFind
     */
    public function setFilteredBeforeFind($filteredBeforeFind)
    {
        $this->filteredBeforeFind = $filteredBeforeFind;
    }

    /**
     * @param boolean $filteredBeforeSave
     */
    public function setFilteredBeforeSave($filteredBeforeSave)
    {
        $this->filteredBeforeSave = $filteredBeforeSave;
    }

    public function setInstansi($instansi)
    {
        $this->instansiSession = $instansi;
        return $this;
    }

    public function setUnit($unit)
    {
        $this->unitSession = $unit;
        return $this;
    }

    public function setJenisIzinPengguna($jenisIzinIds)
    {
        $this->jenisIzinIds = $jenisIzinIds;
        return $this;
    }

    public function setUser($user) {
        $this->userSession = $user;
        return $this;
    }

    public function getInstansi()
    {
        return $this->instansiSession;
    }

    public function getUser()
    {
        return $this->userSession;
    }

    /**
     * make sure to set userSession first before calling this function
     */
    public function isPemohon()
    {
        return $this->userSession &&
            $this->userSession->related_object_name == AuthService::PEMOHON_OBJECT;
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if ($this->filteredBeforeFind) {
            $alias = $this->getRegistryAlias();

            switch ($alias) {
                case 'Unit':
                case 'Instansi':
                    if ($this->instansiSession) {
                        $query->where([
                            'OR' => [
                                $this->_alias . '.id' => $this->instansiSession->id,
                                $this->_alias . '.parent_id' => $this->instansiSession->id
                            ]
                        ]);
                    }
                    break;
                case 'Pengguna':
                    if ($this->instansiSession) {
                        if (array_key_exists('Pegawai', $query->contain())) {
                            // Display data with the following condition:
                            // pegawai.instansi_id match current session instansi_id, or
                            // pegawai.instansi_id IS NULL and peran.instansi_id match current session instansi_id
                            $query->where([
                                'OR' => [
                                    'Pegawai.instansi_id' => $this->instansiSession->id,
                                    'AND' => [
                                        'Peran.instansi_id' => $this->instansiSession->id,
                                        'Pegawai.instansi_id IS' => null,
                                    ]
                                ]
                            ]);
                        }
                    }
                    if ($this->isPemohon()) {
                        $query->where(
                            ['Pengguna.id' => $this->userSession->id]
                        );
                    }
                    break;
                case 'PermohonanIzin':
                    if (!empty($this->jenisIzinIds) && $this->instansiSession) {
                        $query->where([
                            $this->_alias . '.jenis_izin_id IN' => $this->jenisIzinIds,
                            'OR' => [
                                $this->_alias . '.instansi_id' => $this->instansiSession->id,
                                $this->_alias . '.instansi_id IS' => null
                            ]
                        ]);
                    } else {
                        if ($this->instansiSession) {
                            if ($this->isPemohon()) {
                                $query->where([
                                    'OR' => [
                                        $this->_alias . '.instansi_id' => $this->instansiSession->id,
                                    ]
                                ]);
                            } else {
                                $query->where([
                                    'OR' => [
                                        $this->_alias . '.instansi_id' => $this->instansiSession->id,
                                        $this->_alias . '.instansi_id IS' => null
                                    ]
                                ]);
                            }
                        }
                    }
                    break;
                case 'Peran':
                    if ($this->instansiSession) {
                        $query->where([
                            $this->_alias . '.instansi_id' => $this->instansiSession->id
                        ]);
                    }
                    break;
                case 'JenisIzin':
                    if (!empty($this->jenisIzinIds) && $this->instansiSession) {
                        $query->where([
                            $this->_alias . '.id IN' => $this->jenisIzinIds,
                            'OR' => [
                                $this->_alias . '.instansi_id' => $this->instansiSession->id,
                                $this->_alias . '.instansi_id IS' => null
                            ]
                        ]);
                    } else {
                        if ($this->instansiSession) {
                            if ($this->isPemohon()) {
                                $query->where([
                                    'OR' => [
                                        $this->_alias . '.instansi_id' => $this->instansiSession->id,
                                    ]
                                ]);
                            } else {
                                $query->where([
                                    'OR' => [
                                        $this->_alias . '.instansi_id' => $this->instansiSession->id,
                                        $this->_alias . '.instansi_id IS' => null
                                    ]
                                ]);
                            }
                        }
                    }
                    break;
                default:
                    if ($this->instansiSession) {
                        $query->where([
                            'OR' => [
                                $this->_alias . '.instansi_id' => $this->instansiSession->id,
                                $this->_alias . '.instansi_id IS' => null
                            ]
                        ]);
                    }
                    break;
            }
        }
    }

    public function beforeSave(Event $event, Entity $entity, ArrayObject $options)
    {
        if (
            $entity instanceof \App\Model\Entity\Peran ||
            $entity instanceof \App\Model\Entity\Pengguna
        ) {
            return;
        }

        if ($this->strictUpdate && !$entity->isNew()) {
            if ($this->instansiSession) { // If user has instansi
                if ($this->instansiSession->id != $entity->instansi_id) {
                    $entity->setError('instansi_id', __('Anda tidak berhak melakukan perubahan data ini'));
                    return false;
                }
            } else if (!is_null($entity->instansi_id)) { // if not, entity should be empty as well
                $entity->setError('instansi_id', __('Anda tidak berhak melakukan perubahan data ini'));
                return false;
            }
        }

        if ($this->filteredBeforeSave && $entity->isNew()) {
            if ($this->instansiSession && !$entity->instansi_id && $this->filteredBeforeSave) {
                $entity->instansi_id = $this->instansiSession->id;
            }

            if ($this->unitSession && !$entity->unit_id && $this->filteredBeforeSave) {
                $entity->unit_id = $this->unitSession->id;
            }
        }

        if ($entity->isNew()) {
            // Cek double identik data
            $alias = $this->getRegistryAlias();
            $tabel = $this->getTable();

            $con = ConnectionManager::get('default');
            $TblKolom = $con->execute("
                SELECT column_name
                FROM information_schema.columns
                WHERE table_schema = 'public'
                    AND table_name = '{$tabel}'"
            )->fetchAll('assoc');

            $dataTbl = TableRegistry::get($alias);
            $kolom = $dataTbl->find()->select();
            $entData = $entity->getDirty();

            foreach($TblKolom as $tk){
                if (!empty($entity[$tk['column_name']])) {
                    $kolom->where([$tk['column_name'] => $entity[$tk['column_name']]]);
                }
            }

            if ($kolom->count() > 0) {
                return false;
            }
        }

        return;
    }

    public function beforeDelete(Event $event, Entity $entity, ArrayObject $options) {
        if ($this->strictDelete) {
            if ($this->instansiSession) { // If user has instansi, they can only delete data from same instansi
                if ($this->instansiSession->id != $entity->instansi_id) {
                    $entity->setError('instansi_id', __('Anda tidak berhak menghapus data ini'));
                    $event->stopPropagation();
                }
            } else if (!is_null($entity->instansi_id)) {
                // if user doesn't have instansi, the instansi_id should be empty as well
                $entity->setError('instansi_id', __('Anda tidak berhak menghapus data ini'));
                $event->stopPropagation();
            }
        }
    }
}
