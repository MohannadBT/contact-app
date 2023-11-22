<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;

class ImportContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'csv' => 'required',
            'company_id' => 'required|exists:companies,id',
        ];
    }

    public function getCsvRows(): array
    {
        $rows = [];

        $path = Storage::putFile('csv-files', $this->file('csv'));
        $stream = Storage::readStream($path);

        $isHeader = true;
        while ($row = fgetcsv($stream)) {
            if ($isHeader) {
                $isHeader = false;
                continue;
            }

            $rows[] = $row;
        }

        Storage::delete($path);

        return $rows;
    }
}