<?php

namespace App\Http\Controllers;

use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
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
        $query = Contact::query();
        if (request()->query('trash')) {
            $query->onlyTrashed();
        }
        $contacts = $query
            ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
            ->allowedFilters('company_id')
            ->allowedSearch(['first_name', 'last_name', 'email'])
            ->paginate(10);
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

    public function show(Contact $contact)
    {
        // $contact = Contact::findOrFail($id);
        // abort_unless(!empty($contact), 404);
        // abort_if(!isset($contacts[$id]), 404); //the same as the one above but u change the condition(!issert -> issert)
        return view('contacts.show')->with('contact', $contact); // u can send more than 1 value by chaining it like this : ->with()->with()...
    }

    public function edit(Contact $contact)
    {
        $companies = $this->company->pluck();
        // $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        // $contact = Contact::findOrFail($id);
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'company_id' => 'required|exists:companies,id'
        ]);
        $contact->update($request->all());
        return redirect()->route('contacts.index')
            ->with('message', 'Contact has been updated successfully');
    }

    public function destroy(Contact $contact)
    {
        // $contact = Contact::findOrFail($id);
        $contact->delete();
        $redirect = request()->query('redirect');
        return ($redirect ? redirect()->route($redirect) : back())
            ->with('message', 'Contact has been moved to trash.')
            ->with('undoRoute', $this->getUndoRoute('contacts.restore', $contact));
        }

    public function restore(Contact $contact)
    {
        // $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();
        return back()
            ->with('message', 'Contact has been restored from trash.')
            ->with('undoRoute', $this->getUndoRoute('contacts.destroy', $contact));
        }

    protected function getUndoRoute($name, $resource)
    {
        return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    }

    public function forceDelete(Contact $contact)
    {
        // $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();
        return back()
            ->with('message', 'Contact has been removed permanently.');
    }
}