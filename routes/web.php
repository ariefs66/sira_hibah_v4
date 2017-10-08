<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();
Route::get('/', 'mainController@index');
// Route::get('/', function(){return View('maintenence');});
//Route::get('/login', function(){return View('maintenence');});
Route::get('/keluar/{id}', 'mainController@keluar');
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/aktif/{id}', 'mainController@aktifuser');
Route::get('/off/{id}', 'mainController@offuser');
Route::post('/chpass', 'mainController@chpass');
Route::post('/chprofile', 'mainController@chprofile');
Route::get('/gettahun/{tahun}/{status}', 'mainController@getTABudgeting');		

//BUDGETING
Route::get('/main/{tahun}/{status}', 'Budgeting\dashboardController@index');		
// Route::get('/main/{tahun}/{status}', function(){return View('maintenence');});														
//------------------------------------------------------------------------------------------------------------------------
//BL-GET
Route::get('/main/{tahun}/{status}/belanja-langsung', 'Budgeting\blController@index');
Route::get('/main/{tahun}/{status}/belanja-langsung/detail/{id}', 'Budgeting\blController@showDetail');
Route::get('/main/{tahun}/{status}/belanja-langsung/detail/arsip/{id}', 'Budgeting\blController@showDetailArsip');
Route::get('/main/{tahun}/{status}/belanja-langsung/tambah', 'Budgeting\blController@add');
Route::get('/main/{tahun}/{status}/belanja-langsung/ubah/{id}', 'Budgeting\blController@edit');
Route::get('/main/{tahun}/{status}/belanja-langsung/indikator/{id}', 'Budgeting\blController@indikator');
Route::get('/main/{tahun}/{status}/belanja-langsung/rincian/{id}', 'Budgeting\blController@showRincian');
Route::get('/main/{tahun}/{status}/belanja-langsung/rincian/pagu/getpagu/{id}', 'Budgeting\blController@getpagurincian');
Route::get('/main/{tahun}/{status}/belanja-langsung/rincian/arsip/{id}', 'Budgeting\blController@showRincianArsip');
Route::get('/main/{tahun}/{status}/belanja-langsung/rincianrekap/{tipe}/{id}', 'Budgeting\blController@showRekap');
Route::get('/main/{tahun}/{status}/belanja-langsung/rincian/detail/{id}', 'Budgeting\blController@detailRincian');
Route::get('/main/{tahun}/{status}/belanja-langsung/geturgensi/{id}', 'Budgeting\blController@getUrgensi');
Route::get('/main/{tahun}/{status}/belanja-langsung/usulan-pagu', 'Budgeting\blController@usulanpagu');
//BL-POST
Route::post('/main/{tahun}/{status}/belanja-langsung/hapus', 'Budgeting\blController@deleteBL');
Route::post('/main/{tahun}/{status}/belanja-langsung/detail/simpan', 'Budgeting\blController@submitDetail');
Route::post('/main/{tahun}/{status}/belanja-langsung/detail/ubah/simpan', 'Budgeting\blController@submitDetailEdit');
Route::post('/main/{tahun}/{status}/belanja-langsung/detail/hapus', 'Budgeting\blController@delDetail');
Route::post('/main/{tahun}/{status}/belanja-langsung/rincian/simpan', 'Budgeting\blController@submitRincian');
Route::post('/main/{tahun}/{status}/belanja-langsung/rincian/ubah', 'Budgeting\blController@submitRincianEdit');
Route::post('/main/{tahun}/{status}/belanja-langsung/rincian/hapus', 'Budgeting\blController@deleteRincian');
Route::post('/main/{tahun}/{status}/belanja-langsung/rincian/hapus-cb', 'Budgeting\blController@deleteRincianCB');
Route::post('/main/{tahun}/{status}/belanja-langsung/rincian/back-cb', 'Budgeting\blController@restoreRincianCB');
Route::post('/main/{tahun}/{status}/belanja-langsung/validasi', 'Budgeting\blController@validasi');
Route::post('/main/{tahun}/{status}/belanja-langsung/kuncigiat', 'Budgeting\blController@kuncigiat');
Route::post('/main/{tahun}/{status}/belanja-langsung/kunciall', 'Budgeting\blController@kunciall');
Route::post('/main/{tahun}/{status}/belanja-langsung/kuncigiatskpd', 'Budgeting\blController@kuncigiatskpd');
Route::post('/main/{tahun}/{status}/belanja-langsung/kuncirincian', 'Budgeting\blController@kuncirincian');
Route::post('/main/{tahun}/{status}/belanja-langsung/kuncirincianskpd', 'Budgeting\blController@kuncirincianskpd');
Route::post('/main/{tahun}/{status}/belanja-langsung/setStaff', 'Budgeting\blController@setStaff');
Route::post('/main/{tahun}/{status}/belanja-langsung/rincian-musrenbang/simpan', 'Budgeting\blController@setMusren');
Route::post('/main/{tahun}/{status}/belanja-langsung/simpanpaket', 'Budgeting\blController@setPaket');
Route::post('/main/{tahun}/{status}/belanja-langsung/setpagu', 'Budgeting\blController@setPagu');
Route::post('/main/{tahun}/{status}/belanja-langsung/urgensi/simpan', 'Budgeting\blController@setUrgensi');
Route::post('/main/{tahun}/{status}/belanja-langsung/usulan-pagu/terima', 'Budgeting\blController@usulanpaguterima');
Route::post('/main/{tahun}/{status}/belanja-langsung/usulan-pagu/tolak', 'Budgeting\blController@usulanpagutolak');
//BL-API
Route::get('/main/{tahun}/{status}/belanja-langsung/getMurni/{filter}', 'Budgeting\blController@getMurni');
Route::get('/main/{tahun}/{status}/belanja-langsung/ringkasanrekening/{id}', 'Budgeting\blController@getringkasanrekening');
Route::get('/main/{tahun}/{status}/belanja-langsung/kegiatan/{id}/{sub}', 'Budgeting\blController@getKegiatan');
Route::get('/main/{tahun}/{status}/belanja-langsung/capaian/{id}', 'Budgeting\blController@getCapaian');
Route::get('/main/{tahun}/{status}/belanja-langsung/capaianedit/{id}', 'Budgeting\blController@getEditCapaian');
Route::get('/main/{tahun}/{status}/belanja-langsung/rekening/{id}', 'Budgeting\blController@getRekening');
Route::get('/main/{tahun}/{status}/belanja-langsung/komponen/{id}/{blid}', 'Budgeting\blController@getKomponen');
Route::get('/main/{tahun}/{status}/belanja-langsung/staff/{id}', 'Budgeting\blController@getStaff');
Route::get('/main/{tahun}/{status}/belanja-langsung/log/{id}', 'Budgeting\blController@getLog');
Route::get('/main/{tahun}/{status}/belanja-langsung/rekening-musrenbang/{id}', 'Budgeting\blController@getRekMusren');
Route::get('/main/{tahun}/{status}/belanja-langsung/getpagu/{id}', 'Budgeting\blController@getPagu');
//BL-LAMPIRAN
Route::get('/main/{tahun}/{status}/belanja-langsung/rka/{id}', 'Budgeting\lampiranController@rka');
Route::get('/main/{tahun}/{status}/lampiran/{tipe}', 'Budgeting\lampiranController@showLampiran');
Route::get('/main/{tahun}/{status}/lampiran/rkpd/{id}', 'Budgeting\lampiranController@rkpd');
Route::get('/main/{tahun}/{status}/lampiran/rkpddownload/{id}', 'Budgeting\lampiranController@rkpdDownload');
Route::get('/main/{tahun}/{status}/lampiran/ppas/{id}', 'Budgeting\lampiranController@ppas');
Route::get('/main/{tahun}/{status}/lampiran/ppasrincian/{id}', 'Budgeting\lampiranController@ppasRincian');
Route::get('/main/{tahun}/{status}/lampiran/ppasdownload/{id}', 'Budgeting\lampiranController@ppasDownload');
Route::get('/main/{tahun}/{status}/lampiran-download/ppas/{tipe}', 'Budgeting\lampiranController@ppasProgram');
Route::get('/main/{tahun}/{status}/lampiran/apbd/1', 'Budgeting\lampiranController@lampiran1');
//------------------------------------------------------------------------------------------------------------------------
//BTL
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/', 'Budgeting\btlController@index');
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/subunit/{id}', 'Budgeting\pendapatanController@getsubunit');
Route::post('/main/{tahun}/{status}/belanja-tidak-langsung/simpan', 'Budgeting\btlController@submitAdd');
Route::post('/main/{tahun}/{status}/belanja-tidak-langsung/ubah', 'Budgeting\btlController@submitEdit');
Route::post('/main/{tahun}/{status}/belanja-tidak-langsung/hapus', 'Budgeting\btlController@delete');
//BTL-API
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/edit/{id}', 'Budgeting\btlController@getId');
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/pegawai', 'Budgeting\btlController@getPegawai');
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/subsidi', 'Budgeting\btlController@getSubsidi');
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/hibah', 'Budgeting\btlController@getHibah');
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/bantuan', 'Budgeting\btlController@getBantuan');
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/btt', 'Budgeting\btlController@getBTT');
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/getRekening/{id}', 'Budgeting\btlController@getRekening');					
Route::get('/main/{tahun}/{status}/belanja-tidak-langsung/getDetail/{skpd}/{id}', 'Budgeting\btlController@getDetail');
//------------------------------------------------------------------------------------------------------------------------
//BL-ARSIP
Route::get('/main/{tahun}/{status}/arsip/belanja-langsung', 'Budgeting\arsipBLController@index');
Route::post('/main/{tahun}/{status}/arsip/belanja-langsung/restore', 'Budgeting\arsipBLController@restore');
Route::post('/main/{tahun}/{status}/arsip/belanja-langsung/delete', 'Budgeting\arsipBLController@delete');
//BL-ARSIP-API
Route::get('/main/{tahun}/{status}/arsip/belanja-langsung/getData', 'Budgeting\arsipBLController@getData');
//------------------------------------------------------------------------------------------------------------------------
//PENDAPATAN
Route::get('/main/{tahun}/{status}/pendapatan/', 'Budgeting\pendapatanController@index');
Route::get('/main/{tahun}/{status}/pendapatan/edit/{id}', 'Budgeting\pendapatanController@getId');
Route::get('/main/{tahun}/{status}/pendapatan/hapus/{id}', 'Budgeting\pendapatanController@delete');
Route::get('/main/{tahun}/{status}/pendapatan/subunit/{id}', 'Budgeting\pendapatanController@getsubunit');
Route::post('/main/{tahun}/{status}/pendapatan/simpan', 'Budgeting\pendapatanController@submitAdd');
Route::post('/main/{tahun}/{status}/pendapatan/ubah', 'Budgeting\pendapatanController@submitEdit');
Route::post('/main/{tahun}/{status}/pendapatan/hapus', 'Budgeting\pendapatanController@delete');
//PENDAPATAN-API
Route::get('/main/{tahun}/{status}/pendapatan/getData', 'Budgeting\pendapatanController@getPendapatan');
Route::get('/main/{tahun}/{status}/pendapatan/getDetail/{skpd}', 'Budgeting\pendapatanController@getDetail');
//------------------------------------------------------------------------------------------------------------------------
//PEMBIAYAAN
Route::get('/main/{tahun}/{status}/pembiayaan/', 'Budgeting\pembiayaanController@index');
Route::get('/main/{tahun}/{status}/pembiayaan/hapus/{id}', 'Budgeting\pembiayaanController@delete');
Route::post('/main/{tahun}/{status}/pembiayaan/simpan', 'Budgeting\pembiayaanController@submitAdd');
Route::get('/main/{tahun}/{status}/pembiayaan/', 'Budgeting\pembiayaanController@index');
//PEMBIAYAAN-API
Route::get('/main/{tahun}/{status}/pembiayaan/getData', 'Budgeting\pembiayaanController@getPembiayaan');
Route::get('/main/{tahun}/{status}/pembiayaan/getDetail/{skpd}', 'Budgeting\pembiayaanController@getDetail');
//------------------------------------------------------------------------------------------------------------------------
//USULAN
Route::get('/main/{tahun}/{status}/usulan/', 'Budgeting\usulanController@index');
Route::get('/main/{tahun}/{status}/usulan/api', 'apiController@api');
Route::get('/main/{tahun}/{status}/usulan/getMusrenbang', 'Budgeting\usulanController@getMusrenbang');
Route::get('/main/{tahun}/{status}/usulan/getMusrenbang/{kamus}/{giat}', 'Budgeting\usulanController@getMusrenbangFilter');
Route::get('/main/{tahun}/{status}/usulan/getRW', 'Budgeting\usulanController@getRW');
Route::get('/main/{tahun}/{status}/usulan/getKarta', 'Budgeting\usulanController@getKarta');
Route::get('/main/{tahun}/{status}/usulan/getLPM', 'Budgeting\usulanController@getLPM');
Route::get('/main/{tahun}/{status}/usulan/getPKK', 'Budgeting\usulanController@getPKK');
Route::get('/main/{tahun}/{status}/usulan/getReses', 'Budgeting\usulanController@getReses');
Route::get('/main/{tahun}/{status}/usulan/getReses/{id}', 'Budgeting\usulanController@getResesDetail');
Route::post('/main/{tahun}/{status}/usulan/musrenbang/set', 'Budgeting\usulanController@setMusrenbang');
Route::post('/main/{tahun}/{status}/usulan/reses/set', 'Budgeting\usulanController@setReses');
Route::post('/main/{tahun}/{status}/usulan/reses/tolak', 'Budgeting\usulanController@tolakReses');
Route::post('/main/{tahun}/{status}/usulan/pippk/setrw', 'Budgeting\usulanController@setRW');
Route::post('/main/{tahun}/{status}/usulan/pippk/setkarta', 'Budgeting\usulanController@setKarta');
Route::post('/main/{tahun}/{status}/usulan/pippk/setpkk', 'Budgeting\usulanController@setPKK');
Route::post('/main/{tahun}/{status}/usulan/pippk/setlpm', 'Budgeting\usulanController@setLPM');
//------------------------------------------------------------------------------------------------------------------------
//PENGATURAN
//------------------------------------------------------------------------------------------------------------------------
//STAFF
Route::get('/main/{tahun}/{status}/pengaturan/staff', 'Budgeting\staffController@index');
Route::get('/main/{tahun}/{status}/pengaturan/penyelia', 'Budgeting\staffController@penyelia');
Route::get('/main/{tahun}/{status}/pengaturan/staff/getData', 'Budgeting\staffController@getData');
Route::get('/main/{tahun}/{status}/pengaturan/penyelia/getData', 'Budgeting\staffController@penyeliaGetData');
Route::get('/main/{tahun}/{status}/pengaturan/penyelia/getData/skpd/{id}', 'Budgeting\staffController@penyeliaGetDataSkpd');
Route::get('/main/{tahun}/{status}/pengaturan/staff/getStaff', 'Budgeting\staffController@getStaff');
Route::get('/main/{tahun}/{status}/pengaturan/staff/getStaff/{id}', 'Budgeting\staffController@getStaffDetail');
Route::get('/main/{tahun}/{status}/pengaturan/penyelia/getPenyelia/{id}', 'Budgeting\staffController@getPenyeliaDetail');
Route::post('/main/{tahun}/{status}/pengaturan/staff/submitAdd', 'Budgeting\staffController@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/penyelia/submitAdd', 'Budgeting\staffController@submitAddPenyelia');
Route::post('/main/{tahun}/{status}/pengaturan/staff/submitEdit', 'Budgeting\staffController@submitEdit');
Route::post('/main/{tahun}/{status}/pengaturan/penyelia/submitEdit', 'Budgeting\staffController@submitEditPenyelia');
Route::post('/main/{tahun}/{status}/pengaturan/staff/submitEharga', 'Budgeting\staffController@submitEharga');
Route::post('/main/{tahun}/{status}/pengaturan/staff/submitEmonev', 'Budgeting\staffController@submitEmonev');
Route::post('/main/{tahun}/{status}/pengaturan/staff/hapus', 'Budgeting\staffController@hapus');
Route::post('/main/{tahun}/{status}/pengaturan/staff/reset', 'Budgeting\staffController@reset');
//TAHAPAN
Route::get('/main/{tahun}/{status}/pengaturan/tahapan', 'Budgeting\tahapanController@index');
Route::get('/main/{tahun}/{status}/pengaturan/tahapan/getData', 'Budgeting\tahapanController@getData');
Route::get('/main/{tahun}/{status}/pengaturan/tahapan/getData/{id}', 'Budgeting\tahapanController@getDetail');
Route::get('/main/{tahun}/{status}/pengaturan/subtahapan/getData/{id}', 'Budgeting\tahapanController@getSubTahapan');
Route::post('/main/{tahun}/{status}/pengaturan/tahapan/delete', 'Budgeting\tahapanController@delete');
Route::post('/main/{tahun}/{status}/pengaturan/tahapan/add/submit', 'Budgeting\tahapanController@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/subtahapan/add/submit', 'Budgeting\tahapanController@submitSubTahapan');
Route::post('/main/{tahun}/{status}/pengaturan/tahapan/edit/submit', 'Budgeting\tahapanController@submitEdit');
Route::post('/main/{tahun}/{status}/pengaturan/tahapan/tutup', 'Budgeting\tahapanController@tutupTahapan');
//URUSAN
Route::get('/main/{tahun}/{status}/pengaturan/urusan', 'Budgeting\Referensi\urusanController@index');
Route::get('/main/{tahun}/{status}/pengaturan/urusan/getData', 'Budgeting\Referensi\urusanController@getData');
Route::get('/main/{tahun}/{status}/pengaturan/urusan/getData/{id}', 'Budgeting\Referensi\urusanController@getDetail');
Route::post('/main/{tahun}/{status}/pengaturan/urusan/delete', 'Budgeting\Referensi\urusanController@delete');
Route::post('/main/{tahun}/{status}/pengaturan/urusan/add/submit', 'Budgeting\Referensi\urusanController@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/urusan/edit/submit', 'Budgeting\Referensi\urusanController@submitEdit');
//SKPD
Route::get('/main/{tahun}/{status}/pengaturan/skpd', 'Budgeting\Referensi\skpdController@index');
Route::get('/main/{tahun}/{status}/pengaturan/skpd/getData', 'Budgeting\Referensi\skpdController@getData');
Route::get('/main/{tahun}/{status}/pengaturan/skpd/getData/{id}', 'Budgeting\Referensi\skpdController@getDetail');
Route::post('/main/{tahun}/{status}/pengaturan/skpd/delete', 'Budgeting\Referensi\skpdController@delete');
Route::post('/main/{tahun}/{status}/pengaturan/skpd/add/submit', 'Budgeting\Referensi\skpdController@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/skpd/edit/submit', 'Budgeting\Referensi\skpdController@submitEdit');
//PROGRAM
Route::get('/main/{tahun}/{status}/pengaturan/program', 'Budgeting\Referensi\programController@index');
Route::get('/main/{tahun}/{status}/pengaturan/program/getData', 'Budgeting\Referensi\programController@getData');
Route::get('/main/{tahun}/{status}/pengaturan/program/getData/{id}', 'Budgeting\Referensi\programController@getDetail');
Route::get('/main/{tahun}/{status}/pengaturan/program/getDataDetail/{id}', 'Budgeting\Referensi\programController@getDataDetail');
Route::get('/main/{tahun}/{status}/pengaturan/program/getCapaian/{id}', 'Budgeting\Referensi\programController@getCapaian');
Route::get('/main/{tahun}/{status}/pengaturan/program/{tipe}/{id}', 'Budgeting\Referensi\programController@detailCapaian');
Route::post('/main/{tahun}/{status}/pengaturan/program/delete', 'Budgeting\Referensi\programController@delete');
Route::post('/main/{tahun}/{status}/pengaturan/program/add/submit', 'Budgeting\Referensi\programController@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/program/edit/submit', 'Budgeting\Referensi\programController@submitEdit');
Route::post('/main/{tahun}/{status}/pengaturan/program/submitCapaian', 'Budgeting\Referensi\programController@submitCapaian');
Route::post('/main/{tahun}/{status}/pengaturan/program/editCapaian', 'Budgeting\Referensi\programController@editCapaian');
Route::post('/main/{tahun}/{status}/pengaturan/program/hapusOutcome', 'Budgeting\Referensi\programController@hapusOutcome');
Route::post('/main/{tahun}/{status}/pengaturan/program/hapusImpact', 'Budgeting\Referensi\programController@hapusImpact');
//KEGIATAN
Route::get('/main/{tahun}/{status}/referensi/kegiatan/', 'Budgeting\Referensi\kegiatanController@indexReferensi');
Route::get('/main/{tahun}/{status}/referensi/kegiatan/getData', 'Budgeting\Referensi\kegiatanController@getReferensi');
Route::get('/main/{tahun}/{status}/pengaturan/kegiatan/getProgram/{id}', 'Budgeting\Referensi\kegiatanController@getProgram');
Route::get('/main/{tahun}/{status}/pengaturan/kegiatan/getData/{id}', 'Budgeting\Referensi\kegiatanController@getDetail');
Route::get('/main/{tahun}/{status}/pengaturan/kegiatan/getCapaian/{id}', 'Budgeting\Referensi\kegiatanController@getCapaian');
Route::get('/main/{tahun}/{status}/pengaturan/kegiatan/output/{id}', 'Budgeting\Referensi\kegiatanController@detailOutput');
Route::post('/main/{tahun}/{status}/pengaturan/kegiatan/delete', 'Budgeting\Referensi\kegiatanController@delete');
Route::post('/main/{tahun}/{status}/pengaturan/kegiatan/add/submit', 'Budgeting\Referensi\kegiatanController@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/kegiatan/edit/submit', 'Budgeting\Referensi\kegiatanController@submitEdit');
Route::post('/main/{tahun}/{status}/pengaturan/kegiatan/submitCapaian', 'Budgeting\Referensi\kegiatanController@submitCapaian');
Route::post('/main/{tahun}/{status}/pengaturan/kegiatan/editCapaian', 'Budgeting\Referensi\kegiatanController@editCapaian');
Route::post('/main/{tahun}/{status}/pengaturan/kegiatan/hapusOutput', 'Budgeting\Referensi\kegiatanController@hapusOutput');
//PROGRAM ADUM
Route::get('/main/{tahun}/{status}/pengaturan/adum/program', 'Budgeting\Referensi\programControllerAdum@index');
Route::get('/main/{tahun}/{status}/pengaturan/adum/program/getData', 'Budgeting\Referensi\programControllerAdum@getData');
Route::get('/main/{tahun}/{status}/pengaturan/adum/program/getData/{id}', 'Budgeting\Referensi\programControllerAdum@getDetail');
Route::get('/main/{tahun}/{status}/pengaturan/adum/program/getDataDetail/{id}', 'Budgeting\Referensi\programControllerAdum@getDataDetail');
Route::get('/main/{tahun}/{status}/pengaturan/adum/program/getCapaian/{id}', 'Budgeting\Referensi\programControllerAdum@getCapaian');
Route::post('/main/{tahun}/{status}/pengaturan/adum/program/delete', 'Budgeting\Referensi\programControllerAdum@delete');
Route::post('/main/{tahun}/{status}/pengaturan/adum/program/add/submit', 'Budgeting\Referensi\programControllerAdum@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/adum/program/edit/submit', 'Budgeting\Referensi\programControllerAdum@submitEdit');
Route::post('/main/{tahun}/{status}/pengaturan/adum/program/submitCapaian', 'Budgeting\Referensi\programControllerAdum@submitCapaian');
//KEGIATAN ADUM
Route::get('/main/{tahun}/{status}/referensi/adum/kegiatan/', 'Budgeting\Referensi\kegiatanControllerAdum@indexReferensi');
Route::get('/main/{tahun}/{status}/referensi/adum/kegiatan/getData', 'Budgeting\Referensi\kegiatanControllerAdum@getReferensi');
Route::get('/main/{tahun}/{status}/pengaturan/adum/kegiatan/getProgram/{id}', 'Budgeting\Referensi\kegiatanControllerAdum@getProgram');
Route::get('/main/{tahun}/{status}/pengaturan/adum/kegiatan/getData/{id}', 'Budgeting\Referensi\kegiatanControllerAdum@getDetail');
Route::post('/main/{tahun}/{status}/pengaturan/adum/kegiatan/delete', 'Budgeting\Referensi\kegiatanControllerAdum@delete');
Route::post('/main/{tahun}/{status}/pengaturan/adum/kegiatan/add/submit', 'Budgeting\Referensi\kegiatanControllerAdum@submitAdd');
Route::post('/main/{tahun}/{status}/pengaturan/adum/kegiatan/edit/submit', 'Budgeting\Referensi\kegiatanControllerAdum@submitEdit');
//SATUAN
Route::get('/main/{tahun}/{status}/pengaturan/satuan/getData', 'Budgeting\Referensi\satuanController@getData');


