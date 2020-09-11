<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('DashedRoute');

Router::scope('/', function (RouteBuilder $routes) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');

    $routes->setExtensions(['json']);
    $routes->resources('Desa');
    $routes->resources('Caduan');
});

// codeless api routes
Router::prefix('api', function (RouteBuilder $routes) {
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->fallbacks('DashedRoute');

    $routes->setExtensions(['json']);

    $routes->resources('Pengguna', [
        'map' => [
            'profile' => ['action' => 'profile', 'method' => 'GET'],
            'token' => ['action' => 'token', 'method' => 'POST'],
            'logout' => ['action' => 'logout', 'method' => 'POST'],
            'deleteunit' => ['action' => 'deleteUnit', 'method' => 'DELETE'],
            'deletejenisizin' => ['action' => 'deleteJenisIzin', 'method' => 'DELETE'],
            'deletejenisproses' => ['action' => 'deleteJenisProses', 'method' => 'DELETE'],
            'setting' => ['action' => 'getSetting', 'method' => 'GET'],
//            'signup' => ['action' => 'signup', 'method' => 'POST'],
            'forgotpassword' => ['action' => 'forgotPassword', 'method' => 'POST'],
            'checkresettoken' => ['action' => 'checkResetToken', 'method' => 'POST'],
            'resetpassword' => ['action' => 'resetPassword', 'method' => 'POST'],
            'getuservars' => ['action' => 'getUserVars', 'method' => 'GET'],
            'changepassword' => ['action' => 'changePassword', 'method' => 'POST']
        ]
    ]);

    $routes->resources('Peran', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET'],
            'allmenu' => ['action' => 'getAllMenu', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Desa', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Caduan', ['path' => 'caduan']);
    $routes->resources('Caduan', [
        'map' => [
            'edit' => ['action' => 'edit', 'method' => 'POST'],
            'getNumber' => ['action' => 'getNumber', 'method' => 'GET'],
            'getAduanList' => ['action' => 'getAduanList', 'method' => 'GET'],
            'getKategoriList' => ['action' => 'getKategoriList', 'method' => 'GET'],
            'uploadFile' => ['action' => 'uploadFile', 'method' => 'POST'],
            'downloadFile' => ['action' => 'downloadFile', 'method' => 'GET'],
            'downloadFileKomentar' => ['action' => 'downloadFileKomentar', 'method' => 'GET'],
            'addKomentar' => ['action' => 'addKomentar', 'method' => 'POST'],
            'editAduan' => ['action' => 'editAduan', 'method' => 'PUT'],
            'reOpenStatus' => ['action' => 'reOpenStatus', 'method' => 'PUT'],
            'inovasiList' => ['action' => 'inovasiList', 'method' => 'GET'],
            'inovasiListProsesPengembangan' => ['action' => 'inovasiListProsesPengembangan', 'method' => 'GET'],
            'penangananAduanList' => ['action' => 'penangananAduanList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Kecamatan');
    $routes->resources('Kecamatan', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Kabupaten', [
        'map' => ['list' => ['action' => 'getList', 'method' => 'GET']]
    ]);

    $routes->resources('Provinsi', [
        'map' => ['list' => ['action' => 'getList', 'method' => 'GET']]
    ]);

    $routes->resources('Pegawai', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET'],
            'listPenanggungJawab' => ['action' => 'getListPenanggungJawab', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Unit', [
        'map' => [
            'unitlist' => ['action' => 'getUnitList', 'method' => 'GET'],
            'instansilist' => ['action' => 'getInstansiList', 'method' => 'GET'],
            'instansipubliclist' => ['action' => 'getInstansiPublicList', 'method' => 'GET'],
            'tipelist' => ['action' => 'getTipeList', 'method' => 'GET'],
            'hierarchy' => ['action' => 'getHierarchy', 'method'=> 'GET']
        ]
    ]);

    $routes->resources('JenisIzin', [
        'map' => [
            'getlist' => ['action' => 'getList', 'method' => 'GET'],
            'getpersyaratan' => ['action' => 'getPersyaratan', 'method' => 'GET'],
            'copy' => ['action' => 'copy', 'method' => 'PUT']
        ]
    ]);

    $routes->resources('Menu', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET'],
            'appmenu' => ['action' => 'getAppMenu', 'method'=> 'GET'],
            'hierarchy' => ['action' => 'getHierarchy', 'method'=> 'GET']
        ]
    ]);

    $routes->resources('Datatabel', [
        'map' => [
            'deletedatakolom' => ['action' => 'deleteDataKolom', 'method' => 'DELETE'],
            'tipekolomlist' => ['action' => 'getTipeKolomList', 'method' => 'GET'],
            'records' => ['action' => 'getRecords', 'method' => 'GET'],
        ]
    ]);

    $routes->resources('JenisProses', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('AlurProses', [
        'map' => [
            'deletejenisproses' => ['action' => 'deleteJenisProses', 'method' => 'DELETE'],
            'list' => ['action' => 'getList', 'method' => 'GET'],
            'tautanlist' => ['action' => 'getTautanList', 'method' => 'GET'],
            'copyalur' => ['action' => 'copyAlur', 'method' => 'PUT']
        ]
    ]);
    $routes->resources('DaftarProses');

    $routes->resources('DokumenPendukung', [
        'map' => [
            'statuslist' => ['action' => 'getStatusList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('JenisPengajuan', [
        'map' => [
            'jenislist' => ['action' => 'getJenisList', 'method' => 'GET'],
            'satuanlist' => ['action' => 'getSatuanList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('PenanggungJawab');

    $routes->resources('IzinParalel');

    $routes->resources('UnitTerkait');

    $routes->resources('Pemohon', [
        'map' => [
            'genderlist' => ['action' => 'getGenderList', 'method' => 'GET'],
            'jenisidentitaslist' => ['action' => 'getJenisIdentitasList', 'method' => 'GET'],
            'permohonanizinlist' => ['action' => 'getPermohonanIzinList', 'method' => 'GET'],
            'permohonanizindetail' => ['action' => 'getPermohonanIzinDetail', 'method' => 'GET'],
            'perusahaanlist' => ['action' => 'getPerusahaanList', 'method' => 'GET'],
            'perusahaan' => ['action' => 'addPerusahaan', 'method' => 'POST'],
            'editperusahaan' => ['action' => 'editPerusahaan', 'method' => 'PUT'],
            'register' => ['action' => 'register', 'method' => 'POST'],
            'approve' => ['action' => 'approve', 'method' => 'POST'],
            'checkverifytoken' => ['action' => 'checkVerifyToken', 'method' => 'POST'],
            'verify' => ['action' => 'verify', 'method' => 'POST'],
        ]
    ]);

    $routes->resources('Perusahaan', [
        'map' => [
            'jenislist' => ['action' => 'getJenisList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Persyaratan', [
        'map' => [
            'getbypermohonan' => ['action' => 'getByPermohonan', 'method' => 'GET']
        ]
    ]);

    $routes->resources('PermohonanIzin', [
        'map' => [
            'getprosespermohonan' => ['action' => 'getProsesPermohonan', 'method' => 'GET'],
            'opennextstep' => ['action' => 'openNextStep', 'method' => 'POST'],
            'tipepemohonlist' => ['action' => 'getTipePemohonList', 'method' => 'GET'],
            'list' => ['action' => 'getList', 'method' => 'GET'],
            'nomorizin' => ['action' => 'getNomorIzin', 'method' => 'GET'],
            'getpermohonantosign' => ['action' => 'getPermohonanToSign', 'method' => 'GET'],
            'uploadsignature' => ['action' => 'uploadSignature', 'method' => 'POST'],
            'permohonantocertify' => ['action' => 'getPermohonanToCertify', 'method' => 'GET'],
            'listNib' => ['action' => 'listNib', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Izin');

    $routes->resources('TemplateData', [
        'map' => [
            'tipekeluaranlist' => ['action' => 'getTipeKeluaranList', 'method' => 'GET'],
            'keluaran' => ['action' => 'keluaran', 'method' => 'GET'],
            'testquery' => ['action' => 'testQuery', 'method' => 'GET'],
            'testgetpdfsign' => ['action' => 'testGetPdfSign', 'methdo' => 'GET'],
            'generatereport' => ['action' => 'generateReport', 'method' => 'GET'],
            'generatereporttosign' => ['action' => 'generateReportToSign', 'method' => 'GET'],
            'copy' => ['action' => 'copy', 'method' => 'PUT']
        ]
    ]);
    
    $routes->resources('KelompokData', [
        'map' => [
            'jenissumberlist' => ['action' => 'getJenisSumberList', 'method' => 'GET'],
            'tipekelompoklist' => ['action' => 'getTipeKelompokList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('KelompokTabel', [
        'map' => [
            'tipejoinlist' => ['action' => 'getTipeJoinList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('KelompokKolom');

    $routes->resources('KelompokKondisi', [
        'map' => [
            'tipekondisilist' => ['action' => 'getTipeKondisiList', 'method' => 'GET'],
            'tiperelasilist' => ['action' => 'getTipeRelasiList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('JenisUsaha');

    $routes->resources('BidangUsaha');
    $routes->resources(
        'BidangUsaha', [
            'map' => [
                'list' => ['action' => 'getList', 'method' => 'GET']
            ]
        ]
    );

    $routes->resources('Form');

    $routes->resources('UnitDatatabel');

    $routes->resources('Persyaratan');

    $routes->resources('TarifItem');

    $routes->resources('FormulaRetribusi');

    $routes->resources('Penomoran');
    $routes->resources(
        'Penomoran', [
            'map' => [
                'list' => ['action' => 'getList', 'method' => 'GET']
            ]
        ]
    );

    $routes->resources('Kalender');

    $routes->resources(
        'Notifikasi', [
            'map' => [
                'supportdata' => ['action' => 'getSupportData', 'method' => 'GET']
            ]
        ]
    );

    $routes->resources(
        'ServiceEksternal', [
            'map' => [
                'list' => ['action' => 'getList', 'method' => 'GET'],
                'tipeotentikasilist' => ['action' => 'getTipeOtentikasiList', 'method' => 'GET'],
            ]
        ]
    );

    $routes->resources('JenisDokumen', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('DokumenPemohon', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET']
        ]
    ]);

    $routes->resources('Pesan', [
        'map' => [
            'downloadlampiran' => ['action' => 'downloadLampiran', 'method' => 'GET'],
            'notifikasi' => ['action' => 'getNotifikasi', 'method' => 'GET'],
            'insertPesanDibaca' => ['action' => 'insertPesanDibaca', 'method' => 'GET'],
            'upload' => ['action' => 'upload', 'method' => 'POST']
        ]
    ]);

    $routes->resources('GatewayUsers');

    $routes->resources(
        'ReportComponents', [
            'map' => [
                'supportdata' => ['action' => 'getSupportData', 'method' => 'GET']
            ]
        ]
    );

    $routes->resources(
        'Jabatan', [
            'map' => [
                'list' => ['action' => 'getJabatanList', 'method' => 'GET']
            ]
        ]
    );

    $routes->resources('LaporanPermasalahan');

    $routes->resources('RestUsers');

    $routes->resources('RestServices');

    $routes->resources(
        'ProsesPermohonan', [
            'map' => [
                'uploadsignedreport' => ['action' => 'uploadSignReport', 'method' => 'POST'],
                'downloadsignedreport' => ['action' => 'downoloadSignReport', 'method' => 'GET'],
                'signreport' => ['action' => 'signReport', 'method' => 'POST']
            ]
        ]
    );

    // $routes->resources('Dashboard', [
    //     'map' => [
    //         'tabs' => [
    //             'action' => 'getTab',
    //             'method' => 'GET',
    //             'path' => 'tabs/:id'
    //         ]
    //     ]
    // ]);

    $routes->resources('Pages', [
        'map' => [
            'charts' => [
                'action' => 'getChartData',
                'method' => 'GET',
                'path' => 'charts/:id'
            ],
            'chart_types' => [
                'action' => 'getChartTypes',
                'method' => 'GET',
                'path' => 'chart_types'
            ]
        ]
    ]);

    $routes->resources('Test');
    $routes->resources('Faq', [
        'map' => [
            'list' => ['action' => 'upload', 'method' => 'POST'],
            'downloadlampiran' => ['ACTION' => 'downloadLampiran', 'method' => 'GET']
        ]
    ]);

    $routes->resources('FaqCategory', [
        'map' => [
            'list' => ['action' => 'getList', 'method' => 'GET']
        ]
    ]);
});

// mobile sms gateway routes
Router::prefix('gateway', function (RouteBuilder $routes) {
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->fallbacks('DashedRoute');

    $routes->setExtensions(['json']);

    $routes->resources('Users');
    $routes->resources(
        'Users', [
            'map' => [
                'login' => ['action' => 'login', 'method' => 'POST']
            ]
        ]
    );

    $routes->resources(
        'Messages', [
            'map' => [
                'fetch' => ['action' => 'fetch', 'method' => 'POST'],
                'forward' => ['action' => 'forward', 'method' => 'POST']
            ]
        ]
    );
});

// third party REST API
Router::prefix('rest', function (RouteBuilder $routes) {
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->fallbacks('DashedRoute');

    $routes->setExtensions(['json']);

    $routes->resources('RestAuth');
    $routes->resources(
        'RestAuth', [
            'map' => [
                'login' => ['action' => 'login', 'method' => 'POST']
            ]
        ]
    );

    $routes->resources(
        'Public', [
            'map' => [
                'create' => ['action' => 'create', 'method' => 'POST'],
                'update' => ['action' => 'update', 'method' => 'POST'],
                'delete' => ['action' => 'delete', 'method' => 'POST'],
                'get' => ['action' => 'get', 'method' => 'POST'],
                'getall' => ['action' => 'getAll', 'method' => 'POST']
            ]
        ]
    );

    $routes->resources(
        'Sinkronisasi', [
            'map' => [
                'save' => ['action' => 'saveData', 'method' => 'POST'],
                'post' => ['action' => 'postData', 'method' => 'POST']
            ]
        ]
    );

});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
//Plugin::routes();
