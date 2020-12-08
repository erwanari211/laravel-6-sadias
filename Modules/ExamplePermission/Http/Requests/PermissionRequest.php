<?php

namespace Modules\ExamplePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExamplePermission\Models\Permission;

class PermissionRequest extends FormRequest
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

            'name' => 'required|unique:permissions,name',
            'guard_name' => 'nullable',

        ];

        if (strtolower($method) == 'put') {
            $item = $this->route('permission');
            $rules['name'] = 'required|unique:permissions,name,' . $item->id;
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
            $permission = new Permission;
            return \Gate::allows('create', $permission);
        }

        if (strtolower($method) == 'put') {
            $permission = $this->route('permission');
            return \Gate::allows('update', $permission);
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
