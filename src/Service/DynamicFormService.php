<?php
namespace App\Service;

use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Entity;
use Cake\Http\Client;
use Cake\Utility\Inflector;
use Cake\Routing\Router;
use chillerlan\QRCode;
use App\Model\Table\CanvasElementTable;
use App\Model\Table\CanvasTabTable;

/**
 * Business Logic for Dynamic Form
 * Created by Indra
 * Date: 25/09/16
 * Time: 23:09
 */
class DynamicFormService extends AuthService
{
    const TIPE_FORM = 'form';
    const TIPE_TABEL = 'tabel';
    const TIPE_TAB = 'tab';
    const TIPE_TABEL_GRID = 'tabel-grid';
    const TIPE_TABEL_STATIK = 'tabel-statik';
    const TIPE_ACTION = 'action'; // Action Button
    const TIPE_FILTER = 'filter'; // Filter Tabel and Tabel Grid

    const TIPE_ELEMENT_TEXT = 'text';
    const TIPE_ELEMENT_NUMBER = 'number';
    const TIPE_ELEMENT_PASSWORD = 'password';
    const TIPE_ELEMENT_DATE = 'date';
    const TIPE_ELEMENT_EMAIL = 'email';
    const TIPE_ELEMENT_CHECKBOX = 'checkbox';
    const TIPE_ELEMENT_SELECT = 'select';
    const TIPE_ELEMENT_SELECT_WS = 'select-ws';
    const TIPE_ELEMENT_AUTOCOMPLETE = 'autocomplete';
    const TIPE_ELEMENT_NUMBERING = 'numbering';
    const TIPE_ELEMENT_LABEL = 'label';
    const TIPE_ELEMENT_BUTTON_SET = 'button-set';
    const TIPE_ELEMENT_BUTTON_ACTION = 'button-action';
    const TIPE_ELEMENT_FILE = 'file';
    const TIPE_ELEMENT_PHOTO = 'photo';
    const TIPE_ELEMENT_TEXTAREA = 'textarea';
    const TIPE_ELEMENT_HYPERLINK = 'hyperlink';
    const TIPE_ELEMENT_QRCODE = 'qrcode';
    const TIPE_ELEMENT_BARCODE = 'barcode';
	const TIPE_ELEMENT_EDITOR = 'editor';

    const TIPE_SAVE_INTERNAL = 'internal';
    const TIPE_SAVE_EKSTERNAL = 'eksternal';
    const TIPE_SAVE = 'save';
    const TIPE_AUTO_UPDATE = 'auto-update';
    const NEW_NUMBER_FLAG = '!N!';

    private static $formTypeFilter = [];
    private static $withRecord = false;
    private static $isReport = false;
    private static $queryStrings = [];
    private static $exceptionMessage = '';
    private static $hasPhoto = false;
    private static $hasSplittedSection = false;
    private static $hasButtonSet = false;
    private static $tempOptionsSelect = []; // hold json_encoded options to prevent options from WS to be called many times

    /**
     * Set to Filter Form Type to be retrieved
     * @param string $formType
     */
    public static function setFormTypeFilter($formType)
    {
        if (is_string($formType)) {
            self::$formTypeFilter[] = $formType;
        } elseif (is_array($formType)){
            $formTypes = [];
            foreach ($formType as $type) {
                $formTypes[] = trim($type);
            }
            self::$formTypeFilter = array_merge(self::$formTypeFilter, $formTypes);
        }
        return;
    }

    /**
     * Set to Whether to retrieve record or not
     * @param booelan $withRecord
     */
    public static function setWithRecord($withRecord)
    {
        if ($withRecord) {
            self::$withRecord = ($withRecord === 'F') ? false : true;
        }
        return;
    }

    /**
     * Set to Whether the data will be downloaded as report or not
     * @param booelan $isReport
     */
    public static function setIsReport($isReport)
    {
        if ($isReport) {
            self::$isReport = ($isReport === 'F') ? false : true;
        }
        return;
    }

    /**
     * Set query strings to be used as variables when SELECTING data
     *
     * @param [type] $queryStrings
     * @param boolean $addUserFilter
     * @return void
     */
    public static function setQueryStrings($queryStrings, $addUserFilter = true) {
        if (is_array($queryStrings) && !empty($queryStrings)) {

            foreach ($queryStrings as $key => $val) {
                // try to convert if there is any date
                $date = self::parseDate($val);

                // if date conversion success, replace the value
                if ($date) {
                    $queryStrings[$key] = $date->format(self::$dbDateFormat);
                }
            }

            self::$queryStrings = $queryStrings;
        }

        // Get User related data
        $instansiId = (self::$instansi && self::$instansi->id) ? self::$instansi->id : null;

        // Add Instansi related Query String to be used as variables when doing query
        if (!isset(self::$queryStrings['instansi_id'])) {
            self::$queryStrings['instansi_id'] = $instansiId;
        }

        if ($addUserFilter) {
            $userId = (self::$user && self::$user->id) ? self::$user->id : null;
            $pegawaiId = (self::$user && self::$user->pegawai_id) ? self::$user->pegawai_id : null;
            $peranId = (self::$user && self::$user->peran_id) ? self::$user->peran_id : null;
            $unitId = (self::$unit && self::$unit->id) ? self::$unit->id : null;

            if (!isset(self::$queryStrings['user_id'])) {
                self::$queryStrings['user_id'] = $userId;
            }

            if (!isset(self::$queryStrings['peran_id'])) {
                self::$queryStrings['peran_id'] = $peranId;
            }

            if (!isset(self::$queryStrings['pegawai_id'])) {
                self::$queryStrings['pegawai_id'] = $pegawaiId;
            }

            if (!isset(self::$queryStrings['unit_id'])) {
                self::$queryStrings['unit_id'] = $unitId;
            }
        }

        return;
    }

    public static function getExceptionMessage() {
        return self::$exceptionMessage;
    }

    public static function parseDate($strDate)
    {
        if (!is_string($strDate)) {
            return null;
        }

        $newDate = \DateTime::createFromFormat(self::$dateFormat, $strDate);
        if ($newDate instanceof \DateTime) {
            return $newDate;
        }

        return null;
    }

