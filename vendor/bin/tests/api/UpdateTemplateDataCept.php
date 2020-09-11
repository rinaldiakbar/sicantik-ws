<?php
$I = new ApiTester($scenario);
//$I->wantTo('Login via API');
//$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendPOST('/pengguna/token', ['username' => 'indra', 'password' => 'indra']);
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
$I->seeResponseIsJson();
$I->seeResponseJsonMatchesJsonPath('$.token');
$loginResponse = json_decode($I->grabResponse());
$I->haveHttpHeader('Authorization', 'Bearer '.$loginResponse->token);

// BEGIN - Update Data
$I->wantTo('Update Template Data');
$I->sendPUT('/template_data/1',
    [
        'keterangan' => 'Template 1 updated via CLI'
    ],
    [
        'myFile' => [
            'name' => 'indratest_sl.js',
            'type' => 'text/plain',
            'error' => UPLOAD_ERR_OK,
            'size' => filesize(codecept_data_dir('indratest_sl.js')),
            'tmp_name' => codecept_data_dir('indratest_sl.js'),
        ]
    ]
);
$I->seeResponseIsJson();
$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
$updateResponse = json_decode($I->grabResponse());
//var_dump($updateResponse);
// END - Update Data