<?php

namespace App\Repositories;

use App\Repositories\LoginUserInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginUserRepo implements LoginUserInterface
{
    function findLoginUser()
    {
        $loginUser = loginAccount();

        return $loginUser;
    }
}
