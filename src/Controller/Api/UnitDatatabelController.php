<?php
namespace App\Controller\Api;

use App\Model\Entity\UnitDatatabel;

/**
 * UnitDatatabel Controller
 *
 * @property \App\Model\Table\UnitDatatabelTable $UnitDatatabel
 */
class UnitDatatabelController extends ApiController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    /*public function index()
    {
        $this->paginate = [
            'contain' => ['Datatabel', 'Unit']
        ];
        $unitDatatabel = $this->paginate($this->UnitDatatabel);

        $this->set(compact('unitDatatabel'));
        $this->set('_serialize', ['unitDatatabel']);
    }*/

    /**
     * View method
     *
     * @param string|null $id Unit Datatabel id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    /*public function view($id = null)
    {
        $unitDatatabel = $this->UnitDatatabel->get($id, [
            'contain' => ['Datatabel', 'Unit']
        ]);

        $this->set('unitDatatabel', $unitDatatabel);
        $this->set('_serialize', ['unitDatatabel']);
    }*/

    /**
     * Add method
     *
     * @return \Cake\Http\Response|void Redirects on successful add, renders view otherwise.
     */
    /*public function add()
    {
        $unitDatatabel = $this->UnitDatatabel->newEntity();
        if ($this->request->is('post')) {
            $unitDatatabel = $this->UnitDatatabel->patchEntity($unitDatatabel, $this->request->data);
            if ($this->UnitDatatabel->save($unitDatatabel)) {
                $this->Flash->success(__('The unit datatabel has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The unit datatabel tidak berhasil disimpan. Silahkan coba kembali.'));
            }
        }
        $datatabels = $this->UnitDatatabel->Datatabels->find('list', ['limit' => 200]);
        $units = $this->UnitDatatabel->Units->find('list', ['limit' => 200]);
        $this->set(compact('unitDatatabel', 'datatabel', 'unit'));
        $this->set('_serialize', ['unitDatatabel']);
    }*/

    /**
     * Edit method
     *
     * @param string|null $id Unit Datatabel id.
     * @return \Cake\Http\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Http\Exception\NotFoundException When record not found.
     */
    /*public function edit($id = null)
    {
        $unitDatatabel = $this->UnitDatatabel->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $unitDatatabel = $this->UnitDatatabel->patchEntity($unitDatatabel, $this->request->data);
            if ($this->UnitDatatabel->save($unitDatatabel)) {
                $this->Flash->success(__('The unit datatabel has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The unit datatabel tidak berhasil disimpan. Silahkan coba kembali.'));
            }
        }
        $datatabels = $this->UnitDatatabel->Datatabels->find('list', ['limit' => 200]);
        $units = $this->UnitDatatabel->Units->find('list', ['limit' => 200]);
        $this->set(compact('unitDatatabel', 'datatabel', 'unit'));
        $this->set('_serialize', ['unitDatatabel']);
    }*/

    /**
     * Delete method
     *
     * @param string|null $id Unit Datatabel id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $unitDatatabel = $this->UnitDatatabel->get($id);
        if ($this->UnitDatatabel->delete($unitDatatabel)) {
            $this->Flash->success(__('The unit datatabel has been deleted.'));
        } else {
            $this->Flash->error(__('The unit datatabel could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
