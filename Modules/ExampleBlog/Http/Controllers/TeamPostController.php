<?php

namespace Modules\ExampleBlog\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Models\Team;
use Modules\ExampleBlog\Services\PostService;
use Modules\ExampleBlog\Http\Requests\TeamPostRequest;

class TeamPostController extends Controller
{
    public $service;
    public $data;

    public function __construct()
    {
        $this->service = new PostService;

        $teamId = request()->route('team');
        $team = Team::find($teamId);
        $tags = $team->tags->pluck('name', 'id');
        $this->data = [
            'dropdown' => [
                'statuses' => [
                    'draft' =>  __('exampleblog::post.form.dropdown.statuses.draft'),
                    'published' =>  __('exampleblog::post.form.dropdown.statuses.published'),
                    'archived' =>  __('exampleblog::post.form.dropdown.statuses.archived'),
                ]
            ],
            'tags' => $tags,
        ];
    }

    public function index(Team $team)
    {
        $this->authorize('viewTeamPosts', $team);
        $posts = $this->service->getTeamPosts($team);
        return view('exampleblog::team-posts.index', compact(
            'team', 'posts'
        ));
    }

    public function create(Team $team)
    {
        $this->authorize('createTeamPost', $team);
        $post = new Post;
        $dropdown = $this->data['dropdown'];
        $tags = $this->data['tags'];
        return view('exampleblog::team-posts.create', compact(
            'team', 'post',
            'dropdown', 'tags'
        ));
    }

    public function store(TeamPostRequest $request, Team $team)
    {
        $this->authorize('createTeamPost', $team);
        $data = $request->validated();
        $this->service->publishForTeam($team);
        $this->service->create($data);

        $message = __('my_app.messages.data_created');
        flash($message)->success();
        return redirect()->route('example-blog.teams.posts.index', [$team->id]);
    }

    public function show(Team $team, Post $post)
    {
        $this->authorize('viewTeamPost', $team);
        $dropdown = $this->data['dropdown'];
        $tags = $this->data['tags'];
        return view('exampleblog::team-posts.show', compact(
            'post', 'team',
            'dropdown', 'tags'
        ));
    }

    public function edit(Team $team, Post $post)
    {
        $this->authorize('editTeamPost', [$team, $post]);
        $dropdown = $this->data['dropdown'];
        $tags = $this->data['tags'];
        return view('exampleblog::team-posts.edit', compact(
            'team', 'post',
            'dropdown', 'tags'
        ));
    }

    public function update(TeamPostRequest $request, Team $team, Post $post)
    {
        $this->authorize('editTeamPost', [$team, $post]);
        $data = $request->validated();
        $this->service->update($post, $data);

        $message = __('my_app.messages.data_updated');
        flash($message)->success();
        return redirect()->route('example-blog.teams.posts.index', [$team->id]);
    }

    public function destroy(Team $team, Post $post)
    {
        $this->authorize('deleteTeamPost', [$team, $post]);
        $this->service->delete($post);

        $message = __('my_app.messages.data_deleted');
        flash($message)->success();
        return redirect()->route('example-blog.teams.posts.index', [$team->id]);
    }
}
