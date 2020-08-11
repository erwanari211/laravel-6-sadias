<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Support\Str;
use Modules\ExampleBlog\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class TeamPostRequest extends FormRequest
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

            'title' => 'required|string',
            'slug' => 'required|string',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',

        ];

        if (strtolower($method) == 'put') {
            $item = $this->route('post');
            // $rules['slug'] = 'required|string';
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
        $team = $this->route('team');
        $user = auth()->user();

        if (strtolower($method) == 'post') {
            return \Gate::authorize('createTeamPost', $team);
        }

        if (strtolower($method) == 'put') {
            $post = $this->route('post');
            return \Gate::authorize('editTeamPost', [$team, $post]);
        }
    }

    protected function prepareForValidation()
    {
        if ($this->has('slug')){
            $this->merge([
                'slug' => Str::slug($this->slug)
            ]);
        }
    }
}
