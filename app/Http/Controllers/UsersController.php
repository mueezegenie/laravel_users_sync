<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function paginateUsers($page = 1, $limit = 10)
    {
        $toSkip = $page === 1 ? 0 : intval($page) * intval($limit);

        $users = User::take(10)->skip($toSkip)->get();

        $usersTotal = User::get()->count();

        return response()->json(["status" => "success", "data" => $users, "page" => intval($page), "limit" => $limit, "total" => $usersTotal]);
    }
}
