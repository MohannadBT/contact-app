<?php

use App\Http\Controllers\ContactController;
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
    return view('welcome');
});

Route::fallback(function () {
        return view('welcome');
});

// Route::prefix('admin')->group(function () {

    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    
    Route::get('/contacts/creat', [ContactController::class, 'create'])->name('contacts.create');

    // Route::get('/contacts/{id}', [ContactController::class 'show'])->name('contacts.show', 1)->whereNumber('id'); //also can use: ->where('id', '[0-9]+')
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show',1)->whereNumber('id');
    // });

Route::get('/companies/{name?}', function ($name = null) {

    if($name){
        return "company ". $name;

    }else{
        return "All companies";
    }

})->whereAlpha('name'); //also can use: ->where('name', '[a-zA-Z]+')
