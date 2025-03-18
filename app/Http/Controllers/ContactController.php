<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{


    public function show()
    {

        return Inertia::render('contacts/index', [
            'contacts' =>  Contact::all()
        ]);
    }
}
