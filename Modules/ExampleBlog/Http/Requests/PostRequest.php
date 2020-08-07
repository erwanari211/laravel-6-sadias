<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExampleBlog\Models\Post;

class PostRequest extends FormRequest
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

            'author_id' => 'required|integer',
            'title' => 'required|string',
            'slug' => 'required|string',
            'content' => 'nullable',

        ];

        if (strtolower($method) == 'put') {
            //
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
            $post = new Post;
            return \Gate::allows('create', $post);
        }

        if (strtolower($method) == 'put') {
            $post = $this->route('post');
            return \Gate::allows('update', $post);
        }
    }

    protected function prepareForValidation()
    {
        //
    }
}
