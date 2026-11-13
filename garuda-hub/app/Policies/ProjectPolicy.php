<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /** Hanya klien pemilik, dan hanya selama lelang masih berstatus terbuka. */
    public function update(User $user, Project $project): bool
    {
        return $user->hasRole('klien')
            && (int) $user->id === (int) $project->client_id
            && $project->status === 'terbuka';
    }

    /** Hanya klien pemilik proyek. */
    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('klien') && (int) $user->id === (int) $project->client_id;
    }
}
