<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class DownloadFileRequest extends FormRequest
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
            'folder' => ['nullable'],
            'file' => ['required', 'max:200', 
            function($attribute, $value, $fail) {
                if($this->user()->diskConnection()->missing($this->folder.'/'.$value)) {
                    $fail('The '.$value.' file does not exist on your drive. You may not have specified a directory.');
                }
            }],
        ];
    }
}
