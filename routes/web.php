<?php


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

/**
 * All route in this file using attributes of @method App\Providers\RouteServiceProvider mapWebRoutes()
 * Route::get, post, put return a Route so can add attributes after call
 * Route::middleware, namespace, prefix, domain ->  @method Illuminate\Routing\Router __call() create
 * -> @method static \Illuminate\Routing\RouteRegistrar s
 * @method static \Illuminate\Routing\RouteRegistrar get(string $uri, \Closure | array | string | null $action = null)
 */

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/**
 * Route#[prefix, middleware, namespace]R
 * @uses Facades\Facade __call()
 * @see  \Illuminate\Support\Facades\Facade attributes()
 *
 */


Route::get('login/facebook', 'Auth\LoginController@redirectToProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('login/facebook', 'Auth\LoginController@redirectToProvider');
Route::get('login/check/{token}', 'Auth\LoginController@checkToken');
Route::get('index', function () {
    return 'awd';
});


Route::get('/', function (Request $request) {
    return view('welcome') ;
});

Route::prefix('builder_then_get')->middleware('test')
    ->get('/test', function () {

    });

Route::prefix('builder_then_group')->middleware('test')
    ->group(function () {
        Route::get('/test', function () {

        });
    });


Route::get('/after_get', function () {
    return view('welcome');
})->prefix('prefix')
    ->middleware('auth');


Route::group(['prefix' => 'posts'], function () {
    Route::get('/{id}', function ($id) {
        return $id;
    });
});


//php artisan make:controller PhotoController --resource --model=Photo
//php artisan make:controller API/PhotoController --api


/**
 */
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
