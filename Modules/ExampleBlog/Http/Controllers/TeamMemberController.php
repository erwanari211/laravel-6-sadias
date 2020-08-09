<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\TeamMember;
use Modules\ExampleBlog\Services\TeamMemberService;
use Modules\ExampleBlog\Http\Requests\TeamMemberRequest;

class TeamMemberController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new TeamMemberService;
        $this->data = [];
    }

    public function index()
    {
        $this->authorize('viewAny', TeamMember::class);
        $teamMembers = $this->service->getData();
        return view('exampleblog::team-members.index', compact('teamMembers'));
    }

    public function create()
    {
        $this->authorize('create', TeamMember::class);
        $teamMember = new TeamMember;
        return view('exampleblog::team-members.create', compact('teamMember'));
    }

    public function store(TeamMemberRequest $request)
    {
        $this->authorize('create', TeamMember::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.team-members.index');
    }

    public function show(TeamMember $teamMember)
    {
        $this->authorize('view', $teamMember);
        return view('exampleblog::team-members.show', compact('teamMember'));
    }

    public function edit(TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);
        return view('exampleblog::team-members.edit', compact('teamMember'));
    }

    public function update(TeamMemberRequest $request, TeamMember $teamMember)
    {
        $this->authorize('update', $teamMember);
        $data = $request->validated();
        $this->service->update($teamMember, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.team-members.index');
    }

    public function destroy(TeamMember $teamMember)
    {
        $this->authorize('delete', $teamMember);
        $this->service->delete($teamMember);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.team-members.index');
    }
}