//------------------------------------------------------------------------------------------------------------------------
//EHARGA
//------------------------------------------------------------------------------------------------------------------------
Route::get('/harga/{tahun}', 'EHarga\mainController@index');
//USULAN
Route::get('/harga/{tahun}/usulan', 'EHarga\usulanController@index');
Route::get('/harga/{tahun}/usulan/getNotif', 'EHarga\usulanController@getNotif');
Route::get('/harga/{tahun}/usulan/getKategori/{length}', 'EHarga\usulanController@getKategori');
Route::get('/harga/{tahun}/usulan/getKategori_/{length}', 'EHarga\usulanController@getKategori_');
Route::get('/harga/{tahun}/usulan/getData', 'EHarga\usulanController@getUsulan');
Route::get('/harga/{tahun}/usulan/surat/{id}', 'EHarga\usulanController@cetak');
Route::get('/harga/{tahun}/usulan/getData/valid', 'EHarga\usulanController@getValid');
Route::get('/harga/{tahun}/usulan/getData/surat', 'EHarga\usulanController@getSurat');
Route::get('/harga/{tahun}/usulan/getData/detail/{id}', 'EHarga\usulanController@getDetail');
Route::get('/harga/{tahun}/usulan/getData/status/{post}', 'EHarga\usulanController@getStatus');
Route::get('/harga/{tahun}/usulan/getAlasan/{id}', 'EHarga\usulanController@getAlasan');
Route::get('/harga/{tahun}/usulan/getSuggest/{id}', 'EHarga\usulanController@getSugest');
Route::get('/harga/{tahun}/usulan/getSuggest_/{id}', 'EHarga\usulanController@getSugest_');
Route::get('/harga/{tahun}/usulan/getUbah/{id}', 'EHarga\usulanController@getUbah');
Route::get('/harga/{tahun}/usulan/surat', 'EHarga\usulanController@showSurat');
Route::get('/harga/{tahun}/usulan/getSurat', 'EHarga\usulanController@getDataSurat');
Route::get('/harga/{tahun}/usulan/pembahasan', 'EHarga\pembahasanController@index');
Route::get('/harga/{tahun}/usulan/pembahasan/getdata', 'EHarga\pembahasanController@getdata');
Route::get('/harga/{tahun}/usulan/pembahasan/detail/{id}', 'EHarga\pembahasanController@detail');

