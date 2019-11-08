<?php

function set_user_info(\App\Models\User $user)
{
    session(['user' => [
        'id' => $user->id,
        'account' => $user->account,
    ]]);

    return true;
}

function get_user_info()
{
    return session('user');
}