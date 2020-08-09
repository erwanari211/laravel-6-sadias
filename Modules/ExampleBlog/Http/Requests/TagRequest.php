<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExampleBlog\Models\Tag;

class TagRequest extends FormRequest
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

            'owner_id' => 'required|integer',
            'name' => 'required|string',
            'slug' => 'required|string',
            'description' => 'nullable',
            'is_active' => 'required|boolean',

        ];

        if (strtolower($method) == 'put') {
            // $item = $this->route('tag');
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
            $tag = new Tag;
            return \Gate::allows('create', $tag);
        }

        if (strtolower($method) == 'put') {
            $tag = $this->route('tag');
            return \Gate::allows('update', $tag);
        }
    }

    protected function prepareForValidation()
    {
        //
    }
}