Route::post('/harga/{tahun}/usulan/submitUsulan', 'EHarga\usulanController@submitUsulan');
Route::post('/harga/{tahun}/usulan/submitUsulanUbah', 'EHarga\usulanController@submitUsulanUbah');
Route::post('/harga/{tahun}/usulan/submitTambahRekening', 'EHarga\usulanController@submitTambahRekening');
Route::post('/harga/{tahun}/usulan/submitUsulanMultiple', 'EHarga\usulanController@submitUsulanMultiple');
Route::post('/harga/{tahun}/usulan/actUsulan', 'EHarga\usulanController@actUsulan');
Route::post('/harga/{tahun}/usulan/posting', 'EHarga\usulanController@posting');
Route::post('/harga/{tahun}/usulan/submitAlasan', 'EHarga\usulanController@submitAlasan');
Route::post('/harga/{tahun}/usulan/submitDD', 'EHarga\usulanController@submitDD');
Route::post('/harga/{tahun}/usulan/updateDD', 'EHarga\usulanController@updateDD');
Route::post('/harga/{tahun}/usulan/ajukan', 'EHarga\usulanController@ajukan');
Route::post('/harga/{tahun}/usulan/acc', 'EHarga\usulanController@acc');
Route::post('/harga/{tahun}/usulan/grouping', 'EHarga\usulanController@grouping');
Route::post('/harga/{tahun}/usulan/hapus', 'EHarga\usulanController@delete');
Route::post('/harga/{tahun}/usulan/cancel', 'EHarga\usulanController@cancel');
Route::post('/harga/{tahun}/usulan/pembahasan/accept', 'EHarga\pembahasanController@acceptpembahasan');
Route::post('/harga/{tahun}/usulan/pembahasan/decline', 'EHarga\pembahasanController@rejectpembahasan');

