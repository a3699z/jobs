<?php

namespace Modules\Newsletter\Http\Controllers;

// use App\Mail\NewsletterMail;

use App\Notifications\VerifySubscriptionNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ViewErrorBag;
use Modules\Newsletter\Entities\Email;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Notification;
use Modules\Newsletter\Emails\NewsletterMail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:emails,email'
        ]);

        if (checkMailConfig()) {

            $email = $request->email;
            $subscribe_mail = [
                'email' => $email,
                'token' => Str::random(12),
            ];
            session()->put('subscribe_mail', $subscribe_mail);

            Notification::route('mail', $email)
                ->notify(new VerifySubscriptionNotification($subscribe_mail['token']));
        }

        flashSuccess('We sent a verify mail to your email. Please check your email');
        return back();
    }


    /**
     * sent contact email to admin after your verify by email.
     * @param Request $request
     * @return Renderable
     */
    public function subscribeDataSave($token = null)
    {
        $subscribe_mail = session()->get('subscribe_mail');

        if ($subscribe_mail && $subscribe_mail['token'] == $token) {

            Email::create(['email' => $subscribe_mail['email']]);
            flashSuccess('Your subscription added successfully!');

            $subscribe_mail = session()->forget('subscribe_mail');

            return redirect()->route('website.home');
        } else {

            flashWarning('Your verify link is not valid.');
            return redirect()->route('website.home');
        }
    }

    public function sendMail()
    {
        abort_if(!userCan('newsletter.sendmail'), 403);

        $data['emails'] = Email::get();
        return view('newsletter::send-mail', $data);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        abort_if(!userCan('newsletter.view'), 403);

        $data['emails'] = Email::latest()->paginate(20);
        return view('newsletter::index', $data);
    }

    public function destroy(Email $email)
    {
        $deleted = $email->delete();
        $deleted ? flashSuccess('Email Deleted Successfully') : flashError();
        return back();
    }

    public function submitMail(Request $request)
    {
        abort_if(!userCan('newsletter.sendmail'), 403);

        $request->validate([
            'emails' => 'required',
            'subject' => 'required',
            'body' => 'required'
        ]);

        $arrayEmails = $request->emails;
        $emailSubject = $request->subject;
        $emailBody = $request->body;

        foreach ($arrayEmails as $email) {
            Mail::to($email)->send(new NewsletterMail($emailSubject, $emailBody));
        };

        flashSuccess('Mail Sent Successfully');
        return back();
    }
}
