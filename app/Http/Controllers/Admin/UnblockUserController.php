<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;

class UnblockUserController extends Controller
{
    public function __invoke($userId)
    {
        User::whereId($userId)->update([
            'blocked_at' => null,
            'blocked_by' => null,
        ]);

        return redirect()->back();
    }
}
