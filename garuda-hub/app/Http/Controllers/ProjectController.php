<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
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
}
