<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactController extends Controller
{
    protected function userCompanies()
    {
        return Company::forUser(auth()->user())->orderBy('name')->pluck('name', 'id');
    }

    public function index()
    {

        $companies = $this->userCompanies();
        $query = Contact::query();
        if (request()->query('trash')) {
            $query->onlyTrashed();
        }
        $contacts = $query
            ->allowedSorts(['first_name', 'last_name', 'email'], "-id")
            ->allowedFilters('company_id')
            ->allowedSearch(['first_name', 'last_name', 'email'])
            ->forUser(auth()->user())
            ->with("company")
            ->paginate(10);
        return view('contacts.index', compact('contacts', 'companies')); // compact() is the same as ['contacts' => $contacts]
    }

    public function create()
    {
        $companies = $this->userCompanies();
        $contact = new Contact();

        return view('contacts.create', compact('companies', 'contact'));
    }

    public function store (ContactRequest  $request)
    {
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
        $companies = $this->userCompanies();
        // $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('companies', 'contact'));
    }

    public function update(ContactRequest  $request, Contact $contact)
    {
        // $contact = Contact::findOrFail($id);
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
            ->with('undoRoute', getUndoRoute('contacts.restore', $contact));
        }

    public function restore(Contact $contact)
    {
        // $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->restore();
        return back()
            ->with('message', 'Contact has been restored from trash.')
            ->with('undoRoute', getUndoRoute('contacts.destroy', $contact));
    }

    // protected function getUndoRoute($name, $resource)
    // {
    //     return request()->missing('undo') ? route($name, [$resource->id, 'undo' => true]) : null;
    // }

    public function forceDelete(Contact $contact)
    {
        // $contact = Contact::onlyTrashed()->findOrFail($id);
        $contact->forceDelete();
        return back()
            ->with('message', 'Contact has been removed permanently.');
    }
}