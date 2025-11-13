<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SSOBrokerController;
use App\Http\Controllers\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Backend\KonsultasiSOP\BukuTamuKonsultasiController;
use App\Http\Controllers\Backend\KonsultasiSOP\DashboardKonsultasiController;
use App\Http\Controllers\Backend\KonsultasiSOP\JadwalKonsultasiController;
use App\Http\Controllers\Backend\KonsultasiSOP\KonsultasiOnlineController;
use App\Http\Controllers\Backend\ManajemenPengguna\PermissionsController;
use App\Http\Controllers\Backend\ManajemenPengguna\UserPermissionsController;
use App\Http\Controllers\Backend\ManajemenPengguna\UsersController;
use App\Http\Controllers\Backend\MonevSOP\DashboardMonevController;
use App\Http\Controllers\Backend\MonevSOP\FormMonevController;
use App\Http\Controllers\Backend\MonevSOP\InstrumenController;
use App\Http\Controllers\Backend\MonevSOP\MonevController;
use App\Http\Controllers\Backend\MonevSOP\PeriodeController;
use App\Http\Controllers\Backend\MonevSOP\ReportMonevController;
use App\Http\Controllers\Backend\Utilities\FileController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\SSOBrokerMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

### FRONTEND

Route::group([],function(){
    Route::get('/', [LandingController::class, 'index'])->name('/');
});

### END FRONTEND


### BACKEND

Route::get('backend/login', [SSOBrokerController::class, 'authenticateToSSO']);
Route::get('authenticateToSSO', [SSOBrokerController::class, 'authenticateToSSO']);
Route::get('authData/{authData}', [SSOBrokerController::class, 'authenticateToSSO']);
Route::get('/logout-sso', [SSOBrokerController::class, 'logoutSSO'])->name('Logout');
Route::get('logout/{sessionId}', [SSOBrokerController::class, 'logout']);
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


Route::get('report/monev-sop/cetak/{id}', [ReportMonevController::class, 'cetakReportSopPdfPublic'])->name('monev-sop.sop.report.cetak.public');

Route::get('backend/konsultasi-sop/konsultasi-sop-offline/buku-tamu/add', [BukuTamuKonsultasiController::class, 'add'])->name('konsultasi-sop-offline.buku-tamu.add');
Route::post('backend/konsultasi-sop/konsultasi-sop-offline/buku-tamu/store', [BukuTamuKonsultasiController::class, 'store'])->name('konsultasi-sop-offline.buku-tamu.store');

