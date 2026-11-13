<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $term = $request->query('q');
        $q = is_string($term) ? mb_substr(trim($term), 0, 100) : '';

        $projects = Project::with('client')
            ->withCount('bids')
            ->search($q)
            ->latest('id')
            ->get();

        return view('projects.index', compact('projects', 'q'));
    }

    public function show(Project $project): View
    {
        $project->load([
            'client',
            'bids' => fn ($query) => $query->with('developer')
                ->orderByDesc('is_winner')->orderBy('harga')->orderBy('id'),
        ]);

        return view('projects.show', compact('project'));
    }

    public function create(): View
    {
        return view('projects.form', ['project' => new Project]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        // client_id selalu diambil dari user yang login, bukan dari form.
        $project = $request->user()->projects()->create($request->validated());

        return redirect()->route('projects.show', $project)->with('ok', 'Proyek berhasil dipasang.');
    }

    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.form', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $project->update($request->validated());

        return redirect()->route('projects.show', $project)->with('ok', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $project->delete(); // penawaran ikut terhapus lewat FK cascade

        return redirect()->route('projects.index')->with('ok', 'Proyek dihapus.');
    }
}
