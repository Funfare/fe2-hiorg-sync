<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function home()
    {
        $org = \Auth::user()->organization;
        return view('organizations.show', compact('org'));
    }

    public function edit()
    {
        $org = \Auth::user()->organization;
        return view('organizations.edit', compact('org'));
    }

    public function update(Request $request)
    {
        $org = \Auth::user()->organization;
        $data = $request->validate([
            'fe2_link' => 'nullable',
            'fe2_user' => 'nullable',
            'fe2_pass' => 'nullable',
            'fe2_sync_token' => 'nullable',
            'fe2_provisioning_user' => 'nullable',
            'fe2_provisioning_leader' => 'nullable',
        ]);
        $org->update($data);
        return redirect()->route('organizations.edit')->with('message', 'Daten gespeichert');
    }
}
