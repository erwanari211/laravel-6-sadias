<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Services\TeamService;
use Modules\ExampleBlog\Http\Requests\TeamRequest;

class TeamController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new TeamService;
        $this->data = [];
    }

    public function index()
    {
        $this->authorize('viewAny', Team::class);
        $teams = $this->service->getData();
        return view('exampleblog::teams.index', compact('teams'));
    }

    public function create()
    {
        $this->authorize('create', Team::class);
        $team = new Team;
        return view('exampleblog::teams.create', compact('team'));
    }

    public function store(TeamRequest $request)
    {
        $this->authorize('create', Team::class);
        $data = $request->validated();
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.teams.index');
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);
        return view('exampleblog::teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $this->authorize('update', $team);
        return view('exampleblog::teams.edit', compact('team'));
    }

    public function update(TeamRequest $request, Team $team)
    {
        $this->authorize('update', $team);
        $data = $request->validated();
        $this->service->update($team, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.teams.index');
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);
        $this->service->delete($team);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.teams.index');
    }
}
