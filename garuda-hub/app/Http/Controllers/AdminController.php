<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.index', [
            'users' => User::orderBy('id')->get(),
            'projects' => Project::with('client')->latest('id')->get(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('err', 'Anda tidak dapat mengubah peran akun sendiri.');
        }

        $data = $request->validate(
            ['role' => ['required', Rule::in(User::ROLES)]],
            ['role.required' => 'Peran wajib dipilih.', 'role.in' => 'Peran tidak valid.']
        );

        $user->role = $data['role'];
        $user->save();

        return back()->with('ok', 'Peran user diubah.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('err', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete(); // proyek dan penawaran ikut terhapus lewat FK cascade

        return back()->with('ok', 'User dihapus.');
    }

    public function destroyProject(Project $project): RedirectResponse
    {
        $project->delete(); // penawaran ikut terhapus lewat FK cascade

        return back()->with('ok', 'Proyek dihapus.');
    }
}
