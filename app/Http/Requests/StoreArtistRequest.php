<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'dob' => 'required|date',
            'gender' => 'required|in:m,f,o',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
            'first_release_year' => 'required|integer|min:1900|max:' . date('Y'),
            'no_of_albums_released' => 'required|integer|min:0',
        ];
    }
}