    /**
     * Get Form Structure only
     * @param $id
     * @return array
     */
    public static function getFormattedForm($id)
    {
        $formattedForm = [];

        $canvasFilter = [
            'Canvas.del' => 0
        ];
        if (!empty(self::$formTypeFilter)) {
            $canvasFilter['Canvas.form_type IN'] = self::$formTypeFilter;
        }

        $formTable = TableRegistry::get('Form');
        $form = $formTable->get($id, [
            'contain' => [
                'Unit' => ['fields' => ['nama']],
                'Canvas' => [
                    'fields' => [
                        'id', 'tab_index', 'form_type', 'form_name', 'datatabel_id', 'initial_web_service',
                        'form_id', 'del', 'template_data_id', 'no_urut'
                    ],
                    'conditions' => [$canvasFilter],
                    'sort' => ['Canvas.no_urut' => 'ASC', 'Canvas.id' => 'ASC']
                ]
            ],
            'fields' => [
                'id', 'nama_form', 'key_field', 'unit_id', 'otomatis_update', 'target_simpan', 'service_eksternal_id', 'target_path'
            ]
        ]);

        $formattedForm['id'] = $form->id;
        $formattedForm['nama_form'] = $form->nama_form;
        $formattedForm['key_field'] = $form->key_field;
        $formattedForm['otomatis_update'] = $form->otomatis_update;
        $formattedForm['target_simpan'] = $form->target_simpan;
        $formattedForm['service_eksternal_id'] = $form->service_eksternal_id;
        $formattedForm['target_path'] = $form->target_path;
        $formattedForm['unit_id'] = $form->unit_id;

        $elementTable = TableRegistry::get('CanvasElement');
        $tabTable = TableRegistry::get('CanvasTab');

        foreach ($form->canvas as $canvasIndex => $canvas) {
            $formattedForm['canvas'][$canvasIndex] = [
                'id' => $canvas->id,
                'del' => ($canvas->del == 1) ? true : false,
                'tabIdx' => $canvas->tab_index,
                'ftype' => $canvas->form_type,
                'fName' => $canvas->form_name,
                'datatabel' => $canvas->datatabel_id,
                'template_data_id' => $canvas->template_data_id,
                'initial_ws' => $canvas->initial_web_service,
                'no_urut' => $canvas->no_urut
            ];

            switch ($canvas->form_type) {
                case self::TIPE_TAB:
                    $formattedForm['canvas'][$canvasIndex]['ftab'] = self::getFormattedCanvas($canvas, $elementTable, $tabTable);
                    break;
                default:
                    $formattedForm['canvas'][$canvasIndex]['fields'][0] = self::getFormattedCanvas($canvas, $elementTable, $tabTable);
                    break;
            }

            $formattedForm['canvas'][$canvasIndex]['has_photo'] = self::$hasPhoto; // Need to be called after getFormattedCanvas
            $formattedForm['canvas'][$canvasIndex]['has_splitted_section'] = self::$hasSplittedSection; // Need to be called after getFormattedCanvas
            $formattedForm['canvas'][$canvasIndex]['has_button_set'] = self::$hasButtonSet; // Need to be called after getFormattedCanvas
        }

        return $formattedForm;
    }

    /**
     * Get Form Structure with stored data
     * @param $id
     * @param $keyId
     * @return array
     */
    public static function getFormattedFormWithRecord($id, $keyId = null, $userId)
    {
        $formattedForm = [];
        $hasFilter = false;
        $hasTabelGrid = false;
        $canvasTemplateDataId = null;
        $userUnitIds = self::getUserUnit($userId);

        $formTable = TableRegistry::get('Form');
        $elementTable = TableRegistry::get('CanvasElement');
        $tabTable = TableRegistry::get('CanvasTab');

        $canvasFilter = [
            'Canvas.del' => 0
        ];
        if (!empty(self::$formTypeFilter)) {
            $canvasFilter['Canvas.form_type IN'] = self::$formTypeFilter;
        }

        $form = $formTable->get($id, [
            'contain' => [
                'Unit' => ['fields' => ['nama']],
                'Canvas' => [
                    'fields' => [
                        'id', 'tab_index', 'form_type', 'form_name', 'datatabel_id', 
                        'template_data_id', 'form_id', 'del', 'no_urut'
                    ],
                    'conditions' => [$canvasFilter],
                    'sort' => ['Canvas.no_urut' => 'ASC', 'Canvas.id' => 'ASC']
                ],
            ],
            'fields' => [
                'id', 'nama_form', 'unit_id', 'key_field', 'otomatis_update'
            ]
        ]);

        // Check if form has Filter
        if ($form->canvas) {
            foreach ($form->canvas as $canvas) {
                switch ($canvas->form_type) {
                    case self::TIPE_FILTER:
                        if ($canvas->template_data_id) {
                            $canvasTemplateDataId = $canvas->template_data_id;
                        }

                        // Prevent overriding hasFilter if already set to TRUE before
                        if (!$hasFilter) { $hasFilter = true; }
                        break;
                    case self::TIPE_TABEL_GRID:
                        if (!self::$isReport) {
                            self::$withRecord = false;
                        }
                        
                        if (!$hasTabelGrid) { $hasTabelGrid = true; }
                        break;
                }
            }
        }

        // If Form has Tabel Grid
        if ($form->canvas) {
            foreach ($form->canvas as $canvas) {
                if ($canvas->form_type == self::TIPE_TABEL_GRID) {
                    if (!self::$isReport) {
                        self::$withRecord = false;
                    }
                    $hasTabelGrid = true;
                    break;
                }
            }
        }

        $formattedForm['id'] = $form->id;
        $formattedForm['nama_form'] = $form->nama_form;
        $formattedForm['unit_id'] = $form->unit_id;
        $formattedForm['otomatis_update'] = $form->otomatis_update;
        $formattedForm['has_filter'] = $hasFilter;
        $formattedForm['has_tabel_grid'] = $hasTabelGrid;
        $formattedForm['canvas_template_data_id'] = $canvasTemplateDataId;

        // Get All Form Records
        $formDatatabels = self::$withRecord ? self::getFormDatatabel($id, $keyId) : [];

        foreach ($form->canvas as $canvasIndex => $canvas) {
            $formattedForm['canvas'][$canvasIndex] = [
                'id' => $canvas->id,
                'del' => ($canvas->del == 1) ? true : false,
                'tabIdx' => $canvas->tab_index,
                'ftype' => $canvas->form_type,
                'fName' => $canvas->form_name,
                'datatabel' => $canvas->datatabel_id,
                'template_data_id' => $canvas->template_data_id,
                'no_urut' => $canvas->no_urut
            ];

            $isAllowed = self::isAllowedUnit($userUnitIds, $canvas->datatabel_id);

            switch ($canvas->form_type) {
                case self::TIPE_TAB:
                    $formattedForm['canvas'][$canvasIndex]['ftab'] = self::getFormattedCanvas(
                        $canvas,
                        $elementTable,
                        $tabTable,
                        null,
                        $isAllowed
                    );
                    break;
                default:
                    if (isset($formDatatabels[$canvas->id]['records']) && !empty($formDatatabels[$canvas->id]['records'])) {
                        foreach ($formDatatabels[$canvas->id]['records'] as $recordIndex => $record) {
                            $formattedForm['canvas'][$canvasIndex]['fields'][$recordIndex] = self::getFormattedCanvas(
                                $canvas,
                                $elementTable,
                                $tabTable,
                                $record,
                                $isAllowed
                            );
                        }
                    } else {
                        $formattedForm['canvas'][$canvasIndex]['fields'][0] = self::getFormattedCanvas(
                            $canvas,
                            $elementTable,
                            $tabTable,
                            null,
                            $isAllowed
                        );
                    }
                    break;
            }

            // Need to be called after getFormattedCanvas
            $formattedForm['canvas'][$canvasIndex]['has_photo'] = self::$hasPhoto;
            $formattedForm['canvas'][$canvasIndex]['has_splitted_section'] = self::$hasSplittedSection;
            $formattedForm['canvas'][$canvasIndex]['has_button_set'] = self::$hasButtonSet;
        }

        return $formattedForm;
    }

