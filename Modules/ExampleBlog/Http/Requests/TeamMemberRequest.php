<?php

namespace Modules\ExampleBlog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\ExampleBlog\Models\TeamMember;

class TeamMemberRequest extends FormRequest
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

            'user_id' => 'required|integer',
            'team_id' => 'required|integer',
            'role_name' => 'required|string',
            'is_active' => 'required|boolean',

        ];

        if (strtolower($method) == 'put') {
            // $item = $this->route('teamMember');
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
            $teamMember = new TeamMember;
            return \Gate::allows('create', $teamMember);
        }

        if (strtolower($method) == 'put') {
            $teamMember = $this->route('team_member');
            return \Gate::allows('update', $teamMember);
        }
    }

    protected function prepareForValidation()
    {
        //
    }
}
