<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            //現在のパスワードと新しいパスワードが合わなければエラー
            if (!(Hash::check($this->input('current_password'), $user->password))) {
                $validator->errors()->add('current_password', '現在のパスワードが違います。');
            }
        });
    }
}
