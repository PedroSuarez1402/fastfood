<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Estado del Modal
    public $showModal = false;
    public $isEditing = false;

    // Datos del Usuario
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role; // Rol seleccionado

    public function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userId)],
            'role' => 'required|exists:roles,name',
            // Password solo requerido al crear
            'password' => $this->isEditing ? 'nullable|min:6' : 'required|min:6',
        ];
    }

    public function create()
    {
        $this->reset(['name', 'email', 'password', 'role', 'userId']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(User $user)
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        // Obtenemos el primer rol del usuario (asumiendo 1 rol por usuario)
        $this->role = $user->roles->first()?->name ?? ''; 
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $user = User::find($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            
            if ($this->password) {
                $user->update(['password' => bcrypt($this->password)]);
            }
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
        }

        // Sincronizar Rol (Spatie)
        $user->syncRoles($this->role);

        $this->showModal = false;
        session()->flash('success', 'Usuario guardado correctamente.');
    }
    
    public function render()
    {
        return view('livewire.admin.users.index', [
            'users' => User::with('roles')->paginate(10),
            'roles' => Role::all() // Para el select
        ]);
    }
}
