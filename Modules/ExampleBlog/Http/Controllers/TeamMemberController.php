<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Services\TeamMemberService;
use Modules\ExampleBlog\Http\Requests\TeamMemberRequest;

class TeamMemberController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $teamId = request()->route('team');
        $team = Team::find($teamId);
        // dd($team);
        $this->service = new TeamMemberService($team);
        $this->data = [
            'dropdown' => [
                'yes_no' => [
                    1 => __('my_app.yes'),
                    0 => __('my_app.no'),
                ],
                'roles' => [
                    'admin' => __('exampleblog::team_member.form.dropdown.roles.admin'),
                    'editor' => __('exampleblog::team_member.form.dropdown.roles.editor'),
                    'author' => __('exampleblog::team_member.form.dropdown.roles.author'),
                ],
            ],
        ];
    }

    public function index(Team $team)
    {
        $this->authorize('viewAny', TeamMember::class);
        $teamMembers = $this->service->getData($team);
        return view('exampleblog::team-members.index', compact('teamMembers', 'team'));
    }

    public function create(Team $team)
    {
        $this->authorize('create', TeamMember::class);
        $teamMember = new TeamMember;
        $dropdown = $this->data['dropdown'];
        return view('exampleblog::team-members.create', compact(
            'teamMember', 'team',
            'dropdown'
        ));
    }

    public function store(TeamMemberRequest $request, Team $team)
    {
        $this->authorize('create', TeamMember::class);
        $data = $request->validated();
        $isCreated = $this->service->create($data);
        if (!$isCreated) {
            $message = __('exampleblog::team_member.messages.user_already_exists');
            flash($message)->error();
            return redirect()->route('example-blog.team-members.index', [$team->id]);
        }

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.team-members.index', [$team->id]);
    }

    public function show(Team $team, TeamMember $teamMember)
    {
        $this->authorize('view', $teamMember);
        $dropdown = $this->data['dropdown'];
        return view('exampleblog::team-members.show', compact(
            'teamMember', 'team',
            'dropdown'
        ));
    }

    public function edit(Team $team, TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);
        $dropdown = $this->data['dropdown'];
        return view('exampleblog::team-members.edit', compact(
            'teamMember', 'team',
            'dropdown'
        ));
    }

    public function update(TeamMemberRequest $request, Team $team, TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);
        $data = $request->validated();
        $isUpdated = $this->service->update($teamMember, $data);
        if (!$isUpdated) {
            $message = __('exampleblog::team_member.messages.owner_cannot_be_updated');
            flash($message)->error();
            return redirect()->route('example-blog.team-members.index', [$team->id]);
        }

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.team-members.index', [$team->id]);
    }

    public function destroy(Team $team, TeamMember $teamMember)
    {
        $this->authorize('delete', $teamMember);
        $isDeleted = $this->service->delete($teamMember);
        if (!$isDeleted) {
            $message = __('exampleblog::team_member.messages.owner_cannot_be_updated');
            flash($message)->error();
            return redirect()->route('example-blog.team-members.index', [$team->id]);
        }

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.team-members.index', [$team->id]);
    }
}
