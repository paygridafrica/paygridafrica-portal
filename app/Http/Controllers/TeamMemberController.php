<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('created_at', 'desc')->get();

        return view('team-members.index', [
            'members' => $members,
        ]);
    }
}
