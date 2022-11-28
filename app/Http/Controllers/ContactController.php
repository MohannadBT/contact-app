<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $companies = [
            1 => ['name' => 'Company One', 'contacts' => 3],
            2 => ['name' => 'Company Two', 'contacts' => 5],
        ];
        
        $contacts = $this->getContacts();

        return view('contacts.index', compact('contacts', 'companies')); // compact() is the same as ['contacts' => $contacts] 
    }

    public function create()
    {
        return view('contacts.create');
    }
    
    public function show($id)
    {
        $contacts = $this->getContacts();

        abort_if(!isset($contacts[$id]), 404);
        // abort_unless(isset($contacts[$id]), 404); the same as the one above but u change the condition

        $contact = $contacts[$id];
        return view('contacts.show')->with('contact', $contact); // u can send more than 1 value by chaining it like this : ->with()->with()...
    }

    protected function getContacts (){
        return [
            1 => ['name' => 'Name 1', 'phone' => '1234567890'],
            2 => ['name' => 'Name 2', 'phone' => '2345678901'],
            3 => ['name' => 'Name 3', 'phone' => '3456789012'],
        ];
    }

}