    /**
     * Function to format canvas and fill data if provided
     * @param \App\Model\Entity\Canvas $canvas
     * @param \App\Model\Table\CanvasElementTable $elementTable Canvas Element Table
     * @param \App\Model\Table\TabTable $tabTable
     * @param $record
     * @return array
     */
    public static function getFormattedCanvas(Entity $canvas, CanvasElementTable $elementTable, CanvasTabTable $tabTable, $record = null, $enabled = true)
    {
        $canvasFields = [];
        self::$hasPhoto = false;
        self::$hasSplittedSection = false;
        self::$hasButtonSet = false;

        // If record is array, convert it to object
        if (is_array($record)) {
            $record = (object) $record;
        }

        switch ($canvas->form_type) {
            case self::TIPE_TAB:
                // Get Element
                $tabs = $tabTable
                    ->find('all', [
                        'fields' => [
                            'id', 'label', 'idx'
                        ],
                        'conditions' => [
                            'canvas_id' => $canvas->id
                        ],
                        'order' => [
                            'id' => 'ASC'
                        ]
                    ])
                    ->toArray();

                if ($tabs) {
                    foreach ($tabs as $tab) {
                        $canvasFields[] = [
                            'id' => $tab->id,
                            'label' => $tab->label,
                            'idx' => $tab->idx,
                            'del' => false
                        ];
                    }
                }
                break;

            default:
                $canvasFields['id'] = (is_object($record) && (isset($record->id))) ? $record->id : null;
                $canvasFields['del'] = (is_object($record) && (isset($record->del)) && $record->del == 1)
                    ? true : false;
                $canvasFields['disabled'] = !$enabled;

                // Get Element
                $elements = $elementTable
                    ->find('all', [
                        'fields' => [
                            'id', 'label', 'type', 'del', 'data_kolom_id',
                            'required','kelompok_data_id', 'penomoran_id',
                            'tombol_aksi', 'tombol_tautan', 'variable_name',
                            'target_simpan', 'service_eksternal_id',
                            'target_path', 'tautan', 'no_urut'
                        ],
                        'contain' => [
                            'DataKolom' => [
                                'fields' => ['DataKolom.data_kolom', 'DataKolom.tipe_kolom']
                            ],
                            'KelompokData' => [
                                'fields' => [
                                    'KelompokData.id', 'KelompokData.sql', 'KelompokData.combogrid_fields',
                                    'KelompokData.combogrid_value_col', 'KelompokData.combogrid_label_col'
                                ]
                            ]
                        ],
                        'conditions' => [
                            'CanvasElement.canvas_id' => $canvas->id,
                            'CanvasElement.del' => 0
                        ],
                        'order' => [
                            'CanvasElement.no_urut' => 'ASC',
                            'CanvasElement.id' => 'ASC'
                        ]
                    ])
                    ->contain(['ElementOption' => ['fields' => ['id', 'code', 'name', 'element_id']]])
                    ->toArray();

                if ($elements) {
                    if ($record) { // If record exists, decode the data_labels
                        if (isset($record->data_labels)) {
                            $record->data_labels = json_decode($record->data_labels);
                        }
                    }

                    // BEGIN - Loop each element and map the data
                    foreach ($elements as $elementIndex => $element) {

                        if ($element->data_kolom) {
                            $fieldName = $element->data_kolom->data_kolom;

                            // if field name is null, try to build dummy field name from label
                            if (!$fieldName) {
                                $fieldName = Inflector::underscore(Inflector::camelize($element->label));
                            }

                            $data = (is_object($record) && (isset($record->$fieldName))) ? $record->$fieldName : null;
                            $dataLabel = (is_object($record) && (isset($record->data_labels->$fieldName))) ? $record->data_labels->$fieldName : null;

                            // if field type is date, format it
                            if ($element->data_kolom->tipe_kolom == 'date') {
                                if (
                                    $data &&
                                    !$data instanceOf \Cake\I18n\FrozenDate
                                ) {
                                    $data = date(self::$dateFormat, strtotime($data));
                                }
                            }

                            $canvasFields['field'][$elementIndex] = [
                                'id' => $element->id,
                                'del' => ($element->del == 1) ? true : false,
                                'required' => ($element->required == 1) ? true : false,
                                'label' => $element->label,
                                'data_kolom' => $fieldName,
                                'data_kolom_id' => $element->data_kolom_id,
                                'type' => $element->type,
                                'data' => $data,
                                'data_label' => $dataLabel,
                                'kelompok_data_id' => ($element->kelompok_data_id) ?: null,
                                'penomoran_id' => ($element->penomoran_id) ?: null,
                                'tombol_aksi' => ($element->tombol_aksi) ?: null,
                                'tombol_tautan' => ($element->tombol_tautan) ?: null,
                                'variable_name' => ($element->variable_name) ?: null,
                                'disabled' => !$enabled,
                                'tautan' => ($element->tautan) ?: null,
                                'no_urut' => $element->no_urut
                            ];

                            // check if form need to be splitted
                            if ($canvas->form_type === self::TIPE_FORM && !self::$hasSplittedSection) {
                                if ($element->no_urut % 2 == 0) {
                                    self::$hasSplittedSection = true;
                                }
                            }

                            //parse element options
                            switch ($element->type) {
                                case self::TIPE_ELEMENT_SELECT:
                                    if (!empty($element->element_option)) {
                                        foreach ($element->element_option as $option) {
                                            $canvasFields['field'][$elementIndex]['options'][] = [
                                                'row_id' => $option->id,
                                                'id' => $option->code,
                                                'name' => $option->name,
                                            ];
                                        }
                                    }
                                    break;

                                case self::TIPE_ELEMENT_SELECT_WS:
                                    // Request to Web Service to get the options
                                    $kelompokDataTable = TableRegistry::get('KelompokData');
                                    $kelompokData = $kelompokDataTable->find()
                                        ->select(['id', 'template_data_id', 'label_kelompok', 'jenis_sumber', 'sql'])
                                        ->where(['id' => $element->kelompok_data_id])
                                        ->first();

                                    if ($kelompokData) {
                                        switch (strtoupper($kelompokData->jenis_sumber)) {
                                            case 'SQL';
                                                try { // Try executing SQL Query
                                                    // Prevent called same WS many times on list typed form
                                                    $cacheKey = $canvas->id . '_' . $element->id . '_' . $element->kelompok_data_id;

                                                    if (array_key_exists($cacheKey, static::$tempOptionsSelect)) {
                                                        $canvasFields['field'][$elementIndex]['options'] = static::$tempOptionsSelect[$cacheKey];
                                                    } else {
                                                        $sqlQuery = '';
                                                        $q = '%';
                                                        $options = [];
                                                        $connection = ConnectionManager::get('default');

                                                        // Get SQL Query and Eval SQL String
                                                        eval("\$sqlQuery = \"$kelompokData->sql\";");

                                                        // BEGIN - Query SELECT Data
                                                        $sqlQueryData = "$sqlQuery LIMIT 1000";
                                                        $results = $connection->query($sqlQueryData)->fetchAll('num');

                                                        foreach ($results as $result) {
                                                            $options[] = [
                                                                'row_id' => null,
                                                                'id' => $result[0],
                                                                'name' => $result[1] ?: $result[0]
                                                            ];
                                                        }

                                                        $canvasFields['field'][$elementIndex]['options'] = json_encode($options);
                                                        static::$tempOptionsSelect[$cacheKey] = $canvasFields['field'][$elementIndex]['options'];

                                                        unset($options);
                                                        unset($sqlQueryData);
                                                        unset($results);
                                                        // END - Query SELECT Data
                                                    }
                                                } catch (Exception $e) {
                                                    $canvasFields['field'][$elementIndex]['options'] = [];
                                                }
                                                break;
                                            default:
                                                break;
                                        }
                                    }

                                    break;

                                case self::TIPE_ELEMENT_AUTOCOMPLETE:
                                    if ($element->kelompok_data) {
                                        $combogridConfig = $element->kelompok_data->getCombogridConfig();
                                        if ($combogridConfig) {
                                            $canvasFields['field'][$elementIndex] = array_merge($canvasFields['field'][$elementIndex], $combogridConfig);
                                        }
                                    }
                                    break;

                                case self::TIPE_ELEMENT_NUMBERING:
                                    if (!$canvasFields['field'][$elementIndex]['data']) { // if data is not available
                                        $instansiId = (self::$instansi) ? self::$instansi->id : null;
                                        $unitId = (self::$unit) ? self::$unit->id : null;

                                        // generate new data but don't update the last number
                                        $canvasFields['field'][$elementIndex]['data'] =
                                            NumberingService::getFormattedNumber($element->penomoran_id, $instansiId, $unitId) . self::NEW_NUMBER_FLAG;
                                    }
                                    break;

                                case self::TIPE_ELEMENT_PHOTO:
                                    self::$hasPhoto = true;
                                    $data = $canvasFields['field'][$elementIndex]['data'];
                                    if ($data) {
                                        $canvasFields['field'][$elementIndex]['data'] = $data;
                                        $canvasFields['field'][$elementIndex]['file_url'] =
                                            Router::url("/webroot/files/upload/$data", true);
                                    }

                                case self::TIPE_ELEMENT_FILE:
                                    // Always set the upload status to idle
                                    $canvasFields['field'][$elementIndex]['upload_status'] = 'idle';
                                    break;

                                case self::TIPE_ELEMENT_CHECKBOX:
                                    $canvasFields['field'][$elementIndex]['data'] = intval($canvasFields['field'][$elementIndex]['data']);
                                    break;

                                case self::TIPE_ELEMENT_QRCODE:
                                    $data = $canvasFields['field'][$elementIndex]['data'];

                                    if ($data) {
                                        $options = new QRCode\QROptions([
                                            'version'    => 5,
                                            'outputType' => QRCode\QRCode::OUTPUT_IMAGE_PNG,
                                            'eccLevel'   => QRCode\QRCode::ECC_L,
                                        ]);

                                        // invoke a fresh QRCode instance
                                        $qrcode = new QRCode\QRCode($options);

                                        // and dump the output
                                        $canvasFields['field'][$elementIndex]['data'] = $qrcode->render($data);
                                        unset($qrcode);
                                    }
                                    break;

                                case self::TIPE_ELEMENT_BARCODE:
                                    $data = $canvasFields['field'][$elementIndex]['data'];

                                    if ($data) {
                                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                        $canvasFields['field'][$elementIndex]['data'] = base64_encode(
                                            $generator->getBarcode(
                                                $data,
                                                $generator::TYPE_CODE_128,
                                                2,
                                                60
                                            )
                                        );
                                        unset($generator);
                                    }
                                    break;

                                default:
                                    break;
                            }
                        } else {
                            // For Buttons which do not have Data Kolom
                            $canvasFields['field'][$elementIndex] = [
                                'id' => $element->id,
                                'del' => ($element->del == 1) ? true : false,
                                'required' => ($element->required == 1) ? true : false,
                                'label' => $element->label,
                                'data_kolom' => null,
                                'data_kolom_id' => $element->data_kolom_id,
                                'type' => $element->type,
                                'data' => null,
                                'data_label' => null,
                                'kelompok_data_id' => ($element->kelompok_data_id) ?: null,
                                'penomoran_id' => ($element->penomoran_id) ?: null,
                                'tombol_aksi' => ($element->tombol_aksi) ?: null,
                                'tombol_tautan' => ($element->tombol_tautan) ?: null,
                                'variable_name' => ($element->variable_name) ?: null,
                                'disabled' => !$enabled,
                                'target_simpan' => ($element->target_simpan) ?: null,
                                'service_eksternal_id' => ($element->service_eksternal_id) ?: null,
                                'target_path' => ($element->target_path) ?: null,
                                'tautan' => ($element->tautan) ?: null,
                                'no_urut' => $element->no_urut
                            ];

                            if ($element->type === self::TIPE_ELEMENT_BUTTON_SET && !self::$hasButtonSet) {
                                self::$hasButtonSet = true;
                            }
                        }
                    }
                    // END - Loop each element and map the data

                }

                break;
        }

        return $canvasFields;
    }

