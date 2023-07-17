<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactController extends Controller
{
    /*
    * another way of getting the repository

    protected $company;

    public function __construct(CompanyRepository $company)
    {
        $this->company = $company;
    }
    */
    public function __construct(protected CompanyRepository $company)
    {
    }

    public function index()
    {

        $companies = $this->company->pluck();
        $contacts = Contact::latest()->where(function ($query) {
            if ($companyId = request()->query("company_id")) {
                $query->where("company_id", $companyId);
            }
        })->paginate(10);

        return view('contacts.index', compact('contacts', 'companies')); // compact() is the same as ['contacts' => $contacts]
    }

    public function create()
    {
        $companies = $this->company->pluck();
        $contact = new Contact();
        return view('contacts.create', compact('companies', 'contact'));
    }

    public function store (Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ]);
        Contact::create($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        // abort_unless(!empty($contact), 404);
        // abort_if(!isset($contacts[$id]), 404); //the same as the one above but u change the condition(!issert -> issert)
        return view('contacts.show')->with('contact', $contact); // u can send more than 1 value by chaining it like this : ->with()->with()...
    }

    public function edit($id)
    {
        $companies = $this->company->pluck();
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ]);
        $contact->update($request->all());
        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }
}