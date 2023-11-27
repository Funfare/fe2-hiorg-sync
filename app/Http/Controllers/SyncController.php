<?php

namespace App\Http\Controllers;

use App\Models\Sync;
use Illuminate\Http\Request;

class SyncController extends Controller
{


    public function show(Sync $sync)
    {
        abort_if($sync->organization_id != \Auth::user()->organization_id, 403);

        return view('organizations.sync', [
            'sync' => $sync,
            'org' => \Auth::user()->organization,
        ]);
    }
}