Route::group(['middleware' => [SSOBrokerMiddleware::class, CheckRole::class]], function () {

    Route::get('/file/view/{filepath}', [FileController::class, 'viewFile'])->name('file.view');

    Route::get('changeRole/{role}/{unit_id}', [SSOBrokerController::class, 'changeRole'])->name('changeRole');

    Route::prefix('backend')->group(function(){
        // Route::group(['middleware' => ['can:beranda']], function(){
            Route::get('beranda', [DashboardController::class, 'index'])->name('backend.beranda');
        // });

        Route::prefix('dashboard')->group(function(){
            Route::get('monev/sop-stats', [DashboardController::class, 'getMonevSopStats'])->name('dashboard.monev.sop.stats');
            Route::get('monev/bar-chart', [DashboardController::class, 'getBarChartMonevTahunan'])->name('dashboard.monev.sop.bar-chart');
            Route::get('monev/summary', [DashboardController::class, 'getMonevSopSummary'])->name('dashboard.monev.sop.summary');
            Route::get('monev/datatable', [DashboardController::class, 'datatableProgresPelaksanaSOP'])->name('dashboard.monev.sop.datatable-progres');

            Route::get('konsultasi/sop-stats', [DashboardController::class, 'getKonsultasiSopStats'])->name('dashboard.konsultasi.sop.stats');
            Route::get('konsultasi/api/list-jadwal/{unit_code}', [DashboardController::class, 'listJadwal'])->name('dashboard.konsultasi-sop-offline.jadwal.api.list-jadwal');
            Route::get('konsultasi/jadwal/stats', [DashboardController::class, 'getJadwalKonsultasiSopStats'])->name('dashboard.konsultasi-sop-offline.jadwal.stats');
        });

        Route::prefix('pengaturan')->group(function(){
            Route::prefix('manajemen-pengguna')->group(function(){
                Route::prefix('user')->group(function(){
                    Route::get('/', [UsersController::class, 'index'])->name('user.index');
                    Route::get('datatable', [UsersController::class, 'datatable'])->name('user.datatable');
                    Route::get('ajax-filter', [UsersController::class, 'ajaxShowFilter'])->name('user.ajax_filter');
                    Route::get('mapping/{user_role_id}', [UsersController::class, 'userMappingIndex'])->name('user.mapping');
                    Route::get('mapping/datatable/{user_role_id}', [UsersController::class, 'datatableMappingSOP'])->name('user.mapping.datatable');
                    Route::get('mapping/datatable-sop/{unit_code}', [UsersController::class, 'datatableSop'])->name('user.mapping.datatable-sop');
                    Route::post('mapping/store', [UsersController::class, 'storeUserMapping'])->name('user.mapping.store');
                    Route::post('mapping/delete/{id}', [UsersController::class, 'deleteMappingUser'])->name('user.mapping.delete');
                });

                Route::prefix('permission')->group(function(){
                    Route::get('/', [PermissionsController::class, 'index'])->name('permission.index');
                    Route::get('datatable', [PermissionsController::class, 'datatable'])->name('permission.datatable');
                    Route::get('edit/{id}', [PermissionsController::class, 'edit'])->name('permission.edit');
                    // ->middleware(['can:ekonomi-pertumbuhan']);
                    Route::get('ajax-show/{id}', [PermissionsController::class, 'ajaxShowDetail'])->name('permission.ajax_show');
                    Route::post('store', [PermissionsController::class, 'store'])->name('permission.store');
                    Route::post('delete/{id}', [PermissionsController::class, 'delete'])->name('permission.delete');
                });
                
                Route::prefix('user-permission')->group(function(){
                    Route::get('/', [UserPermissionsController::class, 'index'])->name('user-permission.index');
                    Route::post('store', [UserPermissionsController::class, 'store'])->name('user-permission.store');
                });
            });
        });

        Route::prefix('konsultasi-sop')->group(function(){
            Route::prefix('konsultasi-sop-online')->group(function(){
                Route::get('/', [KonsultasiOnlineController::class, 'index'])->name('konsultasi-sop-online.index');
                Route::get('datatable', [KonsultasiOnlineController::class, 'datatable'])->name('konsultasi-sop-online.datatable');
                Route::prefix('konsultasi')->group(function(){
                    Route::get('/{unit_code}', [KonsultasiOnlineController::class, 'indexKonsultasi'])->name('konsultasi-sop-online.konsultasi.index');
                    Route::get('ajax-filter', [KonsultasiOnlineController::class, 'ajaxShowFilter'])->name('konsultasi-sop-online.konsultasi.ajax_filter');
                    Route::post('delete/{id}', [KonsultasiOnlineController::class, 'deleteKonsultasi'])->name('konsultasi-sop-online.konsultasi.delete');
                    Route::post('update-status/{id}/{status}', [KonsultasiOnlineController::class, 'updateStatusKonsultasi'])->name('konsultasi-sop-online.konsultasi.update-status');
                    Route::get('datatable/{unit_code}', [KonsultasiOnlineController::class, 'datatableKonsultasi'])->name('konsultasi-sop-online.konsultasi.datatable');
                });


                Route::get('ajax-filter', [KonsultasiOnlineController::class, 'ajaxShowFilter'])->name('konsultasi-sop-online.konsultasi.ajax_filter');

                Route::prefix('konsultasi-room')->group(function(){
                    Route::get('/{unit_code}/{konsultasi_id?}', [KonsultasiOnlineController::class, 'indexDetailKonsultasi'])->name('konsultasi-sop-online.konsultasi-sop.room.index');
                    Route::post('store', [KonsultasiOnlineController::class, 'storeKonsultasi'])->name('konsultasi-sop-online.konsultasi-sop.room.store');
                });

                Route::prefix('dashboard')->group(function(){
                    Route::get('konsultasi-stats/{unit_code}', [DashboardKonsultasiController::class, 'getKonsultasiSopStats'])->name('dashboard.konsultasi-sop.stats');
                });
            });

            Route::prefix('konsultasi-sop-offline')->group(function(){
                Route::prefix('jadwal')->group(function(){
                    Route::get('/', [JadwalKonsultasiController::class, 'index'])->name('konsultasi-sop-offline.jadwal.index');
                    Route::get('datatable', [JadwalKonsultasiController::class, 'datatable'])->name('konsultasi-sop-offline.jadwal.datatable');
                    // Route::get('edit/{id}', [PeriodeController::class, 'edit'])->name('monev-periode.edit');
                    Route::get('ajax-show/{id}', [JadwalKonsultasiController::class, 'ajaxShowDetail'])->name('konsultasi-sop-offline.jadwal.ajax_show');
                    Route::post('store', [JadwalKonsultasiController::class, 'store'])->name('konsultasi-sop-offline.jadwal.store');
                    Route::post('delete/{id}', [JadwalKonsultasiController::class, 'delete'])->name('konsultasi-sop-offline.jadwal.delete');
                    Route::post('delete-calendar/{id}', [JadwalKonsultasiController::class, 'deleteCalendar'])->name('konsultasi-sop-offline.jadwal.delete-calendar');
                    Route::get('ajax-filter', [JadwalKonsultasiController::class, 'ajaxShowFilter'])->name('konsultasi-sop-offline.jadwal.ajax_filter');

                    Route::get('/api/list-jadwal', [JadwalKonsultasiController::class, 'listJadwal'])->name('konsultasi-sop-offline.jadwal.api.list-jadwal');
                });
                Route::prefix('buku-tamu')->group(function(){
                    Route::get('/', [BukuTamuKonsultasiController::class, 'index'])->name('konsultasi-sop-offline.buku-tamu.index');
                    Route::get('datatable', [BukuTamuKonsultasiController::class, 'datatable'])->name('konsultasi-sop-offline.buku-tamu.datatable');
                    Route::post('delete/{id}', [BukuTamuKonsultasiController::class, 'delete'])->name('konsultasi-sop-offline.buku-tamu.delete');
                    Route::get('ajax-filter', [BukuTamuKonsultasiController::class, 'ajaxShowFilter'])->name('konsultasi-sop-offline.buku-tamu.ajax_filter');
                });
            });
        });

        Route::prefix('monev-sop')->group(function(){
            Route::prefix('monev')->group(function(){
                Route::get('/', [MonevController::class, 'index'])->name('monev-sop.index');
                Route::get('datatable', [MonevController::class, 'datatable'])->name('monev-sop.datatable');

                Route::prefix('sop')->group(function(){
                    Route::get('/{unit_code}', [MonevController::class, 'indexSop'])->name('monev-sop.sop.index');
                    Route::get('datatable/{unit_code}', [MonevController::class, 'datatableSop'])->name('monev-sop.sop.datatable');
                    Route::post('delete/{id}', [MonevController::class, 'deleteSop'])->name('monev-sop.sop.delete');
                    Route::post('restore/{id}', [MonevController::class, 'restoreSop'])->name('monev-sop.sop.restore');
                    Route::get('ajax-show/{id}/{unit_code}', [MonevController::class, 'ajaxShowDetailSop'])->name('monev-sop.sop.ajax_show');
                    Route::post('store', [MonevController::class, 'storeSop'])->name('monev-sop.sop.store');
                });

                Route::prefix('form-monev')->group(function(){
                    Route::get('/{sop_id}', [FormMonevController::class, 'index'])->name('monev-sop.sop.form-monev.index');
                    Route::get('datatable/{sop_id}', [FormMonevController::class, 'datatableResultMonevSOPPeriode'])->name('monev-sop.sop.form-monev.datatable_result');
                    Route::prefix('f01')->group(function(){
                        Route::get('/{sop_id}', [FormMonevController::class, 'indexF01'])->name('monev-sop.sop.form-monev.f01.index');
                        Route::post('store', [FormMonevController::class, 'storeF01'])->name('monev-sop.sop.form-monev.f01.store');
                    });
                    Route::prefix('f02')->group(function(){
                        Route::get('/{sop_id}', [FormMonevController::class, 'indexF02'])->name('monev-sop.sop.form-monev.f02.index');
                        Route::post('store', [FormMonevController::class, 'storeF02'])->name('monev-sop.sop.form-monev.f02.store');
                    });
                });

                Route::prefix('dashboard')->group(function(){
                    Route::get('sop-stats/{unit_code}', [DashboardMonevController::class, 'getMonevSopStats'])->name('dashboard.monev-sop.stats');
                    Route::get('summary/{unit_code}', [DashboardMonevController::class, 'getMonevSopSummary'])->name('dashboard.monev-sop.summary');
                    Route::get('datatable/{unit_code}', [DashboardMonevController::class, 'datatableProgresPelaksanaSOP'])->name('dashboard.monev-sop.datatable-progres');
                });
            });

            Route::prefix('report')->group(function(){
                Route::get('show/{id}', [ReportMonevController::class, 'showReportSopPdf'])->name('monev-sop.sop.report.show');
                Route::get('cetak/{id}', [ReportMonevController::class, 'cetakReportSopPdf'])->name('monev-sop.sop.report.cetak');
                Route::post('cetak/unit/{id}', [ReportMonevController::class, 'cetakReportSopUnitPdf'])->name('monev-sop.sop.unit.report.cetak');
            });

            Route::prefix('periode')->group(function(){
                Route::get('/', [PeriodeController::class, 'index'])->name('monev-periode.index');
                Route::get('datatable', [PeriodeController::class, 'datatable'])->name('monev-periode.datatable');
                Route::get('edit/{id}', [PeriodeController::class, 'edit'])->name('monev-periode.edit');
                Route::get('ajax-show/{id}', [PeriodeController::class, 'ajaxShowDetail'])->name('monev-periode.ajax_show');
                Route::post('store', [PeriodeController::class, 'store'])->name('monev-periode.store');
                Route::post('delete/{id}', [PeriodeController::class, 'delete'])->name('monev-periode.delete');
                Route::get('ajax-filter', [PeriodeController::class, 'ajaxShowFilter'])->name('monev-periode.ajax_filter');
            });

            Route::prefix('instrumen-monev')->group(function(){

                Route::prefix('instrumen-form1')->group(function(){
                    Route::get('/', [InstrumenController::class, 'indexF01'])->name('instrumen-form1.index');
                    Route::get('datatable', [InstrumenController::class, 'datatableF01'])->name('instrumen-form1.datatable');
                    Route::get('ajax-show/{id}', [InstrumenController::class, 'ajaxShowDetailF01'])->name('instrumen-form1.ajax_show');
                    Route::post('store', [InstrumenController::class, 'storeF01'])->name('instrumen-form1.store');
                    Route::post('delete/{id}', [InstrumenController::class, 'deleteF01'])->name('instrumen-form1.delete');
                    Route::get('ajax-filter', [InstrumenController::class, 'ajaxShowFilterF01'])->name('instrumen-form1.ajax_filter');
                });

                Route::prefix('instrumen-form2')->group(function(){
                    Route::get('/', [InstrumenController::class, 'indexF02'])->name('instrumen-form2.index');
                    Route::get('datatable', [InstrumenController::class, 'datatableF02'])->name('instrumen-form2.datatable');
                    Route::get('ajax-show/{id}', [InstrumenController::class, 'ajaxShowDetailF02'])->name('instrumen-form2.ajax_show');
                    Route::post('store', [InstrumenController::class, 'storeF02'])->name('instrumen-form2.store');
                    Route::post('delete/{id}', [InstrumenController::class, 'deleteF02'])->name('instrumen-form2.delete');
                    Route::get('ajax-filter', [InstrumenController::class, 'ajaxShowFilterF02'])->name('instrumen-form2.ajax_filter');
                });
            });
            
        });
    });
});

### END BACKEND