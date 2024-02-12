<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Actions\Profile\ProfileUpdate;
use App\Models\Admin;
use App\Models\User;
use App\Traits\UploadAble;

use function Symfony\Component\String\b;

class ProfileController extends Controller
{
    use UploadAble;

    /**
     * Profile View.
     *
     * @return void
     */
    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Profile Setting.
     *
     * @return void
     */
    public function setting()
    {
        $user = Admin::find(auth()->id());
        return view('admin.profile.setting', compact('user'));
    }


    /**
     * Profile Update.
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function profile_update(ProfileRequest $request)
    {
        $data = $request->only(['name', 'email']);
        $user = Admin::find(auth()->id());

        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->image, 'user');

            deleteFile($user->image);
        }
        if ($request->isPasswordChange == 1) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profile update successfully!');
    }
}
