<?php

namespace Modules\ExamplePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExamplePermission\Models\User;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = request()->method();
        $rules = [

            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',

        ];

        if (strtolower($method) == 'put') {
            $item = $this->route('user');
            $rules['email'] = 'required|unique:users,email,' . $item->id;
            $rules['password'] = 'nullable|confirmed';
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $method = request()->method();
        if (strtolower($method) == 'post') {
            $user = new User;
            return \Gate::allows('create', $user);
        }

        if (strtolower($method) == 'put') {
            $user = $this->route('user');
            return \Gate::allows('update', $user);
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [

            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Konfirmasi Password',

        ];
    }

    protected function prepareForValidation()
    {
        //
    }
}
