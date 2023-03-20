<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('location')->group(function () {
    Route::post('get_province' , 'API\LocationController@ajax_province');
    Route::post('get_city' , 'API\LocationController@ajax_city');
    Route::post('get_kecamatan' , 'API\LocationController@ajax_kecamatan');
    Route::post('android_province' , 'API\LocationController@android_province');
    Route::post('android_city' , 'API\LocationController@android_city');
    Route::post('android_kecamatan' , 'API\LocationController@android_kecamatan');
    
});


//Login
Route::prefix('api')->group(function () {
    Route::get('appupdate' , 'API\AndroidAbsensiController@downloadnewvers');
    Route::post('gethelp' , 'API\AndroidAbsensiController@gethelp');
    Route::post('getvers' , 'API\AndroidAbsensiController@getvers');
    Route::post('login' , 'API\AndroidAbsensiController@login');
    Route::post('loginurl' , 'API\AndroidAbsensiController@login_url');
//Time In
    Route::post('check_in' , 'API\AndroidAbsensiController@CheckIn');
//Time Out
    Route::post('check_out' , 'API\AndroidAbsensiController@CheckOut');
//profile
    Route::post('profile' , 'API\AndroidAbsensiController@profiles')->name('user.Profile');
//getLocation
    Route::post('getAddress' , 'API\AndroidAbsensiController@getmyaddress')->name('user.getAddress');
//history_list
    Route::post('list_checkin', 'API\AndroidAbsensiController@getDailyAbsensi')->name('user.getDailyAbsensi');
//detail history
    Route::post('detail_checkin', 'API\AndroidAbsensiController@getDetailDailyAbsensi')->name('user.getDetailDailyAbsensi');
    Route::post('dtl_daily', 'API\AndroidAbsensiController@dtl_percard');
//time location
    Route::post('team_location', 'API\AndroidAbsensiController@teamLocation')->name('user.getTeamLocation');
    Route::post('myteam', 'API\AndroidAbsensiController@teamhistory');
//leaves
    Route::post('izin' , 'API\AndroidAbsensiController@savePermission')->name('user.getPermission');
    Route::post('leave' , 'API\AndroidAbsensiController@saveLeave')->name('user.getLeaves');
    Route::post('list_leave' , 'API\AndroidAbsensiController@listLeave')->name('user.listLeaves');
    Route::post('list_approval' , 'API\AndroidAbsensiController@listApproval');
    Route::post('leave_approve' , 'API\AndroidAbsensiController@leaveApproval');
    Route::post('leave_reject' , 'API\AndroidAbsensiController@leaveReject');    
    Route::post('show_dtl_leave' , 'API\AndroidAbsensiController@DetailApproval');    
    Route::post('upload_file' , 'API\AndroidAbsensiController@upload_file');
//show leave and permission
    Route::post('show_leaves' , 'API\AndroidAbsensiController@showleaves')->name('user.showLeaves');
});

Route::prefix('notif')->group(function () {
    Route::post('notif_settlement' , 'API\ProjectController@getNotif');
});

Route::prefix('malescron')->group(function () {
    Route::get('deadline/{all}', 'Cron\CronJobController@deadline')->where('all', '.*');
    Route::get('deadlinevendor/{all}', 'Cron\CronJobController@deadlinevendor')->where('all', '.*');
    Route::resource('cron', 'Cron\CronJobController');
});
