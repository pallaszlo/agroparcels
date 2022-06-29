<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\FitoProductController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LayerController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ParcelController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkMachineController;
use App\Models\Group;
use App\Models\Operation;
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

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::get('me', [LoginController::class, 'me']);

    Route::post('password/update', [AdminController::class,'changePassword']);
    Route::get('users', [AdminController::class,'getUsers']);
    Route::post('users/edit', [AdminController::class,'updateUser']);
    Route::post('users', [AdminController::class,'addUser']);
    Route::get('roles', [AdminController::class,'geRoles']);

    Route::resource('companies', CompanyController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('layers', LayerController::class);
    Route::resource('parcels', ParcelController::class);
    Route::resource('cultures', CultureController::class);
    Route::resource('diseases', DiseaseController::class);
    Route::resource('fitoproducts', FitoProductController::class);
    Route::resource('workmachines', WorkMachineController::class);
    Route::resource('seasons', SeasonController::class);
    Route::resource('persons', PersonController::class);
    Route::get('persons/{personUuid}/works/new', [PersonController::class,'getAttachableWorks']);
    Route::post('persons/{personUiid}/works/{workUuid}', [PersonController::class,'addWork']);
    Route::delete('persons/{personUiid}/works/{workUuid}', [PersonController::class,'removeWork']);
    Route::post('persons/{personUiid}/operations/{operationUuid}', [PersonController::class,'addOperation']);
    Route::put('persons/{personUiid}/operations/{operationUuid}', [PersonController::class,'updateOperation']);
    Route::delete('persons/{personUiid}/operations/{operationUuid}', [PersonController::class,'removeOperation']);
    Route::resource('works', WorkController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::resource('operations', OperationController::class);
    Route::post('operations/{opUuid}/status/{statusId}', [OperationController::class,'changeStatus']);
    Route::post('operations/{opUuid}/{personUuid}', [OperationController::class, 'changeHoursWorked']);
    Route::put('operations/{opUuid}/treatment_quantity', [OperationController::class, 'changeTreatmentQuantity']);
    Route::get('operations/{opUuid}/possible_persons', [OperationController::class, 'getPossiblePersons']);
});
