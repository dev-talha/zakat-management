<?php
namespace App\Http\Controllers;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles')->where('user_type', 'staff')->latest();
        if ($s = $request->get('search')) $query->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%");
        $users = $query->paginate(20);
        return view('users.index', compact('users'));
    }
    public function create() { $roles = Role::all(); return view('users.create', compact('roles')); }
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'email' => 'required|email|unique:users', 'role' => 'required']);
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile, 'password' => bcrypt($request->password ?? 'password'), 'user_type' => 'staff', 'status' => 'active']);
        $user->assignRole($request->role);
        return redirect()->route('users.index')->with('success', 'User created.');
    }
    public function show(User $user) { return view('users.show', compact('user')); }
    public function edit(User $user) { $roles = Role::all(); return view('users.edit', compact('user', 'roles')); }
    public function update(Request $request, User $user)
    {
        $user->update($request->only('name', 'email', 'mobile', 'status'));
        if ($request->role) { $user->syncRoles([$request->role]); }
        return back()->with('success', 'Updated.');
    }
    public function destroy(User $user) { $user->delete(); return redirect()->route('users.index')->with('success', 'Deleted.'); }
}
