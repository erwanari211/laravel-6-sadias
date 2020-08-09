<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExampleBlog\Models\Team;

class TeamRequest extends FormRequest
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
            'slug' => 'required|string|unique:example_blog_teams,slug',
            'description' => 'nullable',
            'is_active' => 'required|boolean',

        ];

        if (strtolower($method) == 'put') {
            $item = $this->route('team');
            $rules['slug'] = 'required|string|unique:example_blog_teams,slug,' . $item->id;
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
            $team = new Team;
            return \Gate::allows('create', $team);
        }

        if (strtolower($method) == 'put') {
            $team = $this->route('team');
            return \Gate::allows('update', $team);
        }
    }

    protected function prepareForValidation()
    {
        //
    }
}
