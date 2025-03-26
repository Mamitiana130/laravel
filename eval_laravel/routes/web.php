<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\TauxController;
use App\Http\Controllers\DepenseTicketController;
use App\Http\Controllers\DepenseLeadController;
use App\Http\Middleware\CrmAuthenticated;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CrmManagerRole;



Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(CrmAuthenticated::class)->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::middleware(CrmManagerRole::class)->group(function () {
        Route::get('/api/customers-by-year', [DashboardController::class, 'getCustomersByYear']);
        Route::get('/api/total-customers', [DashboardController::class, 'getTotalCustomers']);
        /**---------------------------------TICKET-------------------------------------------------------- */
        Route::get('/api/total-tickets', [DashboardController::class, 'getTotalTickets']);
        Route::get('/api/tickets-by-status', [DashboardController::class, 'getTicketsByStatus']);
        Route::get('/api/tickets-by-year', [DashboardController::class, 'getTicketsByYear']);
        
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
        
        Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');
        /**---------------------------------LEAD-------------------------------------------------------- */
        Route::get('/api/total-leads', [DashboardController::class, 'getTotalLeads']);
        Route::get('/api/leads-by-status', [DashboardController::class, 'getLeadsByStatus']);
        Route::get('/api/leads-by-year', [DashboardController::class, 'getLeadsByYear']);
        
        
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
        
        Route::get('/leads/{id}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::put('/leads/{id}', [LeadController::class, 'update'])->name('leads.update');
        /**---------------------------------TAUX-------------------------------------------------------- */
        Route::get('/taux/create', [TauxController::class, 'create'])->name('taux.create');
        Route::post('/taux/store', [TauxController::class, 'store'])->name('taux.store');
        /**---------------------------------DEPENSE TICKET-------------------------------------------------------- */
        Route::get('/depenses/tickets', [DepenseTicketController::class, 'index'])->name('depensesTickets.index');
        Route::get('/depenses/tickets/{id}/edit', [DepenseTicketController::class, 'edit'])->name('depenses.tickets.edit');
        Route::put('/depenses/tickets/{id}', [DepenseTicketController::class, 'update'])->name('depenses.tickets.update');
        Route::delete('/depenses/tickets/{id}', [DepenseTicketController::class, 'destroy'])->name('depenses.tickets.destroy');
        /**---------------------------------DEPENSE LEAD-------------------------------------------------------- */
        Route::get('/depenses/leads', [DepenseLeadController::class, 'index'])->name('depensesLeads.index');
        Route::get('/depenses/leads/{id}/edit', [DepenseLeadController::class, 'edit'])->name('depenses.leads.edit');
        Route::put('/depenses/leads/{id}', [DepenseLeadController::class, 'update'])->name('depenses.leads.update');
        Route::delete('/depenses/leads/{id}', [DepenseLeadController::class, 'destroy'])->name('depenses.leads.destroy');

        Route::get('/api/total-depenses-tickets', [DashboardController::class, 'getTotalDepensesTickets']);
        Route::get('/api/total-depenses-leads', [DashboardController::class, 'getTotalDepensesLeads']);
    });
});

