<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ExampleBlog\Entities\ExampleBlogChannel as Channel;

class ExampleBlogChannelRequest extends FormRequest
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
            'slug' => 'required|unique:example_blog_channels,slug',
            'description' => 'nullable',
        ];

        if (strtolower($method) == 'put') {
            $item = $this->route('channel');
            $rules['slug'] = 'required|unique:example_blog_channels,slug,' . $item->id;
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
            $channel = new Channel;
            return \Gate::allows('create', $channel);
        }

        if (strtolower($method) == 'put') {
            $channel = $this->route('channel');
            return \Gate::allows('update', $channel);
        }

        return true;
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
