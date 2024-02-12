<?php

namespace Modules\Contact\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Contact\Entities\Contact;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyContactNotification;
use Modules\Contact\Repositories\ContactRepositories;
use Modules\Contact\Notifications\ContactNotification;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!userCan('contact.view'), 403);

        $contacts = Contact::all();
        return view('contact::index', compact('contacts'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|min:3",
            'email' => "required",
            'subject' => "required|min:5",
            'message' => "required|min:10",
            'g-recaptcha-response' => config('captcha.active') ? 'required|captcha' : ''
        ], [
            'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
        ]);

        // to form creator first step
        if (checkMailConfig()) {

            $name = $request->name;
            $email = $request->email;
            $subject = $request->subject;
            $message = $request->message;

            $contact = [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'token' => Str::random(12),
            ];

            Admin::all()->each(function ($admin) use ($contact) {
                $admin->notify(new ContactNotification($contact));
            });
        }

        flashSuccess('Your contact mail has been sent');
        return back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (!enableModule('contact')) {
            abort(404);
        }
        $contact = Contact::find($id);

        return [
            'name' => $contact->name,
            'email' => $contact->email,
            'message' => $contact->message,
        ];
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        session()->flash('success', 'Contact Deleted Successfully!');
        return back();
    }

    /**
     * Remove multiple resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
}
