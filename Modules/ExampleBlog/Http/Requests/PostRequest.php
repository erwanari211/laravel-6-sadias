<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Support\Str;
use Modules\ExampleBlog\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

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
            'slug' => 'required|string|unique:example_blog_posts,slug',
            'content' => 'nullable',

        ];

        if (strtolower($method) == 'put') {
            $item = $this->route('post');
            $rules['slug'] = 'required|unique:example_blog_posts,slug,' . $item->id;
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
        if ($this->has('slug')){
            $this->merge([
                'slug' => Str::slug($this->slug)
            ]);
        }
    }
}
