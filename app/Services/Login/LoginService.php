<?php

namespace App\Services\Login;

use App\Repositories\LoginUserRepo;

class LoginService
{
    protected LoginUserRepo $loginService;

    function __construct(LoginUserRepo $loginUser)
    {
        $this->loginService = $loginUser;
    }

    function findLoginUserService(){
        return $this->loginService->findLoginUser();
    }
}