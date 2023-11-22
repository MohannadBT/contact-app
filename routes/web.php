<?php


use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\controllers\WelcomeController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactNoteController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\PasswordController;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class);
    Route::get('/settings/profile-information', ProfileController::class)->name('user-profile-information.edit');
    Route::get('/settings/password', PasswordController::class)->name('user-password.edit');
    Route::get('/sample-contacts', function () {
        return response()->download(Storage::path('contacts-sample.csv'));
    })->name('sample-contacts');
    Route::get('/contacts/import', [ImportContactController::class, 'create'])->name('contacts.import.create');
    Route::post('/contacts/import', [ImportContactController::class, 'store'])->name('contacts.import.store');
    Route::resource('/contacts', ContactController::class);
    Route::delete('/contacts/{contact}/restore', [ContactController::class, 'restore'])
        ->name('contacts.restore')
        ->withTrashed();
    Route::delete('/contacts/{contact}/force-delete', [ContactController::class, 'forceDelete'])
        ->name('contacts.force-delete')
        ->withTrashed();
    Route::resource('/companies', CompanyController::class);
    Route::delete('/companies/{company}/restore', [CompanyController::class, 'restore'])
        ->name('companies.restore')
        ->withTrashed();
    Route::delete('/companies/{company}/force-delete', [CompanyController::class, 'forceDelete'])
        ->name('companies.force-delete')
        ->withTrashed();
    Route::resources([
        '/tags' => TagController::class ,
        '/tasks' => TaskController::class
    ]);
    Route:: resource('/contacts.notes', ContactNoteController::class)->shallow();
    /* how to change the parameters for a route resource */
    Route::resource('/activities', ActivityController::class)->parameters([
        'activities' => 'active'
    ]);
});

Route::get('/eagerload-multipe', function () {
    $users = User::with(['companies', 'contacts'])->get();

    foreach ($users as $user) {
        echo $user->name . ": ";
        echo $user->companies->count() . " companies, " . $user->contacts->count() . " contacts<br>";
    }
});
Route::get('/eagerload-nested', function () {
    $users = User::with(['companies', 'companies.contacts'])->get();
    foreach ($users as $user) {
        echo $user->name . "<br />";
        foreach ($user->companies as $company) {
            echo $company->name . " has " . $company->contacts->count() . " contacts<br />";
        }
        echo "<br />";
    }
});
Route::get('/eagerload-constraint', function () {
    $users = User::with(['companies' => function ($query) {
        $query->where('email', 'like', '%.org');
    }])->get();
    foreach ($users as $user) {
        echo $user->name . "<br />";
        foreach ($user->companies as $company) {
            echo $company->email . "<br />";
        }
        echo "<br />";
    }
});
Route::get('/eagerload-lazy', function () {
    $users = User::get();
    $users->load(['companies' => function ($query) {
        $query->orderBy('name');
    }]);
    foreach ($users as $user) {
        echo $user->name . "<br />";
        foreach ($user->companies as $company) {
            echo $company->name . "<br />";
        }
        echo "<br />";
    }
});
Route::get('/eagerload-default', function () {
    $users = User::get();
    // $users = User::without('contacts', 'companies')->get();
    foreach ($users as $user) {
        echo $user->name . "<br />";
        foreach ($user->companies as $company) {
            echo $company->email . "<br />";
        }
        echo "<br />";
    }
});
Route::get('/count-models', function () {
    // $users = User::select(['name', 'email'])->withCount([
    //     'contacts as contacts_number',
    //     'companies as companies_count_end_with_gmail' => function ($query) {
    //         $query->where('email', 'like', '%@gmail.com');
    //     }
    // ])->get();

    // foreach ($users as $user) {
    //     echo $user->name . "<br />";
    //     echo $user->companies_count_end_with_gmail . " companies<br />";
    //     echo $user->contacts_number . " contacts<br />";
    //     echo "<br />";
    // }
    $users = User::get();
    $users->loadCount(['companies' => function ($query) {
        $query->where('email', 'like', '%@gmail.com');
    }]);
    foreach ($users as $user) {
        echo $user->name . "<br />";
        echo $user->companies_count . " companies<br />";
        echo "<br />";
    }
});
/* To select some of the methods from a resource */
// Route::resource('/activities', ActivityController::class)->only([ // ->except(['index' , 'show'])  /* to show the same result */
//     'create', 'store', 'update', 'edit', 'destroy'
// ]);

/* To change the route naming for the methods */
// Route::resource('/activities', ActivityController::class)->names([
//     'index' => 'activities.all',
//     'create' => 'activities.view',
// ]);




//grouping routers if we have the same controller
// Route::controller(ContactController::class)->group(function() {
//     Route::get('/contacts', 'index')->name('contacts.index');

//     Route::get('/contacts/create', 'create')->name('contacts.create');

//     Route::get('/contacts/{id}', 'show')->name('contacts.show',1)->whereNumber('id'); //also can use: ->where('id', '[0-9]+')
// });

// another way is by grouping it with the also the name function
// Route::controller(ContactController::class)->name('contacts.')->group(function() {
//     Route::get('/contacts', 'index')->name('index');

//     Route::get('/contacts/create', 'create')->name('create');

//     Route::get('/contacts/{id}', 'show')->name('show',1)->whereNumber('id');
// });

Route::get('/companies/{name?}', function ($name = null) {

    if($name){
        return "company ". $name;

    }else{
        return "All companies";
    }

})->whereAlpha('name'); //also can use: ->where('name', '[a-zA-Z]+')