//KOMPONEN
Route::get('/harga/{tahun}/komponen', 'EHarga\komponenController@index');
Route::get('/harga/{tahun}/_/komponen/getData/{kategori}', 'EHarga\komponenController@getReferensi');
Route::get('/harga/{tahun}/_/komponen/getuser/{komponen}', 'EHarga\komponenController@getuser');
Route::get('/harga/{tahun}/_/komponen/getrekening/{komponen}', 'EHarga\komponenController@getrekening');
Route::get('/harga/{tahun}/komponen/getkategori/{jenis}', 'EHarga\komponenController@getKategori');
Route::get('/harga/{tahun}/komponen/detail/{id}', 'EHarga\komponenController@detail');
Route::get('/harga/{tahun}/komponen/detail-rekom/{id}', 'EHarga\komponenController@detailrekom');
Route::post('/harga/{tahun}/komponen/kunci/{kunci}', 'EHarga\komponenController@kunci');
Route::post('/harga/{tahun}/komponen/hapus', 'EHarga\komponenController@delete');
Route::post('/harga/{tahun}/komponen/submit', 'EHarga\komponenController@submit');
Route::post('/harga/{tahun}/komponen/uploadHSPK', 'EHarga\komponenController@uploadHSPK');
Route::post('/harga/{tahun}/komponen/uploadASB', 'EHarga\komponenController@uploadASB');
Route::post('/harga/{tahun}/komponen-ubah/submit', 'EHarga\komponenController@submitubah');
Route::post('/harga/{tahun}/komponen-rekening/submit', 'EHarga\komponenController@submitrekom');
//MONITOR
Route::get('/harga/{tahun}/monitor', 'EHarga\monitorUsulanController@index');
Route::get('/harga/{tahun}/monitor/getData', 'EHarga\monitorUsulanController@getData');
Route::get('/harga/{tahun}/monitor/getData/{tipe}/{jenis}/{opd}/{posisi}', 'EHarga\monitorUsulanController@getFilter');
//KOMPONEN
//REFERENSI
Route::get('/main/{tahun}/{status}/referensi/komponen', 'EHarga\komponenController@referensi');
Route::get('/main/{tahun}/{status}/referensi/komponen/getData/{kategori}', 'EHarga\komponenController@getReferensi');
Route::get('/main/{tahun}/{status}/referensi/komponen/getuser/{komponen}', 'EHarga\komponenController@getuser');
Route::get('/main/{tahun}/{status}/referensi/komponen/getrekening/{komponen}', 'EHarga\komponenController@getrekening');
//REKENING
Route::get('/harga/{tahun}/rekening', 'Budgeting\Referensi\rekeningController@index');
Route::get('/harga/{tahun}/rekening/getData', 'Budgeting\Referensi\rekeningController@getData');
Route::get('/harga/{tahun}/rekening/getData/{id}', 'Budgeting\Referensi\rekeningController@getDetail');
Route::post('/harga/{tahun}/rekening/delete', 'Budgeting\Referensi\rekeningController@delete');
Route::post('/harga/{tahun}/rekening/add/submit', 'Budgeting\Referensi\rekeningController@submitAdd');
Route::post('/harga/{tahun}/rekening/edit/submit', 'Budgeting\Referensi\rekeningController@submitEdit');
Route::post('/harga/{tahun}/rekening/{kunci}', 'Budgeting\Referensi\rekeningController@kunci');
//------------------------------------------------------------------------------------------------------------------------
//STATISTIK
//------------------------------------------------------------------------------------------------------------------------
//RINGKASAN
Route::get('/main/{tahun}/{status}/ringkasan', 'Budgeting\ringkasanController@index');
//DOWNLOAD
Route::get('/main/{tahun}/{status}/download', 'Budgeting\downloadController@index');
Route::get('/main/{tahun}/{status}/download/rekaprincian', 'Budgeting\downloadController@rekapAll');
Route::get('/main/{tahun}/{status}/download/rekapbtl', 'Budgeting\downloadController@rekapBTL');
Route::get('/main/{tahun}/{status}/download/rekappendapatan', 'Budgeting\downloadController@rekapPendapatan');
Route::get('/main/{tahun}/{status}/download/rekapreses', 'Budgeting\downloadController@rekapReses');
Route::get('/main/{tahun}/{status}/download/rekaprincian/{id}', 'Budgeting\downloadController@rekapRincian');
Route::get('/main/{tahun}/{status}/download/musrenbang/{id}', 'Budgeting\downloadController@rekapMusrenbangSKPD');
Route::get('/main/{tahun}/{status}/download/reses/{id}', 'Budgeting\downloadController@rekapResesSKPD');
//VIEW
Route::get('/main/{tahun}/{status}/statistik', 'Budgeting\statistikController@index');
Route::get('/main/{tahun}/{status}/statistik/perangkat-daerah', 'Budgeting\statistikController@pd');
Route::get('/main/{tahun}/{status}/statistik/perangkat-daerah-input', 'Budgeting\statistikController@pdInput');
Route::get('/main/{tahun}/{status}/statistik/perangkat-daerah/detail/{id}', 'Budgeting\statistikController@pdDetail');
Route::get('/main/{tahun}/{status}/statistik/urusan', 'Budgeting\statistikController@urusan');
Route::get('/main/{tahun}/{status}/statistik/program', 'Budgeting\statistikController@program');
Route::get('/main/{tahun}/{status}/statistik/kegiatan', 'Budgeting\statistikController@kegiatan');
Route::get('/main/{tahun}/{status}/statistik/kegiatanadum', 'Budgeting\statistikController@kegiatanAdum');
Route::get('/main/{tahun}/{status}/statistik/kegiatanadum/detail/{id}', 'Budgeting\statistikController@kegiatanAdumDetail');
Route::get('/main/{tahun}/{status}/statistik/paket', 'Budgeting\statistikController@paket');
Route::get('/main/{tahun}/{status}/statistik/paket/detail/{id}', 'Budgeting\statistikController@paketDetail');
Route::get('/main/{tahun}/{status}/statistik/rekening', 'Budgeting\statistikController@rekening');
Route::get('/main/{tahun}/{status}/statistik/rekening/detail/{id}', 'Budgeting\statistikController@rekeningDetail');
Route::get('/main/{tahun}/{status}/statistik/indikator', 'Budgeting\statistikController@indikator');
Route::get('/main/{tahun}/{status}/statistik/musrenbang', 'Budgeting\statistikController@musrenbang');
Route::get('/main/{tahun}/{status}/statistik/tagging', 'Budgeting\statistikController@tagging');
Route::get('/main/{tahun}/{status}/statistik/pagu', 'Budgeting\statistikController@pagu');
Route::get('/main/{tahun}/{status}/statistik/pagu/detail/{id}', 'Budgeting\statistikController@paguDetail');
Route::get('/main/{tahun}/{status}/statistik/komponen', 'Budgeting\statistikController@komponen');
Route::get('/main/{tahun}/{status}/statistik/komponen/detail/{id}', 'Budgeting\statistikController@komponenDetail');
Route::get('/main/{tahun}/{status}/statistik/porsi-apbd', 'Budgeting\statistikController@porsiAPBD');
//API
Route::get('/main/{tahun}/{status}/statistik/perangkat-daerah/api', 'Budgeting\statistikController@pdApi');
Route::get('/main/{tahun}/{status}/statistik/perangkat-daerah-input/api', 'Budgeting\statistikController@pdApiInput');
Route::get('/main/{tahun}/{status}/statistik/perangkat-daerah/api/{id}', 'Budgeting\statistikController@pdApiDetail');
Route::get('/main/{tahun}/{status}/statistik/urusan/api', 'Budgeting\statistikController@urusanApi');
Route::get('/main/{tahun}/{status}/statistik/program/api', 'Budgeting\statistikController@programApi');
Route::get('/main/{tahun}/{status}/statistik/kegiatan/api', 'Budgeting\statistikController@kegiatanApi');
Route::get('/main/{tahun}/{status}/statistik/kegiatan/adum/api', 'Budgeting\statistikController@kegiatanApiAdum');
Route::get('/main/{tahun}/{status}/statistik/kegiatan/adum/detail/api/{id}', 'Budgeting\statistikController@kegiatanApiAdumDetail');
Route::get('/main/{tahun}/{status}/statistik/paket/api', 'Budgeting\statistikController@paketApi');
Route::get('/main/{tahun}/{status}/statistik/paket/api/{id}', 'Budgeting\statistikController@paketApiDetail');
Route::get('/main/{tahun}/{status}/statistik/rekening/api', 'Budgeting\statistikController@rekeningApi');
Route::get('/main/{tahun}/{status}/statistik/rekening/api/filter/{id}/{jenis}/{persentase}', 'Budgeting\statistikController@rekeningApiFilter');
Route::get('/main/{tahun}/{status}/statistik/rekening/api/{id}', 'Budgeting\statistikController@rekeningApiDetail');
Route::get('/main/{tahun}/{status}/statistik/indikator/{tipe}', 'Budgeting\statistikController@indikatorApi');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/renja', 'Budgeting\statistikController@renjaApi');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/reses', 'Budgeting\statistikController@resesApi');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/rw', 'Budgeting\statistikController@rwApi');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/karta', 'Budgeting\statistikController@kartaApi');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/pkk', 'Budgeting\statistikController@pkkApi');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/lpm', 'Budgeting\statistikController@lpmApi');
Route::get('/main/{tahun}/{status}/statistik/tagging/api', 'Budgeting\statistikController@taggingApi');
Route::get('/main/{tahun}/{status}/statistik/pagu/api', 'Budgeting\statistikController@paguApi');
Route::get('/main/{tahun}/{status}/statistik/pagu/api/{id}', 'Budgeting\statistikController@paguApiDetail');
Route::get('/main/{tahun}/{status}/statistik/komponen/api', 'Budgeting\statistikController@komponenApi');
Route::get('/main/{tahun}/{status}/statistik/komponen/api/{id}', 'Budgeting\statistikController@komponenApiDetail');
Route::get('/main/{tahun}/{status}/statistik/porsi-apbd/api', 'Budgeting\statistikController@porsiAPDBApi');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/renja/{pd}', 'Budgeting\statistikController@renjaDetail');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/reses/{pd}', 'Budgeting\statistikController@resesDetail');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/renja/getData/{pd}', 'Budgeting\statistikController@renjaDetailGetData');
Route::get('/main/{tahun}/{status}/statistik/musrenbang/reses/getData/{pd}', 'Budgeting\statistikController@resesDetailGetData');

