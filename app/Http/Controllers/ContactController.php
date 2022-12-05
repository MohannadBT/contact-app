<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;
use App\Models\Contact;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /* 
    * another way of getting the repository

    protected $company;

    public function __construct(CompanyRepository $company) 
    {
        $this-> company = $company;
    }
    */
    public function __construct(protected CompanyRepository $company)
    {
    }

    public function index()
    {
        
        $companies = $this->company->pluck();
        $contacts = Contact::latest()->get();

        return view('contacts.index', compact('contacts', 'companies')); // compact() is the same as ['contacts' => $contacts] 
    }

    public function create()
    {
        return view('contacts.create');
    }
    
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        // abort_unless(!empty($contact), 404); 
        // abort_if(!isset($contacts[$id]), 404); //the same as the one above but u change the condition(!issert -> issert)

        return view('contacts.show')->with('contact', $contact); // u can send more than 1 value by chaining it like this : ->with()->with()...
    }
}
