<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole('klien')) {
            $projects = $user->projects()->withCount('bids')->latest('id')->get();

            return view('dashboard.klien', compact('projects'));
        }

        $bids = $user->bids()->with('project.client')->latest('id')->get();

        return view('dashboard.developer', compact('bids'));
    }
}
