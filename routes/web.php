<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');


Route::get('dashboard/birthday/{for}/{id}/{type}', [App\Http\Controllers\DashboardController::class, 'birthdayList'])->name('dashboard.birthday')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin,Followup_Admin');

Route::get('dashboard/zone-list/{id}', [App\Http\Controllers\DashboardController::class, 'zoneList'])->name('dashboard.zone-list')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin');
Route::get('dashboard/zone-detail/{id}', [App\Http\Controllers\DashboardController::class, 'zoneDetail'])->name('dashboard.zone-detail')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin');
Route::get('dashboard/sabha-list/{id}', [App\Http\Controllers\DashboardController::class, 'sabhaList'])->name('dashboard.sabha-list')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin');
Route::get('dashboard/sabha-detail/{id}', [App\Http\Controllers\DashboardController::class, 'sabhaDetail'])->name('dashboard.sabha-detail')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin');
Route::get('dashboard/group-list/{id}', [App\Http\Controllers\DashboardController::class, 'groupList'])->name('dashboard.group-list')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');
Route::get('dashboard/group-detail/{id}', [App\Http\Controllers\DashboardController::class, 'groupDetail'])->name('dashboard.group-detail')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');
Route::get('dashboard/followupkaryakarta-list/{id}', [App\Http\Controllers\DashboardController::class, 'followupkaryakartaList'])->name('dashboard.followupkaryakarta-list')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin');
Route::get('dashboard/followupkaryakarta-detail/{id}', [App\Http\Controllers\DashboardController::class, 'followupkaryakartaDetail'])->name('dashboard.followupkaryakarta-detail')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin');


Route::middleware(['auth'])->group(function () {
    Route::get('profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::post('profile/update', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('profile.update');
});

Route::get('admins/ajax-autocomplete-search', [App\Http\Controllers\AdminController::class, 'ajaxAutocompleteSearch'])->name('admins.ajax-autocomplete-search')->middleware(['auth']);
Route::resource('admins', App\Http\Controllers\AdminController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin');

Route::resource('pradeshs', App\Http\Controllers\PradeshController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin');

Route::get('zones/ajax-autocomplete-search', [App\Http\Controllers\ZoneController::class, 'ajaxAutocompleteSearch'])->name('zones.ajax-autocomplete-search')->middleware(['auth']);
Route::resource('zones', App\Http\Controllers\ZoneController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin');

Route::get('sabhas/ajax-autocomplete-search', [App\Http\Controllers\SabhaController::class, 'ajaxAutocompleteSearch'])->name('sabhas.ajax-autocomplete-search')->middleware(['auth']);
Route::resource('sabhas', App\Http\Controllers\SabhaController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');

Route::resource('action-logs', App\Http\Controllers\AdminActionLogController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin');
Route::resource('login-logs', App\Http\Controllers\AdminLoginLogController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin');
Route::resource('cron-logs', App\Http\Controllers\CronLogsController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin');

Route::get('members/ajax-autocomplete-search', [App\Http\Controllers\MemberController::class, 'ajaxAutocompleteSearch'])->name('members.ajax-autocomplete-search')->middleware(['auth']);
Route::get('members/suject-list-html', [App\Http\Controllers\MemberController::class, 'subjectListHtml'])->name('members.suject-list-html');
Route::resource('members', App\Http\Controllers\MemberController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin,Followup_Admin');


Route::get('attendances/vaktaAddEdit/{id}', [App\Http\Controllers\AttendanceController::class, 'vaktaAddEdit'])->name('attendances.vaktaAddEdit')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');
Route::put('attendances/vaktaStoreUpdate', [App\Http\Controllers\AttendanceController::class, 'vaktaStoreUpdate'])->name('attendances.vaktaStoreUpdate')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');
Route::post('attendances/sabhaCancel', [App\Http\Controllers\AttendanceController::class, 'sabhaCancel'])->name('attendances.sabhaCancel')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');
Route::post('attendances/attendanceSingleMember', [App\Http\Controllers\AttendanceController::class, 'attendanceSingleMember'])->name('attendances.attendanceSingleMember')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin,Followup_Admin');
Route::resource('attendances', App\Http\Controllers\AttendanceController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin,Followup_Admin');
Route::resource('attenders', App\Http\Controllers\AttenderController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin,Group_Admin,Followup_Admin');


Route::get('groups/ajax-autocomplete-search', [App\Http\Controllers\GroupController::class, 'ajaxAutocompleteSearch'])->name('groups.ajax-autocomplete-search')->middleware(['auth']);
Route::resource('groups', App\Http\Controllers\GroupController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');

Route::resource('countries', App\Http\Controllers\CountryController::class);
Route::resource('states', App\Http\Controllers\StateController::class);
Route::resource('cities', App\Http\Controllers\CityController::class);


Route::get('/create-sabha-attendance', [App\Http\Controllers\CronjobController::class, 'createSabhaAttendance'])->name('create-sabha-attendance');
Route::get('/joining-sabha-date', [App\Http\Controllers\CronjobController::class, 'joiningDate'])->name('joining-sabha-date');
Route::get('/update-member-attending-sabha-status', [App\Http\Controllers\CronjobController::class, 'updateMemberAttendingSabhaStatus'])->name('update-member-attending-sabha-status');
Route::get('/clear-cache-all', function() {
    Artisan::call('cache:clear');
});

Route::get('tagsMaster/ajax-autocomplete-search', [App\Http\Controllers\TagMasterController::class, 'ajaxAutocompleteSearch'])->name('tagsMaster.ajax-autocomplete-search')->middleware(['auth']);
Route::resource('tagsMaster', App\Http\Controllers\TagMasterController::class)->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin');

Route::get('reports/attendance', [App\Http\Controllers\ReportsController::class, 'attendanceReport'])->name('reports.attendance')->middleware('admintype:Super_Admin,Country_Admin,State_Admin,Pradesh_Admin,Zone_Admin,Sabha_Admin');