    /**
     * Get record  from a custom table
     * @param $formId
     * @param $keyId
     * @return array
     */
    public static function getFormDatatabel($formId, $keyId)
    {
        $formData = [];

        $formTable = TableRegistry::get('Form');
        $form = $formTable->find('all', [
            'fields' => ['Form.id', 'Form.key_field'],
            'contain' => [
                'Canvas' => [
                    'fields' => ['id', 'form_id', 'datatabel_id', 'form_type', 'initial_web_service'],
                    'conditions' => ['Canvas.del' => 0]
                ],
                'Canvas.Datatabel' => [
                    'fields' => ['id', 'nama_datatabel', 'use_mapper', 'is_custom']
                ]
            ],
            'conditions' => [
                'Form.id' => $formId
            ]
        ])->first();

        if ($form) {

            foreach ($form->canvas as $canvasIndex => $canvas) {

                // If web service defined
                if ($canvas->initial_web_service && !empty($canvas->initial_web_service)) {
                    // If it's not table-grid, try to load the data first
                    if ($canvas->form_type != self::TIPE_TABEL_GRID) {
                        $records = self::getCanvasData($canvas->id, $keyId);

                        // If data is found, use it
                        if (!empty($records)) {
                            $formData[$canvas->id]['records'] = $records;
                            continue;
                        }
                    }

                    if (strpos($canvas->initial_web_service, "http") === false) {
                        // Add '/' if initial web service doesn't start with '/'
                        if (substr($canvas->initial_web_service, 0, 1) != '/') {
                            $canvas->initial_web_service = '/' . $canvas->initial_web_service;
                        }
                        $canvas->initial_web_service = Router::url('/api', true) . $canvas->initial_web_service;
                    }

                    // Do request to web service
                    $http = new Client();
                    $response = $http->get($canvas->initial_web_service, self::$queryStrings, [
                        'auth' => ['username' => 'myusername', 'password' => 'mypassword']
                    ]);//TODO change dummy username and password
                    $responseJson = $response->json;

                    if (isset($responseJson['data']['data']) && !empty($responseJson['data']['data'])) {
                        $initialData = $responseJson['data']['data'];
                        $formData[$canvas->id]['records'] = $initialData;
                        continue;
                    }

                    $dataKey = null;

                    if (isset($responseJson['data']['data']) && !empty($responseJson['data']['data'])) {
                        $dataKey = 'data';
                    } elseif (isset($responseJson['data']['items']) && !empty($responseJson['data']['items'])) {
                        $dataKey = 'items';
                    } elseif (isset($responseJson['data']['item']) && !empty($responseJson['data']['item'])) {
                        $dataKey = 'item';
                    }

                    if ($dataKey) {
                        $initialData = $responseJson['data'][$dataKey];
                        $formData[$canvas->id]['records'] = $initialData;
                        continue;
                    }
                } else { // If no web service specified, get data from table
                    $records = self::getCanvasData($canvas->id, $keyId);
                    $formData[$canvas->id]['records'] = $records;
                }
            }
        }

        return $formData;
    }

