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
        // $contacts = Contact::latest()->paginate(10);
        // $contactsCollection = Contact::latest()->get();
        // $perPage = 10;
        // $currentPage = request()->query('page', 1);
        // $items = $contactsCollection->slice(($currentPage * $perPage) - $perPage, $perPage);
        // $total = $contactsCollection->count();
        // $contacts = new LengthAwarePaginator($items, $total, $perPage, $currentPage, [
        //     'path' => request()->url(),
        //     'query' => request()->query()
        // ]);
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

        return view('contacts.create', compact('companies'));
    }

    public function store (Request $request)
    {
        dd($request);
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        // abort_unless(!empty($contact), 404);
        // abort_if(!isset($contacts[$id]), 404); //the same as the one above but u change the condition(!issert -> issert)

        return view('contacts.show')->with('contact', $contact); // u can send more than 1 value by chaining it like this : ->with()->with()...
    }
}