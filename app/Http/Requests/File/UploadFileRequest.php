<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
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
            'userfile' => ['required', 'file', 'max:20400', 
            function($attribute, $value, $fail) {
                if(pathinfo($value->getClientOriginalName())['extension'] == 'php') {
                    $fail('The '.$attribute.' must not be a file of type: php.');
                }
                $disk = $this->user()->diskConnection();
                $allFiles = $disk->allFiles();
                $diskSize = 0;
                foreach ($allFiles as $file) {
                    $diskSize += $disk->size($file);
                }
                if ($diskSize + $value->getSize() > 104857600) {
                    $fail('Not enought disk space');
                }
            }],
            'folder' => 'nullable|max:100|alpha_num'
        ];
    }
}
