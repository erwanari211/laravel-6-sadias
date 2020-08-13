<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Models\Tag;
use Modules\ExampleBlog\Services\TagService;
use Modules\ExampleBlog\Http\Requests\TagRequest;

class TeamTagController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new TagService;
        $this->data = [
            'dropdown' => [
                'yes_no' => [
                    1 => __('my_app.yes'),
                    0 => __('my_app.no'),
                ],
            ],
        ];
    }

    public function index(Team $team)
    {
        $this->authorize('view', $team);
        $tags = $this->service->getTeamTags($team);
        return view('exampleblog::team-tags.index', compact(
            'team', 'tags'
        ));
    }

    public function create(Team $team)
    {
        $this->authorize('update', $team);
        $tag = new Tag;
        $dropdown = $this->data['dropdown'];
        return view('exampleblog::team-tags.create', compact(
            'team', 'tag',
            'dropdown'
        ));
    }

    public function store(TagRequest $request, Team $team)
    {
        $this->authorize('update', $team);
        $data = $request->validated();
        $this->service->setTagOwner('team', $team);
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.teams.tags.index', [$team->id]);
    }

    public function show(Team $team, Tag $tag)
    {
        $this->authorize('view', $team);
        $dropdown = $this->data['dropdown'];
        return view('exampleblog::team-tags.show', compact(
            'team', 'tag',
            'dropdown'
        ));
    }

    public function edit(Team $team, Tag $tag)
    {
        $this->authorize('update', $team);
        $dropdown = $this->data['dropdown'];
        return view('exampleblog::team-tags.edit', compact(
            'team', 'tag',
            'dropdown'
        ));
    }

    public function update(TagRequest $request, Team $team, Tag $tag)
    {
        $this->authorize('update', $team);
        $data = $request->validated();
        $this->service->update($tag, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.teams.tags.index', [$team->id]);
    }

    public function destroy(Team $team, Tag $tag)
    {
        $this->authorize('update', $team);
        $this->service->delete($tag);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.teams.tags.index', [$team->id]);
    }
}