    /**
     * Get canvas data for tabel-grid
     * @param integer $canvasId
     * @param string $q
     * @return array
     */
    public static function getPaginatedData($canvasId, $q = '', $page = null, $limit = null)
    {
        $gridData = [];

        $canvasTable = TableRegistry::get('Canvas');
        $canvas = $canvasTable->find('all', [
            'fields' => ['id', 'form_type', 'initial_web_service'],
            'conditions' => [
                'Canvas.id' => $canvasId
            ]
        ])->first();

        if ($canvas && $canvas->form_type == self::TIPE_TABEL_GRID) {
            // Check Initial Web Service first
            if ($canvas->initial_web_service && !empty($canvas->initial_web_service)) {

                if (strpos($canvas->initial_web_service, "http") === false) {
                    // Add '/' if initial web service doesn't start with '/'
                    if (substr($canvas->initial_web_service, 0, 1) != '/') {
                        $canvas->initial_web_service = '/' . $canvas->initial_web_service;
                    }
                    $canvas->initial_web_service = Router::url('/api', true) . $canvas->initial_web_service;
                }

                // Do request to web service
                $http = new Client();
                $queryStrings = array_merge(self::$queryStrings, [
                    'page' => $page,
                    'limit' => $limit
                ]);

                $response = $http->get($canvas->initial_web_service, $queryStrings, [
                    'auth' => ['username' => 'myusername', 'password' => 'mypassword']
                ]);//TODO change dummy username and password

                $responseJson = $response->json;

                if (isset($responseJson['data']) && !empty($responseJson['data'])) {
                    $gridData = $responseJson['data'];
                }
            } else {
                // Get from table
                $gridData = self::getCanvasData($canvas->id, null, $page, $limit);
            }
        }

        return $gridData;
    }

