<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Admin\Mesas\Create;
use App\Livewire\Admin\Mesas\Edit;
use App\Livewire\Admin\Mesas\Index;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Livewire\Admin\Productos\Index as ProductosIndex;
use App\Livewire\Admin\Productos\Create as ProductosCreate;
use App\Livewire\Admin\Productos\Edit as ProductosEdit;
use App\Livewire\Admin\Productos\Show as ProductosShow;
use App\Livewire\Admin\Pedidos\Index as PedidosIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Roles\Index as RolesIndex;
use App\Livewire\Admin\Ingredients\Index as IngredientsIndex;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    // Productos
    Route::get('admin/productos', ProductosIndex::class)->name('admin.productos.index');
    Route::get('admin/productos/crear', ProductosCreate::class)->name('admin.productos.create');
    Route::get('admin/productos/{producto}/editar', ProductosEdit::class)->name('admin.productos.edit');
    Route::get('admin/productos/{producto}', ProductosShow::class)->name('admin.productos.show');
    // Pedidos
    Route::get('admin/pedidos', PedidosIndex::class)->name('admin.pedidos.index');

    // Mesas
    Route::get('admin/mesas', Index::class)->name('admin.mesas.index');
    Route::get('admin/mesas/crear', Create::class)->name('admin.mesas.create');
    Route::get('admin/mesas/{mesa}/editar', Edit::class)->name('admin.mesas.edit');

    // Gestion de usuarios
    Route::get('admin/users', UsersIndex::class)
        ->middleware('can:gestionar_usuarios')
        ->name('admin.users.index');
        
    Route::get('/admin/roles', RolesIndex::class)
        ->middleware('can:gestionar_usuarios') // O gestionar_roles si lo creas
        ->name('admin.roles.index');
    // Gestion de ingredientes
    Route::get('/admin/ingredients', IngredientsIndex::class)->name('admin.ingredients.index');
});
