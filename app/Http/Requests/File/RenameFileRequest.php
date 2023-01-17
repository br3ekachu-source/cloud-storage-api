<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class RenameFileRequest extends FormRequest
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
            'old_name' => ['required', 'max:200', 
            function($attribute, $value, $fail) {
                if($this->user()->diskConnection()->missing($value)) {
                    $fail('The '.$value.' file does not exist on your drive. You may not have specified a directory.');
                }
            }],
            'new_name' => ['required', 'max:200', 
            function($attribute, $value, $fail) {
                if(pathinfo($value)['extension'] == 'php') {
                    $fail('The '.$attribute.' must not be a file of type: php.');
                };
                if(substr_count($value, '/')>1) {
                    $fail('The '.$attribute.' cannot contains more than one character /.');
                }
            }]
        ];
    }
}