    public static function prepareSaveFormSetup($requestData)
    {
        $data = [];
        $data['nama_form'] = $requestData['nama_form'];
        $data['key_field'] = $requestData['key_field'];
        $data['otomatis_update'] = $requestData['otomatis_update'];
        $data['target_simpan'] = $requestData['target_simpan'];
        $data['service_eksternal_id'] = $requestData['service_eksternal_id'];
        $data['target_path'] = $requestData['target_path'];
        $data['unit_id'] = null;

        foreach ($requestData['canvas'] as $index => $canvasData) {
            // If canvas is deleted, skip it
            // because the action to delete canvas already done in form/deletecanvas endpoint via xhr
            if (isset($canvasData['del']) && $canvasData['del'] == true) {
                continue;
            }

            $data['canvas'][$index] = [
                'tab_index' => $canvasData['tabIdx'],
                'form_type' => $canvasData['ftype'],
                'form_name' => isset($canvasData['fName']) ? $canvasData['fName'] : null,
                'datatabel_id' => isset($canvasData['datatabel']) ? $canvasData['datatabel'] : null,
                'template_data_id' => isset($canvasData['template_data_id']) ? $canvasData['template_data_id']: null,
                'initial_web_service' => $canvasData['initial_ws'] ?: null,
                'no_urut' => isset($canvasData['no_urut']) ? intval($canvasData['no_urut']) : null
            ];

            if (isset($canvasData['id']) && !empty($canvasData['id'])) {
                $data['canvas'][$index]['id'] = (int)$canvasData['id'];
            }

            switch ($canvasData['ftype']) {
                case self::TIPE_ACTION:
                case self::TIPE_FILTER:
                case self::TIPE_TABEL:
                case self::TIPE_FORM:
                case self::TIPE_TABEL_GRID:
                case self::TIPE_TABEL_STATIK:
                    if (isset($canvasData['fields'])) {

                        foreach ($canvasData['fields'] as $fields) {
                            foreach ($fields['field'] as $fieldIndex => $field) {

                                if ($field['label'] && $field['type']) {
                                    $data['canvas'][$index]['canvas_element'][$fieldIndex] = [
                                        'label' => $field['label'],
                                        'required' => ($field['required'] == true) ? 1 : 0,
                                        'type' => $field['type'],
                                        'data_kolom_id' => (!empty($field['data_kolom_id'])) ? (int) $field['data_kolom_id'] : null,
                                        'del' => ($field['del'] == true) ? 1 : 0,
                                        'variable_name' => (!empty($field['variable_name'])) ? (string) $field['variable_name'] : null,
                                        'tautan' => (!empty($field['tautan'])) ? (string) $field['tautan'] : null,
                                        'no_urut' => (isset($field['no_urut'])) ? $field['no_urut'] : 1
                                    ];

                                    // If Element ID exists, use that record id to update
                                    if (isset($field['id']) && !empty($field['id'])) {
                                        $data['canvas'][$index]['canvas_element'][$fieldIndex]['id'] = (int)$field['id'];
                                    }
                                }

                                switch ($field['type']) {
                                    case self::TIPE_ELEMENT_SELECT:
                                        if (!empty($field['options'])) {
                                            // Save option
                                            $options = $field['options'];
                                            foreach ($options as $optionIndex => $option) {
                                                $data['canvas'][$index]['canvas_element'][$fieldIndex]['element_option'][$optionIndex] = [
                                                    'code' => $option['id'],
                                                    'name' => $option['name'],
                                                ];
                                                if (isset($option['row_id']) && !empty($option['row_id'])) {
                                                    $data['canvas'][$index]['canvas_element'][$fieldIndex]['element_option'][$optionIndex]['id'] = (int)$option['row_id'];
                                                }
                                            }
                                        }
                                        break;
                                    case self::TIPE_ELEMENT_NUMBERING:
                                        if (!empty($field['penomoran_id'])) {
                                            $data['canvas'][$index]['canvas_element'][$fieldIndex]['penomoran_id'] = $field['penomoran_id'];
                                        }
                                        break;
                                    case self::TIPE_ELEMENT_AUTOCOMPLETE:
                                    case self::TIPE_ELEMENT_SELECT_WS:
                                        if (!empty($field['kelompok_data_id'])) {
                                            $data['canvas'][$index]['canvas_element'][$fieldIndex]['kelompok_data_id'] = $field['kelompok_data_id'];
                                        }
                                        break;
                                    case self::TIPE_ELEMENT_BUTTON_ACTION:
                                    case self::TIPE_ELEMENT_BUTTON_SET:
                                        if (!empty($field['tombol_aksi'])) {
                                            $data['canvas'][$index]['canvas_element'][$fieldIndex]['tombol_aksi'] = $field['tombol_aksi'];
                                        }

                                        if (!empty($field['tombol_tautan'])) {
                                            $data['canvas'][$index]['canvas_element'][$fieldIndex]['tombol_tautan'] = $field['tombol_tautan'];
                                        }

                                        if (!empty($field['target_simpan'])) {
                                            $data['canvas'][$index]['canvas_element'][$fieldIndex]['target_simpan'] = $field['target_simpan'];
                                        }

                                        if (!empty($field['service_eksternal_id'])) {
                                            $data['canvas'][$index]['canvas_element'][$fieldIndex]['service_eksternal_id'] = $field['service_eksternal_id'];
                                        }

                                        if (!empty($field['target_path'])) {
                                            $data['canvas'][$index]['canvas_element'][$fieldIndex]['target_path'] = $field['target_path'];
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                    }
                    break;
                case self::TIPE_TAB:
                    if (isset($canvasData['ftab']) && !empty($canvasData['ftab'])) {
                        foreach ($canvasData['ftab'] as $tabIndex => $tab) {
                            if (isset($tab['del']) && $tab['del']) {
                                continue;
                            }

                            $data['canvas'][$index]['canvas_tab'][$tabIndex] = [
                                'idx' => $tab['idx'],
                                'label' => $tab['label']
                            ];
                            if (isset($tab['id']) && !empty($tab['id'])) {
                                $data['canvas'][$index]['canvas_tab'][$tabIndex]['id'] = (int)$tab['id'];
                            }
                        }
                    }
                    break;
            }
        }

        return $data;
    }

    /**
     * Prepare to save data from dynamic form
     * @param array $requestData
     * @param string $keyField
     * @param int $keyId
     * @param int $formId
     * 
     * @return array
     */
    public static function prepareSaveFormData($requestData, $keyField, $keyId = null, $formId = null)
    {
        $saveData = [];
        $buttonElement = null;
        $form = null;

        // Default Save Action
        $saveAction = new \stdClass();
        $saveAction->target_simpan = self::TIPE_SAVE_INTERNAL;

        $datatabelTable = TableRegistry::get('Datatabel');
        $dataKolomTable = TableRegistry::get('DataKolom');

        $instansiId = (self::$instansi) ? self::$instansi->id : null;
        $unitId = (self::$unit) ? self::$unit->id : null;

        // Get Button Element by ID
        if (isset($requestData['button_id'])) {

            // came from Auto Update Data Feature
            if ($requestData['button_id'] == -1) {
                $form = $dataKolomTable->CanvasElement->Canvas->Form->find('all', [
                    'fields' => [
                        'Form.otomatis_update', 'Form.target_simpan', 'Form.service_eksternal_id', 'Form.target_path'
                    ],
                    'contain' => [
                        'ServiceEksternal' => [
                            'fields' => [
                                'id', 'base_url', 'tipe_otentikasi', 'username', 'password'
                            ]
                        ]
                    ],
                    'conditions' => [
                        'Form.id' => $formId
                    ]
                ])->first();

                if ($form && $form->otomatis_update == 1) {
                    $saveAction->target_simpan = $form->target_simpan;
                    $saveAction->service_eksternal_id = $form->service_eksternal_id;
                    $saveAction->target_path = $form->target_path;
                    $saveAction->service_eksternal = $form->service_eksternal;
                }
            } else { // came from Save Button
                $buttonElement = $dataKolomTable->CanvasElement->find('all', [
                    'fields' => [
                        'CanvasElement.target_simpan', 'CanvasElement.service_eksternal_id', 'CanvasElement.target_path'
                    ],
                    'contain' => [
                        'ServiceEksternal' => [
                            'fields' => [
                                'id', 'base_url', 'tipe_otentikasi', 'username', 'password'
                            ]
                        ]
                    ],
                    'conditions' => [
                        'CanvasElement.id' => $requestData['button_id'],
                        'CanvasElement.type' => self::TIPE_ELEMENT_BUTTON_ACTION,
                        'CanvasElement.del' => 0
                    ]
                ])->first();

                if ($buttonElement) {
                    $saveAction->target_simpan = $buttonElement->target_simpan;
                    $saveAction->service_eksternal_id = $buttonElement->service_eksternal_id;
                    $saveAction->target_path = $buttonElement->target_path;
                    $saveAction->service_eksternal = $buttonElement->service_eksternal;
                }
            }
        }

        foreach ($requestData['canvas'] as $index => $canvasData) {

            if (!empty($canvasData['datatabel'])) {
                $i = 0;
                $datatabelId = $canvasData['datatabel'];

                // If same datatabel has been parsed before
                if (array_key_exists($datatabelId, $saveData)) {
	                $numPreviousData = count($saveData[$datatabelId]);
	                $i = ($numPreviousData > 0) ? ($numPreviousData - 1) : 0;
	                unset($numPreviousData);
                }

                // Get Datatabel
                $datatabelData = $datatabelTable
                    ->find('all', [
                        'fields' => [
                            'nama_datatabel', 'label', 'use_mapper'
                        ],
                        'conditions' => [
                            'id' => $datatabelId
                        ],
                        'order' => [
                            'id' => 'ASC'
                        ]
                    ])->first();

                if (!empty($datatabelData)) {
                    $saveData[$datatabelId]['datatabel_id'] = $datatabelId;
                    $saveData[$datatabelId]['nama_datatabel'] = $datatabelData['nama_datatabel'];
                    $saveData[$datatabelId]['use_mapper'] = $datatabelData['use_mapper'];

                    switch ($canvasData['ftype']) {
                        case self::TIPE_FORM:
                        case self::TIPE_TABEL:
                        case self::TIPE_TABEL_GRID:
                        case self::TIPE_TABEL_STATIK:

                            if (isset($canvasData['fields'])) {
                                // BEGIN - Looping Each Record
                                foreach ($canvasData['fields'] as $recordIndex => $fields) {
                                    $numNotEmpty = 0;
                                    $dataLabels = [];

                                    // BEGIN - Looping Each Field
                                    foreach ($fields['field'] as $field) {
                                        if (
                                            !in_array($field['type'], [
                                                self::TIPE_ELEMENT_LABEL,
                                                self::TIPE_ELEMENT_BARCODE,
                                                self::TIPE_ELEMENT_QRCODE
                                            ])
                                        ) {
                                            // If new data already deleted, no need to keep it
                                            if ((is_null($fields['id']) || empty($fields['id'])) && $fields['del']) {
                                                break;
                                            }

                                            $field['data'] = trim($field['data']);

                                            // If Datatabel ID exists, use that record id to update
                                            if (isset($fields['id']) && !empty($fields['id'])) {
                                                $saveData[$datatabelId]['records'][$i]['id'] = $fields['id'];
                                            }

                                            // If numbering indicates that's new number, do generation
                                            if (
                                                $field['type'] == self::TIPE_ELEMENT_NUMBERING &&
                                                preg_match('/('.self::NEW_NUMBER_FLAG.')$/', $field['data'])
                                            ) {
                                                // if it's numbering, generate new number and update the last number
                                                $field['data'] = NumberingService::getFormattedNumber($field['penomoran_id'], $instansiId, $unitId, true);
                                            }

                                            // Format the Data
                                            switch ($field['type']) {
                                                case self::TIPE_ELEMENT_DATE:
                                                    $field['data'] = self::parseDate($field['data']);
                                                    break;
                                                case self::TIPE_ELEMENT_TEXTAREA:
                                                    $field['data'] = strip_tags($field['data'], null);
                                                    break;
                                            }

                                            if (!empty($field['data_kolom_id'])) {
                                                $dataKolomId = $field['data_kolom_id'];
                                                // Get Data Kolom
                                                $dataKolomData = $dataKolomTable
                                                    ->find('all', [
                                                        'fields' => [
                                                            'data_kolom', 'tipe_kolom'
                                                        ],
                                                        'conditions' => [
                                                            'id' => $dataKolomId
                                                        ],
                                                        'order' => [
                                                            'id' => 'ASC'
                                                        ]
                                                    ])->first();

                                                if (!empty($dataKolomData)) {

                                                    if (!empty($field['data'])) {
                                                        // format the value
                                                        switch ($dataKolomData['tipe_kolom']) {
                                                            case 'character varying':
                                                                $field['data'] = strval($field['data']);
                                                                break;
                                                            case 'bigint':
                                                                $field['data'] = intval($field['data']);
                                                                break;
                                                        }

                                                        $saveData[$datatabelId]['records'][$i][$dataKolomData['data_kolom']] = $field['data'];
                                                        $numNotEmpty++;
                                                    } elseif (strpos($keyField, $datatabelData['nama_datatabel']) !== false) {
                                                        $saveData[$datatabelId]['records'][$i][$dataKolomData['data_kolom']] = null;
                                                    }

                                                    // push to the label array with these json format: [{fieldName1 : label1}, {fieldName1 : label1}]
                                                    if (!empty($field['data_label'])) {
                                                        $dataLabels[$dataKolomData['data_kolom']] = $field['data_label'];
                                                    }
                                                }
                                            }

                                            // If all of them are empty, remove the record
                                            if ($numNotEmpty == 0 && $canvasData['ftype'] != self::TIPE_FORM) {
                                                unset($saveData[$datatabelId]['records'][$i]);
                                                continue;
                                            }

                                            // Set the del flag
                                            if ($fields['del'] == true) {
                                                $saveData[$datatabelId]['records'][$i]['del'] = 1;
                                            } else {
                                                $saveData[$datatabelId]['records'][$i]['del'] = 0;
                                            }

                                            // save labels from combogrid with these format: [{fieldName1 : label1}, {fieldName1 : label1}]
                                            if (!empty($dataLabels)) {
                                                $saveData[$datatabelId]['records'][$i]['data_labels'] = json_encode($dataLabels);
                                            }

                                            // set the key_id
                                            /**
                                             * hanya berlaku jika tabel bukan tabel utama / nama tabel tidak ada di
                                             **/
                                            if ($keyId && strpos($keyField, $datatabelData['nama_datatabel']) === false) {
                                                $saveData[$datatabelId]['records'][$i][$keyField] = $keyId;
                                            }
                                        }
                                    }

                                    // END - Looping Each Field
                                    $i++;
                                }
                                // END - Looping Each Record
                            }
							break;
                    }
                }
            }
        }

        $data = [
            'saveAction' => $saveAction,
            'saveData' => $saveData
        ];

        return $data;
    }

    /**
     * Take form and related data, then remove id and foreign key ids
     * @param $form
     * @return array
     */
    public static function prepareCopyFormSetup($form)
    {
        if (!empty($form)) {
            unset($form['id']);

            $instansiId = (self::$instansi) ? self::$instansi->id : null;
            $form['instansi_id'] = $instansiId;
            $form['nama_form'] = $form['nama_form'] . '-copy';

            if (!empty($form['canvas'])) {
                foreach ($form['canvas'] as $canvasIndex => $canvas) {
                    unset($form['canvas'][$canvasIndex]['id']);
                    unset($form['canvas'][$canvasIndex]['form_id']);

                    if (!empty($form['canvas'][$canvasIndex]['canvas_tab'])) {
                        foreach ($form['canvas'][$canvasIndex]['canvas_tab'] as $tabIndex => $tab) {
                            unset($form['canvas'][$canvasIndex]['canvas_tab'][$tabIndex]['id']);
                            unset($form['canvas'][$canvasIndex]['canvas_tab'][$tabIndex]['canvas_id']);
                        }
                    }

                    if (!empty($form['canvas'][$canvasIndex]['canvas_element'])) {
                        foreach ($form['canvas'][$canvasIndex]['canvas_element'] as $elementIndex => $element) {
                            unset($form['canvas'][$canvasIndex]['canvas_element'][$elementIndex]['id']);
                            unset($form['canvas'][$canvasIndex]['canvas_element'][$elementIndex]['canvas_id']);

                            if (!empty($form['canvas'][$canvasIndex]['canvas_element'][$elementIndex]['element_option'])) {
                                foreach ($form['canvas'][$canvasIndex]['canvas_element'][$elementIndex]['element_option'] as $optionIndex => $option) {
                                    unset($form['canvas'][$canvasIndex]['canvas_element'][$elementIndex]['element_option'][$optionIndex]['id']);
                                    unset($form['canvas'][$canvasIndex]['canvas_element'][$elementIndex]['element_option'][$optionIndex]['element_id']);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $form;
    }

    public static function prepareCopyTemplateData($templateData)
    {
        if (!empty($templateData)) {
            unset($templateData['id']);
            unset($templateData['template_data_id']);
            unset($templateData['tgl_dibuat']);
            unset($templateData['dibuat_oleh']);
            unset($templateData['tgl_diubah']);
            unset($templateData['diubah_oleh']);

            $instansiId = (self::$instansi) ? self::$instansi->id : null;
            $templateData['instansi_id'] = $instansiId;
            $templateData['keterangan'] = $templateData['keterangan'] . '-copy';

            $relatedEntities = [
                'kelompok_data'
            ];

            foreach ($relatedEntities as $relatedEntity) {

                if (array_key_exists($relatedEntity, $templateData) && !empty($templateData[$relatedEntity])) {

                    foreach ($templateData[$relatedEntity] as $index => $kelompokData) {
                        unset($templateData[$relatedEntity][$index]['id']);
                        unset($templateData[$relatedEntity][$index]['template_data_id']);
                        unset($templateData[$relatedEntity][$index]['tgl_dibuat']);
                        unset($templateData[$relatedEntity][$index]['dibuat_oleh']);
                        unset($templateData[$relatedEntity][$index]['tgl_diubah']);
                        unset($templateData[$relatedEntity][$index]['diubah_oleh']);

                        $relatedEntities2 = [
                            'kelompok_tabel', 'kelompok_kolom', 'kelompok_kondisi'
                        ];

                        foreach ($relatedEntities2 as $relatedEntity2) {
                            if (
                                array_key_exists($relatedEntity2, $kelompokData) &&
                                !empty($kelompokData[$relatedEntity2])
                            ) {
                                foreach ($kelompokData[$relatedEntity2] as $index2 => $linkedEntity) {
                                    unset($templateData[$relatedEntity][$index][$relatedEntity2][$index2]['id']);
                                    unset($templateData[$relatedEntity][$index][$relatedEntity2][$index2]['kelompok_data_id']);
                                    unset($templateData[$relatedEntity][$index][$relatedEntity2][$index2]['tgl_dibuat']);
                                    unset($templateData[$relatedEntity][$index][$relatedEntity2][$index2]['dibuat_oleh']);
                                    unset($templateData[$relatedEntity][$index][$relatedEntity2][$index2]['tgl_diubah']);
                                    unset($templateData[$relatedEntity][$index][$relatedEntity2][$index2]['diubah_oleh']);
                                }
                            }
                        }
                    }
                }
            }

        }

        return $templateData;
    }

    public static function getUserUnit($userId)
    {
        $penggunaUnitIds = [];

        // Get User Unit
        $penggunaTable = TableRegistry::get('Pengguna');
        $pengguna = $penggunaTable->find()->select(['id'])
            ->contain(['Unit' => [
                'fields' => ['Unit.id', 'UnitPengguna.pengguna_id']
            ]])
            ->where(['id' => $userId])
            ->all();

        if (!empty($pengguna)) {
            foreach ($pengguna as $penggunaData) {
                foreach ($penggunaData->unit as $unit) {
                    $penggunaUnitIds[] = $unit->id;
                }
            }
        }

        return $penggunaUnitIds;
    }

    public static function isAllowedUnit($unitIds, $datatabelId)
    {
        if (empty($unitIds)) {
            return true;
        }

        $unitDatatabelTable = TableRegistry::get('UnitDatatabel');

        // Get at least one record of Unit Datatabel
        $unitDatatabel = $unitDatatabelTable->find()
            ->select(['unit_id'])
            ->where([
                'datatabel_id' => $datatabelId
            ])
            ->first();

        // If no record of Unit Datatabel, allow all access
        if (!$unitDatatabel) {
            return true;
        }

        // Match User Unit Ids with unit ids from Unit Datatabel
        $unitDatatabel = $unitDatatabelTable->find()
            ->select(['unit_id'])
            ->where([
                'datatabel_id' => $datatabelId,
                'unit_id IN' => $unitIds
            ])
            ->first();

        if ($unitDatatabel) {
            return true;
        }

        return false;
    }

    /**
     * Get Data of a Canvas by running SQL Query
     * @param integer $canvasId
     * @param integer|null $keyId
     * @return array
     */
    public static function getCanvasData($canvasId, $keyId = null, $page = null, $limit = null)
    {
        $data = [];
        $filteredFields = [];

        try {
            // Parse parameter
            $page = ($page) ? (int) $page : null;
            $limit = ($limit) ? (int) $limit : null;

            // Load Canvas by Using the ID
            $canvasTable = TableRegistry::get('Canvas');
            $canvas = $canvasTable->get($canvasId, [
                'fields' => ['id', 'datatabel_id', 'form_id', 'form_type', 'initial_web_service'],
                'contain' => [
                    'Form' => [
                        'fields' => ['id', 'key_field']
                    ],
                    'Datatabel' => [
                        'fields' => ['id', 'nama_datatabel', 'use_mapper', 'is_custom']
                    ],
                    'CanvasElement' => [
                        'fields' => ['id', 'canvas_id', 'data_kolom_id'],
                        'conditions' => ['del' => 0]
                    ],
                    'CanvasElement.DataKolom' => [
                        'fields' => ['id', 'data_kolom', 'tipe_kolom']
                    ]
                ],
                'conditions' => ['Canvas.del' => 0]
            ]);

            if ($canvas->datatabel) {
                $fieldToSelect = [];
                $conditions = [];

                $tableName = $canvas->datatabel->nama_datatabel;
                $isCustom = $canvas->datatabel->is_custom;
                $useMapper = $canvas->datatabel->use_mapper;

                // Check if it's altered default table
                if (self::isAlteredDefaultTable($tableName)) {
                    $isCustom = true;
                }

                // Get the 'q' query string
                $qVal = null;
                if (array_key_exists('q', self::$queryStrings) && strlen(self::$queryStrings['q']) > 0) {
                    $qVal = self::$queryStrings['q'];
                }

                // Default field to select besides custom fields
                if ($isCustom) { // For custom tables
                    $fieldToSelect = ["$tableName.id", "$tableName.del", "$tableName.data_labels"];
                    $conditions["$tableName.del"] = 0;
                } else {
                    $fieldToSelect = ["$tableName.id"];
                }

                if (!empty($canvas->canvas_element)) {

                    foreach ($canvas->canvas_element as $element) {
                        if ($element->data_kolom) {
                            $fieldName = $element->data_kolom->data_kolom;
                            $fullFieldName = $tableName. "." . $fieldName;
                            $fieldToSelect[] = $fullFieldName; // Field name

                            // if field name is the same with one of the query strings, create the filter
                            if (array_key_exists($fieldName, self::$queryStrings) && strlen(self::$queryStrings[$fieldName]) > 0) {
                                // Create filter based on type of column
                                // TODO make sure whether tipe_kolom will contains native postgres column type or use conversion
                                switch ($element->data_kolom->tipe_kolom) {
                                    case 'character varying':
                                        $conditions["{$fullFieldName} ILIKE"] = '%' . self::$queryStrings[$fieldName] . '%';
                                        break;
                                    case 'bigint':
                                        $conditions["{$fullFieldName}"] = (int) self::$queryStrings[$fieldName];
                                        break;
                                    case 'double precision':
                                        $conditions["{$fullFieldName}"] = (double) self::$queryStrings[$fieldName];
                                        break;
                                    case 'date':
                                        $conditions["{$fullFieldName}"] = self::parseDate(self::$queryStrings[$fieldName]);
                                        break;
                                }

                                $filteredFields[] = $fullFieldName;
                            }

                            // If q is provided and filter for that field has not been applied
                            if (!in_array($fullFieldName, $filteredFields) && $qVal) {
                                $conditions['OR']["{$fullFieldName} ILIKE"] = '%' . $qVal . '%';
                                $filteredFields[] = $fullFieldName;
                            }
                        }
                    }

                }

                // Load the Custom Table
                $datatabelTable = TableRegistry::get($tableName, [
                    'table' => $tableName
                ]);

                if ($datatabelTable) {
                    //Select all records
                    $query = $datatabelTable->find('all')->select($fieldToSelect)->from($tableName);

                    // If it's using mapper, join with the mapper table
                    if ($useMapper) {
                        $query->innerJoin('mapper', [
                            "mapper.datatabel_record_id = $tableName.id",
                            "mapper.nama_datatabel" => $tableName
                        ]);

                        if ($keyId) {
                            $conditions["mapper.key_id"] = $keyId;
                        }
                    } else {
                        if ($keyId) {
                            if ($tableName . "_id" == $canvas->form->key_field) {
                                $conditions["$tableName.id"] = $keyId;
                            } else {
                                $conditions["$tableName.{$canvas->form->key_field}"] = $keyId;
                            }
                        }
                    }

                    if (!empty($conditions)) {
                        $query->where($conditions);
                    }

                    $count = $query->count();

                    if ($count > 0) {
                        // calculate limit and offset for pagination
                        if ($page && $limit) {

                            $totalPages = ceil($count / $limit);

                            if ($page > $totalPages) $page = $totalPages;
                            $start = $limit * $page - $limit; // do not put $limit*($page - 1)
                            $query->limit($limit)->offset($start);

                            // return the data with pagination info
                            $data = [
                                'limit' => $limit,
                                'page' => $page,
                                'items' => $query->toArray(),
                                'total_items' => $count
                            ];
                        } else {
                            // return the data
                            $data = $query->toArray();
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            self::$exceptionMessage = $ex->getMessage();
            throw new \Exception(self::$exceptionMessage);
        }

        return $data;
    }

    /**
     * Check if the table has been treated as custom table
     * @return array
     */
    public static function isAlteredDefaultTable($tableName)
    {
        $alteredTable = [
            'pegawai', 'data_sinc', 'data_sinc_detail'
        ];

        if (in_array($tableName, $alteredTable)) {
            return true;
        }

        return false;
    }
}
