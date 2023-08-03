<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Display\DisplayController;
use App\Http\Controllers\Manages\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Manages\TraineeController;
use App\Http\Controllers\Manages\CoursesController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DisplayController::class, 'welcome'])->name('welcome');

// login and register 
Route::prefix('Auth')->name('Auth.')->group(function () {

    Route::get('register/', [RegisterController::class, 'getRegister'])->name('getRegister');

    Route::post('register/', [RegisterController::class, 'postRegister'])->name('postRegister');

    Route::get('login/', [LoginController::class, 'getLogin'])->name('getLogin');

    Route::post('login/', [LoginController::class, 'postLogin'])->name('postLogin');

    Route::post('logout/', [LogoutController::class, 'logout'])->name('logout');
});

//bảo mật check status
Route::middleware(['auth', 'checkUserStatus'])->group(function () {
    
    Route::prefix('All')->name('All.')->group(function () {
        // Route index by role
        Route::group(['prefix' => 'InRole'], function () {

            Route::get('indexA/', [DisplayController::class, 'indexAdmin'])->middleware('checkRole:admin')->name('index.admin');

            Route::get('indexTing/', [DisplayController::class, 'indexTraining'])->middleware('checkRole:training')->name('index.training');

            Route::get('indexTer/', [DisplayController::class, 'indexTrainer'])->middleware('checkRole:trainer')->name('index.trainer');
        });

        Route::get('dashboard/', [DisplayController::class, 'dashboard'])->middleware('checkRole:admin')->name('Dashboard');

        Route::get('notifications/', [DisplayController::class, 'notifications'])->name('notifications');

        Route::get('user/', [DisplayController::class, 'user'])->name('user');
    });

    Route::prefix('Users')->name('Users.')->group(function () {
        // User Management
        Route::get('/users', [UserController::class, 'index'])->middleware('checkRole:admin')->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])->middleware('checkRole:admin')->name('users.create');

        Route::post('/users', [UserController::class, 'postCreate'])->middleware('checkRole:admin')->name('users.postCreate');

        // Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        // Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'delete'])->middleware('checkRole:admin')->name('users.delete');

        Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('checkRole:admin')->name('users.toggleStatus');
    });

    Route::prefix('managetrainee')->name('managetrainee.')->group(function () {
        // Route for displaying the form to create a new trainee account
        Route::get('/trainees/create', [TraineeController::class, 'create'])->middleware('checkRole:admin,training')->name('trainees.create');

        // Route for storing the newly created trainee account data in the database
        Route::post('/trainees', [TraineeController::class, 'store'])->middleware('checkRole:admin,training')->name('trainees.store');

        // Route for displaying the form to edit an existing trainee account
        Route::get('/trainees/{trainee}/edit', [TraineeController::class, 'edit'])->middleware('checkRole:admin,training')->name('trainees.edit');

        // Route for updating the trainee account data in the database
        Route::put('/trainees/{traineeId}', [TraineeController::class, 'update'])->middleware('checkRole:admin,training')->name('trainees.update');

        // Route for deleting a trainee account from the database
        Route::delete('/trainees/{trainee}', [TraineeController::class, 'delete'])->middleware('checkRole:admin,training')->name('trainees.delete');

        // Route for displaying a list of all trainee accounts
        Route::get('/trainees', [TraineeController::class, 'index'])->middleware('checkRole:admin,training')->name('trainees.index');
    });

    Route::prefix('manageCourse')->name('manageCourse.')->group(function () {
        // Route for displaying the form to create a new trainee account
        Route::get('/courses/create', [CoursesController::class, 'create'])->middleware('checkRole:admin,training')->name('courses.create');

        // Route for storing the newly created trainee account data in the database
        Route::post('/courses', [CoursesController::class, 'store'])->middleware('checkRole:admin,training')->name('courses.store');

        // Route for displaying the form to edit an existing trainee account
        Route::get('/courses/{course}/edit', [CoursesController::class, 'edit'])->middleware('checkRole:admin,training')->name('courses.edit');

        // Route for updating the trainee account data in the database
        Route::put('/courses/{courseId}', [CoursesController::class, 'update'])->middleware('checkRole:admin,training')->name('courses.update');

        // Route for deleting a trainee account from the database
        Route::delete('/courses/{course}', [CoursesController::class, 'delete'])->middleware('checkRole:admin,training')->name('courses.delete');

        // Route for displaying a list of all trainee accounts
        Route::get('/courses', [CoursesController::class, 'index'])->middleware('checkRole:admin,training')->name('courses.index');
    });
});
