<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\ImportContactRequest;

class ImportContactController extends Controller
{
    public function create()
    {
        $companies = Company::forUser(auth()->user())
            ->orderBy('name')
            ->pluck('name', 'id');

        return view('contacts.import', compact('companies'));
    }

    public function store(ImportContactRequest $request)
    {
        dd("import");
    }
}