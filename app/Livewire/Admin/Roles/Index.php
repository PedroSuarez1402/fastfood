<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithPagination;
    public $search = '';
    // Modal
    public $showModal = false;
    public $isEditing = false;

    // Datos del Formulario
    public $roleId;
    public $name;
    public $selectedPermissions = []; // Array para los checkboxes

    public function rules()
    {
        return [
            'name' => 'required|string|min:3|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array'
        ];
    }

    // Resetear paginación si se busca
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'roleId', 'selectedPermissions']);
        $this->isEditing = false;
        $this->showModal = true;
    }
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        
        // Evitar editar SuperAdmin para no romper el sistema
        if($role->name === 'SuperAdmin') {
            session()->flash('error', 'El rol SuperAdmin no se puede editar.');
            return;
        }

        $this->roleId = $role->id;
        $this->name = $role->name;
        
        // Cargar permisos actuales del rol en el array (convertimos a strings para los checkboxes)
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();

        $this->isEditing = true;
        $this->showModal = true;
    }
    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
        } else {
            $role = Role::create(['name' => $this->name]);
        }

        // Sincronizar permisos (Spatie se encarga de borrar los viejos y poner los nuevos)
        // Convertimos los keys true/false de livewire a nombres de permisos si es necesario, 
        // pero wire:model con array funciona directo con values.
        $role->syncPermissions($this->selectedPermissions);

        $this->showModal = false;
        session()->flash('success', 'Rol guardado correctamente.');
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        if($role->name === 'SuperAdmin') {
            return; // Seguridad extra
        }

        // Verificar si tiene usuarios asignados
        if($role->users()->count() > 0) {
            session()->flash('error', 'No se puede eliminar un rol que tiene usuarios asignados.');
            return;
        }

        $role->delete();
        session()->flash('success', 'Rol eliminado.');
    }
    public function render()
    {
        $roles = Role::withCount('permissions')
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        // Pasamos todos los permisos disponibles para el formulario
        $permissions = Permission::all();

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }
}
