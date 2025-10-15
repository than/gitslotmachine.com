<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // No auth required - public API
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'commit_hash' => ['required', 'string', 'size:7', 'regex:/^[0-9a-f]{7}$/i'],
            'commit_full_hash' => ['nullable', 'string', 'size:40', 'regex:/^[0-9a-f]{40}$/i'],
            'repo_url' => ['required', 'url', 'regex:/github\.com/'],
            'repo_owner' => ['required', 'string'],
            'repo_name' => ['required', 'string'],
            'github_username' => ['required', 'string'],
        ];
    }
}
