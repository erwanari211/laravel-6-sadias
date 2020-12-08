<?php

namespace Modules\ExamplePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExamplePermission\Models\Role;

class RoleRequest extends FormRequest
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

            'name' => 'required|unique:roles,name',
            'guard_name' => 'nullable',

        ];

        if (strtolower($method) == 'put') {
            $item = $this->route('role');
            $rules['name'] = 'required|unique:roles,name,' . $item->id;
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
            $role = new Role;
            return \Gate::allows('create', $role);
        }

        if (strtolower($method) == 'put') {
            $role = $this->route('role');
            return \Gate::allows('update', $role);
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
            'guard_name' => 'Guard',

        ];
    }

    protected function prepareForValidation()
    {
        //
    }
}
