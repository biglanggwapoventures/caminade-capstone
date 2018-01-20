<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Auth;

class BlockUserController extends Controller
{
    public function __invoke($userId)
    {
        User::whereId($userId)->update([
            'blocked_at' => date('Y-m-d H:i:s'),
            'blocked_by' => Auth::id(),
        ]);

        return redirect()->back();
    }
}
