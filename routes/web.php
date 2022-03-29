<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\CompaniesController;

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
//landing page
Route::match(['get', 'post'],'/', [HomeController::class, 'index'])->name('index');
//login function
Route::match(['get', 'post'],'/login', [AuthController::class, 'login'])->name('login');
Route::match(['get', 'post'],'/logout', [AuthController::class, 'logout'])->name('logout');
//After Authentication
Route::group(['middleware' => 'auth:admin'], function () {
    //Dashboard
    Route::match(['get', 'post'],'/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    //Employees Page
    Route::match(['get', 'post'],'/employees', [EmployeesController::class, 'index'])->name('employees');
    //Adding Function
    Route::match(['get', 'post'],'/add-employees', [EmployeesController::class, 'add_employees'])->name('add_employees');
    //Edit Function
    Route::match(['get', 'post'],'/edit-employee/{id}', [EmployeesController::class, 'edit_employee'])->name('edit_employee');
    //Delete Function
    Route::match(['get', 'post'],'/delete-employee/{id}', [EmployeesController::class, 'delete_employee'])->name('delete_employee');
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Companies List
    Route::match(['get', 'post'],'/companies', [CompaniesController::class, 'index'])->name('companies');
    //Adding Function
    Route::match(['get', 'post'],'/add-companies', [CompaniesController::class, 'add_companies'])->name('add_companies');
    //Edit Function
    Route::match(['get', 'post'],'/edit-company/{id}', [CompaniesController::class, 'edit_company'])->name('edit_company');
    //Delete Function
    Route::match(['get', 'post'],'/delete-company/{id}', [CompaniesController::class, 'delete_company'])->name('delete_company');
});