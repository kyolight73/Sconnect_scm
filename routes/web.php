<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostInfoController;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    /* ROUTE FOR DEPARTMENT */
    Route::get('/department', 'DepartmentController@index')->middleware('can:department');
    Route::post('/department/add', 'DepartmentController@addDepartment');
    Route::post('/department/addsub', 'DepartmentController@addSubDepartment');
    Route::post('/department/update', 'DepartmentController@updateDepartment');
    Route::get('/department/delete-{id}', 'DepartmentController@deleteDepartment');
    Route::get('/department/assign-manager/{dept_id}/{user_id}', 'DepartmentController@assignManager');
    Route::get('/department/list-for-transfer/{staff_id}', 'DepartmentController@loadDeptTreeForTransfer');

    /* ROUTE FOR USER */
    Route::get('/staff', 'StaffController@index')->middleware('can:staff');
    Route::post('/staff/add', 'StaffController@addStaff');
    Route::post('/staff/add-in-dept', 'StaffController@addStaffInDept');
    Route::post('/staff/update-in-dept', 'StaffController@updateStaffInDept');
    Route::get('/staff/load-staff/{dept_id}', 'StaffController@loadStaffList');
    Route::get('/staff/assign-manager/{dept_id}/{user_id}', 'StaffController@assignManager');
    Route::get('/staff/load-staff-info/{staff_id}', 'StaffController@loadStaffInfo');
    Route::get('/staff/transfer/{staff_id}/to-dept/{new_dept_id}', 'StaffController@transferToDepartment');
    Route::get('/staff/status/{staff_id}/{new_status}', 'StaffController@updateStaffStatus');
    Route::get('/manager/status/{staff_id}/{new_status}', 'StaffController@updateManagerStatus');
    Route::get('/staff/get-delete-staff/{staff_id}', 'StaffController@getDeleteStaffInfo');
    Route::get('/staff/delete-staff/{staff_id}', 'StaffController@deleteStaff');

    /* ROUTE FOR GENERAL SETTING */
    Route::get('/setting', 'SettingController@index')->middleware('can:setting');
    Route::post('/setting/add-prefix', 'SettingController@addDeptPrefix');
    Route::post('/setting/save-prefix', 'SettingController@saveDeptPrefix');
    Route::get('/setting/delete-prefix-{id}', 'SettingController@deleteDeptPrefix');
    Route::post('/setting/add-title', 'SettingController@addTitle');
    Route::post('/setting/save-title', 'SettingController@saveTitle');
    Route::get('/setting/delete-title-{id}', 'SettingController@deleteTitle');

    /* ROUTE FOR CONFIG */
    Route::get('/config', 'ConfigController@index');
    Route::post('facebook_access_token', 'ConfigController@facebook_access_token')->name('config.facebook_access_token');

    /* ROUTE FOR MEDIA */
    Route::get('/platform', 'PlatformController@index')->middleware('can:platform');
    Route::post('/platform/save', 'PlatformController@savePlatform');
    Route::get('/platform/delete/{id}', 'PlatformController@deletePlatform');
    Route::get('/origin-product', 'OriginController@index')->middleware('can:origin-product');
    Route::post('/origin-product/save-folder', 'OriginController@saveFolder');
    Route::post('/origin-product/save-file', 'OriginController@saveFile');
    Route::get('/origin-product/delete-folder/{id}', 'OriginController@deleteFolder');
    Route::get('/origin-product/delete-file/{id}', 'OriginController@deleteFile');

    Route::get('/topic', 'TopicController@index')->middleware('can:topic');
    Route::post('/topic/save', 'TopicController@saveTopic');
    Route::get('/topic/delete/{id}', 'TopicController@deleteTopic');

    Route::get('/channel', 'ChannelController@index')->middleware('can:channel');
    Route::post('/channel/save', 'ChannelController@saveChannel');
    Route::get('/channel/delete', 'ChannelController@deleteChannel');
    Route::get('/channel-type', 'ChannelController@channelType')->middleware('can:channel-type');
    Route::post('/channel-type/save', 'ChannelController@saveChannelType');
    Route::get('/channel-type/delete/{ct_id}', 'ChannelController@deleteChannelType');
    Route::get('/channel/collect/{channel_id}', 'ChannelController@collectChannelInfo');

    Route::get('/video', 'VideoController@index')->middleware('can:video');
    Route::get('/video/detail', 'VideoController@detail');
    Route::get('/video-file/load/{video_id}/{current_folder_id}/{current_dept_id}', 'VideoController@loadFileForVideo');
    Route::get('/video-file/assign/{video_id}/{file_id}', 'VideoController@assignFileForVideo');

    //Fanpage
    Route::resource('fanpage', FanpageController::class);
    Route::resource('post_info', PostInfoController::class);
    Route::delete('delete/{post_info}', 'PostInfoController@delete')->name('post_info.delete');
    // Route::get('/fanpage', 'FanpageController@index')->name('fanpage.index');
    Route::get('get_api_facebook_pages', 'FanpageController@get_api_facebook_pages')->name('api.face');
    Route::get('get_api_all_post', 'FanpageController@get_api_all_post')->name('api.get_api_all_post');
    Route::get('show_post_info/{fanpage}', 'FanpageController@show_post_info')->name('fanpage.show_post_info');
    Route::post('create_post_info/{fanpage}', 'FanpageController@create_post_info')->name('fanpage.create_post_info');
    Route::post('add_post_api_from_to/{fanpage}', 'FanpageController@add_post_api_from_to')->name('fanpage.add_post_api_from_to');
    Route::get('page_insight', 'FanpageController@page_insight')->name('fanpage.page_insight');

    // Group
    Route::resource('group', GroupController::class);
    Route::get('get_api_all_group', 'GroupController@get_api_all_group')->name('group.get_api_all_group');
    Route::get('group_insight', 'GroupController@group_insight')->name('group.group_insight');

    // Route::get('page-information/{id}', 'DepartmentController@deleteDepartment');

    Route::resource('shortlink', ShortlinkController::class)->middleware('can:shortlink');
    // Shortlink Bitly
    Route::resource('shortlink', ShortlinkController::class);
    Route::get('get_update_all_link', 'ShortlinkController@get_update_all_link')->name('shortlink.get_update_all_link');
    Route::post('create_bitly_api', 'ShortlinkController@create_bitly_api')->name('shortlink.create_bitly_api');
    Route::post('get_newest_link', 'ShortlinkController@get_newest_link')->name('shortlink.get_newest_link');
    Route::post('create_bitly_link_api', 'ShortlinkController@create_bitly_link_api')->name('shortlink.create_bitly_link_api');
    Route::get('shortlink_insight', 'ShortlinkController@shortlink_insight')->name('shortlink.shortlink_insight');
    Route::get('shortlink_country_group_insight', 'ShortlinkController@shortlink_country_group_insight')->name('shortlink.shortlink_country_group_insight');
    Route::get('shortlink_record', 'ShortlinkController@shortlink_record')->name('shortlink.shortlink_record');
    Route::get('shortlink_record_index', 'ShortlinkController@shortlink_record_index')->name('shortlink.shortlink_record_index');

    Route::get('/promotion', 'PromotionController@index')->middleware('can:promotion');
    Route::get('/promotion/get-ticket-by-id/{id}', 'TicketController@getTicket');
    Route::match(['get', 'post'], '/ticket/save', 'TicketController@saveTicket');
    Route::match(['get', 'post'], '/comment/save', 'TicketController@saveComment');
    Route::get('/promote/tab/{tab_id}', function ($tab_id) {
        session(['tab_id' => $tab_id]);
    });
    Route::get('/dl/{file_name}', function ($file_name) {
        return \App\Utils::download('/download/' . $file_name, $file_name);
    });


    /* ROUTE FOR AUTHENTICATION */
    Route::match(['get', 'post'], '/login', 'Auth\LoginController@login')->name('login');
    Route::match(['get', 'post'], '/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/auth/google', 'Auth\LoginController@redirectToGoogle')->name('login.google');
    Route::get('/auth/google-prompt', 'Auth\LoginController@redirectToGooglePrompt')->name('login.google.prompt');
    Route::get('/auth/google/callback', 'Auth\LoginController@handleGoogleCallback');

    /* BITRIX24 - IT - SUPPORT */
    Route::match(['get', 'post'], '/ticket24', 'Ticket24\Ticket24Controller@home')->name('ticket24.index');
    Route::match(['get', 'post'], '/ticket24/setting', 'Ticket24\Ticket24Controller@setting')->name('ticket24.setting');


    // Route::get('/shortlink', 'ShortlinkController@index')->name('mkt.shortlink');

    // Roles
    Route::get('/roles', 'RoleController@index')->name('roles')->middleware('can:roles');
    Route::post('/roles/add', 'RoleController@add')->name('roles.add');
    Route::post('/roles/change-{id}', 'RoleController@change')->name('roles.change');
    Route::get('/roles/edit/{id}', 'RoleController@edit')->name('roles.edit');
    Route::post('/roles/update/{id}', 'RoleController@update')->name('roles.update');
    Route::get('/roles/delete-{id}', 'RoleController@delete')->name('roles.delete');

    // Permissions
    Route::get('/permission/create', 'PermissionController@create')->name('permissions.create');
    Route::post('/store', 'PermissionController@store')->name('permissions.store');
});

//Auth::routes();


/*




*/
