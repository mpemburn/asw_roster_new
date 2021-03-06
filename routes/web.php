<?php

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

Route::get('/', function () {
    return view('home');
});

Route::get('/test', 'TestController@show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// All Auth protected routes
Route::group(['middlewareGroups' => 'web'], static function () {

    Route::get('logout', [
        'middleware' => ['auth'],
        'uses' => 'Auth\LoginController@logout'
    ]);

    Route::get('profile/password', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@resetProfilePassword'
    ]);

    Route::post('profile/reset', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@setNewPassword'
    ]);

    Route::get('member', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@index'
    ]);
    Route::get('/member/covens', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@listCovens'
    ]);
    Route::get('/member/details', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@memberDetails'
    ]);
    Route::get('/member/details/{id}', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@memberDetails'
    ]);
    Route::get('/member/missing', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@missingDetails'
    ]);
    Route::get('/member/search', [
        'middleware' => ['auth'],
        'uses' => 'MembersController@memberSearch'
    ]);
    Route::get('/member/migrate', 'MembersController@migrate');
    Route::post('member/{id}/update', [
        'middleware' => ['auth'],
        'as' => 'member.update',
        'uses' => 'MembersController@update'
    ]);

    Route::get('/guild/manage/{guild_id}', [
        'middleware' => ['auth'],
        'uses' => 'GuildsController@manage'
    ]);
    Route::get('/guild/add', [
        'middleware' => ['auth'],
        'uses' => 'GuildsController@add'
    ]);
    Route::get('/guild/remove', [
        'middleware' => ['auth'],
        'uses' => 'GuildsController@remove'
    ]);

    /* Roles, Permissions, and Users */
    Route::group(['prefix' => 'admin'], function () {
        Route::any('roles', 'RolesController');
//        Route::any('permissions', 'PermissionsController');
//        Route::any('users', 'UsersController');
    });

    /* TODO: Create RBAC admin interface
    Route::get('acl', [
        'middleware' => ['auth'],
        'uses' => 'RbacController@index'
    ]);
    Route::get('rbac/set_leaders', [
        'middleware' => ['auth'],
        'uses' => 'RbacController@setLeadershipRoles'
    ]);
    Route::get('rbac/set_perms', [
        'middleware' => ['auth'],
        'uses' => 'RbacController@setRolePermissions'
    ]);
    */

});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
});
