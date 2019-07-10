<?php


namespace App\Http\Controllers;


use App\User;

class PublicController extends ApiController
{
    public function userProfile($id)
    {
        $user = User::whereId($id)->with('profile')->firstOrFail();
        return $this->showOne($user);
    }
}