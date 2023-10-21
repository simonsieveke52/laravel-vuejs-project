<?php

namespace App\Http\Controllers;

use App\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreContactRequest;
use App\Notifications\ContactRequestNotification;

class ContactRequestController extends Controller
{
    /**
     * @return ContactRequest
     */
    public function store(StoreContactRequest $request): ContactRequest
    {
        $contact = ContactRequest::create($request->only([
            'name', 'email', 'phone', 'content'
        ]));

        $contact->notify(new ContactRequestNotification());

        return $contact;
    }
}
