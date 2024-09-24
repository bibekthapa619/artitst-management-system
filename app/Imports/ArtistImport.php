<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class ArtistImport implements ToCollection
{
    public $validatedData = [];
    public $validationErrors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            // Skip header row (usually the first row)
            if ($key == 0) continue;

            // Prepare row data with correct field names
            $rowData = [
                'first_name' => $row[0] ?? null,
                'last_name' => $row[1] ?? null,
                'email' => $row[2] ?? null,
                'phone' => $row[3] ?? null,
                'dob' => $row[4] ?? null,
                'gender' => $row[5] ?? null,
                'address' => $row[6] ?? null,
                'artist_name' => $row[7] ?? null,
                'first_release_year' => $row[8] ?? null,
                'no_of_albums_released' => $row[9] ?? null,
            ];

            // Validate the row data
            $validator = Validator::make($rowData, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'phone' => 'required|regex:/^[0-9]{10,15}$/|unique:users',
                'dob' => 'required|date',
                'gender' => 'required|in:m,f,o',
                'address' => 'required|string|max:255',
                'artist_name' => 'required|string|max:255',
                'first_release_year' => 'required|integer|digits:4',
                'no_of_albums_released' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->getMessages() as $field => $messages) {
                    foreach ($messages as $message) {
                        $this->validationErrors[] = "Error in column '{$field}' on row " . ($key + 1) . ": {$message}";
                    }
                }
            } else {
                $this->validatedData[] = $validator->validated();
            }
        }
        if (!empty($this->validationErrors)) {
            $messageBag = new MessageBag(['validationErrors' => $this->validationErrors]);

            throw ValidationException::withMessages($messageBag->getMessages());
        }
    }

    public function getValidatedData()
    {
        return $this->validatedData;
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
