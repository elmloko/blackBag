<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Incluye eliminados si tu User usa SoftDeletes
        $users = User::withTrashed()->paginate();

        return view('user.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * $users->perPage());
    }

    public function create()
    {
        $user = new User();

        // Muestra solo roles del guard 'web'
        $roles = Role::where('guard_name', 'web')->get();

        return view('user.create', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string'],
            'email'    => ['required', 'email', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'password' => ['required', 'string', 'min:8'],
            'city'     => ['required', 'string'],
            'ci'       => ['required', 'string'],
            'roles'    => ['nullable', 'array'],
            'roles.*'  => ['integer', 'exists:roles,id'],
        ]);

        $user = new User();
        $user->name     = $request->string('name');
        $user->email    = $request->string('email');
        $user->password = bcrypt($request->input('password'));
        $user->city     = $request->string('city');
        $user->ci       = $request->string('ci');
        $user->save();

        // Convertir IDs -> nombres (Spatie espera nombres o modelos)
        $roleNames = Role::where('guard_name', 'web')
            ->whereIn('id', (array) $request->input('roles', []))
            ->pluck('name')
            ->toArray();

        // Asignar (si no mandas nada, queda sin roles)
        $user->syncRoles($roleNames);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente');
    }

    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return view('user.show', compact('user'));
    }

    public function edit($id)
    {
        $user  = User::withTrashed()->findOrFail($id);
        $roles = Role::where('guard_name', 'web')->get();

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'    => ['required', 'string'],
            // ignora el propio ID y además ignora soft-deleted para no chocar
            'email'   => ['required', 'email', 'unique:users,email,' . $user->id . ',id,deleted_at,NULL'],
            'city'    => ['required', 'string'],
            'ci'      => ['required', 'string'],
            'password'=> ['nullable', 'string', 'min:8'],
            'roles'   => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $user->name  = $request->string('name');
        $user->email = $request->string('email');
        $user->city  = $request->string('city');
        $user->ci    = $request->string('ci');

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        // Sincroniza roles convirtiendo IDs a nombres
        $roleNames = Role::where('guard_name', 'web')
            ->whereIn('id', (array) $request->input('roles', []))
            ->pluck('name')
            ->toArray();

        $user->syncRoles($roleNames); // si [] => borra roles

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }
}