Route::get('/real/{tahun}','realController@index');
Route::get('/real/{tahun}/getdata','realController@getdata');
Route::get('/real/{tahun}/transferoutput','realController@transferoutput');


//auto
Route::get('/main/{tahun}/{status}/trfrw/{kec}', 'Budgeting\usulanController@trfRW');
Route::get('/main/{tahun}/{status}/trfpkk/{kec}', 'Budgeting\usulanController@trfPKK');
Route::get('/main/{tahun}/{status}/trflpm/{kec}', 'Budgeting\usulanController@trfLPM');
Route::get('/main/{tahun}/{status}/trfkarta/{kec}', 'Budgeting\usulanController@trfKARTA');
Route::get('/main/{tahun}/{status}/validasiall', 'Budgeting\blController@validasiAll');
//AUTO - VIEW
Route::get('/auto/{tahun}/getfromsimda', 'realController@getfromsimda');
Route::get('/auto/{tahun}/progres/{param}/{kodeperubahan}', 'realController@progress');
Route::get('/auto/{tahun}/getStatus/{param}/{kodeperubahan}', 'realController@getStatus');
//AUTO-PROCESS
Route::get('/auto/{tahun}/trfUrusanFromSimda', 'realController@transferUrusanFromSimda');
Route::get('/auto/{tahun}/trfProgramFromSimda', 'realController@transferProgramFromSimda');
Route::get('/auto/{tahun}/trfRealisasiFromSimda', 'realController@getRealisasi');
Route::get('/auto/{tahun}/trfKegiatanFromSimda', 'realController@transferKegiatanFromSimda');
Route::get('/auto/{tahun}/trfBelanjaFromSimda/{kodeperubahan}/{skpd}', 'realController@transferBelanjaFromSimda');
Route::get('/auto/{tahun}/trfSubrincianFromSimda/{kodeperubahan}/{skpd}', 'realController@transferSubrincianFromSimda');
Route::get('/auto/{tahun}/trfRincianFromSimda/{kodeperubahan}/{skpd}', 'realController@transferRincianFromSimda');
Route::get('/auto/{tahun}/trfBTLFromSimda/{kodeperubahan}/{skpd}', 'realController@transferBTLFromSimda');
Route::get('/auto/{tahun}/trfPendapatanFromSimda/{kodeperubahan}/{skpd}', 'realController@transferPendapatanFromSimda');
Route::get('/auto/{tahun}/trfPembiayaanFromSimda/{kodeperubahan}/{skpd}', 'realController@transferPembiayaanFromSimda');
Route::get('/auto/transferuser/{tahunawal}/{tahunakhir}', 'realController@transferuser');
Route::get('/trfnamakomponen', 'realController@trfnamakomponen');
Route::get('/trfnamakomponenperubahan', 'realController@trfnamakomponenperubahan');
Route::get('/trfprogram/{tahun}/{kode}', 'realController@trfprogram');


//sirup api
Route::get('/main/{tahun}/{status}/api/sirup', 'apiController@apiSirup');

