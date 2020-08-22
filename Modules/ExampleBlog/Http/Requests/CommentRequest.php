<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExampleBlog\Models\Comment;

class CommentRequest extends FormRequest
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

            'author_id' => 'required|integer|min:0',
            'post_id' => 'required|integer|min:0',
            'parent_id' => 'nullable|integer|min:0',
            'content' => 'required|string',
            'is_approved' => 'required|boolean',
            'status' => 'required|string|in:published,hidden',

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
            $comment = new Comment;
            return \Gate::allows('create', $comment);
        }

        if (strtolower($method) == 'put') {
            $comment = $this->route('comment');
            return \Gate::allows('update', $comment);
        }
    }

    protected function prepareForValidation()
    {
        //
    }
}
