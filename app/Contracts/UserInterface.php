<?php

namespace App\Contracts;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Requests\UserAccountRequest;
use Illuminate\Http\Request;

interface UserInterface
{
    public function index(Request $request);
    public function update(UserAccountRequest $request,int $id);
    public function updateImage(UpdateImageRequest $request,$id);
    public function destroy(int $id);
    public function resetPassword(ResetPasswordRequest $request);
}