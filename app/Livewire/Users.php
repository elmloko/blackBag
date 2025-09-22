<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // ← IMPORTANTE
use Illuminate\Validation\Rule;

class Users extends Component
{
    use WithPagination;

    public $search = '';
    public $searchQuery = '';
    public $newPassword = '';
    public $userIdBeingUpdated = null;

    // Roles
    public $allRoles = [];     // lista de roles (id=>name) para el select
    public $roleId = null;     // id del rol seleccionado en el modal
    public $userIdForRole = null; // usuario al que se le asignará el rol

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Carga todos los roles del guard 'web'
        $this->allRoles = Role::where('guard_name', 'web')
            ->orderBy('name')
            ->get(['id','name'])
            ->toArray();
    }

    public function updatingSearch()
    {
        // Si teclea, que pagine desde la primera página
        $this->resetPage();
    }

    public function searchUsers()
    {
        $this->searchQuery = $this->search;
        $this->resetPage();
    }

    // Modal: Cambiar contraseña
    public function setPasswordUser($userId)
    {
        $this->userIdBeingUpdated = $userId;
        $this->dispatch('openModal'); // abre modal de contraseña
    }

    // Baja (soft delete)
    public function deleteUser($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);
        $user->delete();
        session()->flash('success', 'Usuario dado de baja correctamente.');
    }

    // Alta (restore)
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        session()->flash('success', 'Usuario reactivado correctamente');
        // Si prefieres redirigir:
        // return redirect()->route('users.index')->with('success','Usuario reactivado correctamente');
    }

    // Guardar nueva contraseña
    public function updatePassword()
    {
        $this->validate([
            'newPassword' => ['required','min:6'],
        ]);

        $user = User::find($this->userIdBeingUpdated);

        if ($user) {
            $user->password = Hash::make($this->newPassword);
            $user->save();
            session()->flash('success', 'Contraseña actualizada correctamente.');
        } else {
            session()->flash('error', 'Usuario no encontrado.');
        }

        $this->newPassword = '';
        $this->userIdBeingUpdated = null;
        $this->dispatch('closeModal');
    }

    // Modal: abrir asignación de rol
    public function setRoleUser($userId)
    {
        $this->userIdForRole = $userId;

        // Preseleccionar el primer rol del usuario (si tiene)
        $user = User::with('roles')->findOrFail($userId);
        $this->roleId = optional($user->roles->first())->id;

        $this->dispatch('openRoleModal');
    }

    // Guardar rol (resuelve por ID con guard web)
    public function saveUserRole()
    {
        $this->validate([
            'userIdForRole' => ['required','integer','exists:users,id'],
            'roleId'        => ['required','integer', Rule::exists('roles','id')->where('guard_name','web')],
        ], [
            'roleId.required' => 'Debes seleccionar un rol.',
            'roleId.exists'   => 'El rol seleccionado no existe para el guard web.',
        ]);

        $user = User::findOrFail($this->userIdForRole);

        // Resuelve el rol por ID y guard 'web' para evitar "There is no role named '3'"
        $role = Role::findById((int)$this->roleId, 'web');

        // Opción 1: reemplazar TODOS los roles del usuario por uno
        $user->syncRoles([$role]);

        // Opción 2 (alternativa): agregar sin quitar otros
        // $user->assignRole($role);

        session()->flash('success', 'Rol actualizado correctamente.');

        $this->roleId = null;
        $this->userIdForRole = null;

        $this->dispatch('closeRoleModal');
    }

    public function render()
    {
        $q = trim($this->searchQuery);

        $users = User::withTrashed()
            ->with(['roles' => function($r){ $r->select('id','name'); }])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderBy('id','asc')
            ->paginate(10);

        return view('livewire.users', [
            'users' => $users,
        ]);
    }
}
