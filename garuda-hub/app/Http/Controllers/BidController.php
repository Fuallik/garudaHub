<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBidRequest;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function store(StoreBidRequest $request, Project $project): RedirectResponse
    {
        if (! $project->isOpen()) {
            return back()->with('err', 'Lelang sudah ditutup; penawaran tidak dapat diajukan.');
        }

        $developer = $request->user();

        if ($project->bids()->where('developer_id', $developer->id)->exists()) {
            return back()->with('err', 'Anda sudah mengajukan penawaran pada proyek ini.');
        }

        try {
            // developer_id selalu dari user yang login, bukan dari form.
            $project->bids()->create($request->validated() + ['developer_id' => $developer->id]);
        } catch (UniqueConstraintViolationException) {
            return back()->with('err', 'Anda sudah mengajukan penawaran pada proyek ini.');
        }

        return back()->with('ok', 'Penawaran berhasil dikirim.');
    }

    public function pick(Request $request, Project $project, Bid $bid): RedirectResponse
    {
        $error = DB::transaction(function () use ($request, $project, $bid): ?string {
            // Kepemilikan dicek di query; baris proyek dikunci agar tidak ada dua pemenang.
            $locked = Project::query()
                ->whereKey($project->getKey())
                ->where('client_id', $request->user()->getKey())
                ->lockForUpdate()
                ->first();

            if ($locked === null) {
                return 'Proyek tidak ditemukan atau bukan milik Anda.';
            }

            if ($locked->status !== 'terbuka') {
                return 'Pemenang sudah dipilih atau lelang sudah ditutup.';
            }

            $bid->forceFill(['is_winner' => true])->save();
            $locked->update(['status' => 'ditutup']);

            return null;
        });

        return redirect()
            ->route('projects.show', $project)
            ->with($error ? 'err' : 'ok', $error ?? 'Pemenang berhasil dipilih.');
    }
}
