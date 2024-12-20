<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{

    public function send(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'phoneNumber' => 'required',
            'details' => 'required',
        ]);

        Mail::to('juanjosecaicedo6@mail.com')->send(
            new ContactMail($request->all())
        );

        return back()->with('status', __('Contact us sent successfully!'));
    }
}
