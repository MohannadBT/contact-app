<?php


use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactNoteController;
use App\Http\controllers\WelcomeController;
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

Route::get('/', WelcomeController::class);

Route::fallback(WelcomeController::class);

Route::resource('/contacts', ContactController::class);
Route::delete('/contacts/{contact}/restore', [ContactController::class, 'restore'])
    ->name('contacts.restore')
    ->withTrashed();
Route::delete('/contacts/{contact}/force-delete', [ContactController::class, 'forceDelete'])
    ->name('contacts.force-delete')
    ->withTrashed();
Route::resource('/companies', CompanyController::class);
Route::resources([
    '/tags' => TagController::class ,
    '/tasks' => TaskController::class
]);
Route:: resource('/contacts.notes', ContactNoteController::class)->shallow();

/* To select soem of the methods from a resource */
// Route::resource('/activities', ActivityController::class)->only([ // ->except(['index' , 'show'])  /* to shhow the same resoulte */
//     'create', 'store', 'update', 'edit', 'destroy'
// ]);

/* To change the route naming for the methods */
// Route::resource('/activities', ActivityController::class)->names([
//     'index' => 'activities.all',
//     'create' => 'activities.view',
// ]);

/* how to change the parameters for a route resource */
Route::resource('/activities', ActivityController::class)->parameters([
    'activities' => 'active'
]);


//grouping routers if we have the same countroller
// Route::controller(ContactController::class)->group(function() {
//     Route::get('/contacts', 'index')->name('contacts.index');

//     Route::get('/contacts/creat', 'create')->name('contacts.create');

//     Route::get('/contacts/{id}', 'show')->name('contacts.show',1)->whereNumber('id'); //also can use: ->where('id', '[0-9]+')
// });

// another way is by grouping it with the also the name finction
// Route::controller(ContactController::class)->name('contacts.')->group(function() {
//     Route::get('/contacts', 'index')->name('index');

//     Route::get('/contacts/creat', 'create')->name('create');

//     Route::get('/contacts/{id}', 'show')->name('show',1)->whereNumber('id');
// });

Route::get('/companies/{name?}', function ($name = null) {

    if($name){
        return "company ". $name;

    }else{
        return "All companies";
    }

})->whereAlpha('name'); //also can use: ->where('name', '[a-zA-Z]+')