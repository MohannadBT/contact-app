<?php

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

function getContacts (){
    return [
        1 => ['name' => 'Name 1', 'phone' => '1234567890'],
        2 => ['name' => 'Name 2', 'phone' => '2345678901'],
        3 => ['name' => 'Name 3', 'phone' => '3456789012'],
    ];
}

Route::get('/', function () {
    return view('welcome');
});

Route::fallback(function () {
        return view('welcome');
});

Route::prefix('admin')->group(function () {

    Route::get('/contacts', function () {
        
        $contacts = getContacts();

        return view('contacts.index', compact('contacts')); // compact() is the same as ['contacts' => $contacts] 
    })->name('contacts.index');
    
    Route::get('/contacts/creat', function () {
        return view('contacts.create');
    })->name('contacts.create');

    Route::get('/contacts/{id}', function ($id) {
        $contacts = getContacts();

        abort_if(!isset($contacts[$id]), 404);
        // abort_unless(isset($contacts[$id]), 404); the same as the one above but u change the condition

        $contact = $contacts[$id];
        return view('contacts.show')->with('contact', $contact); // u can send more than 1 value by chaining it like this : ->with()->with()...
    })->name('contacts.show', 1)->whereNumber('id'); //also can use: ->where('id', '[0-9]+')
});

Route::get('/companies/{name?}', function ($name = null) {

    if($name){
        return "company ". $name;

    }else{
        return "All companies";
    }

})->whereAlpha('name'); //also can use: ->where('name', '[a-zA-Z]+')
