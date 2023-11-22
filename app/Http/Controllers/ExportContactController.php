<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ExportContactRequest;

class ExportContactController extends Controller
{
    public function create()
    {
        $columns = ['first_name', 'last_name', 'email', 'phone', 'address', 'company'];

        return view('contacts.export', compact('columns'));
    }

    public function store(ExportContactRequest $request)
    {
        $columns = $request->columns;
        dd($columns);
    }
}