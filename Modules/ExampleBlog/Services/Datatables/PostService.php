<?php

namespace Modules\ExampleBlog\Services\Datatables;

use Illuminate\Support\Str;
use Modules\ExampleBlog\Models\Post;
use Modules\ExampleBlog\Http\Resources\PostResource;
use DataTables;

class PostService
{
    public $model;
    public $perPage = 10;
    public $data;
    public $publishForTeam = false;
    public $team;

    public function __construct()
    {
        $this->model = new Post;
    }

    public function getUserPosts()
    {
        $user = auth()->user();
        $data = $this->model->query()
            ->with('author')
            ->where('author_id', $user->id)
            ->where('postable_id', $user->id)
            ->where('postable_type', get_class($user));

        $data = $this->getUserPostsCustomFilter($data);
        $result = $this->getUserPostsFormatted($data);
        return $result;
    }

    public function getUserPostsCustomFilter($data)
    {
        $start_date = request('start_date');
        $end_date = request('end_date');

        if($start_date && $end_date){
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));
            $data = $data->whereBetween('created_at', [$start_date, $end_date]);
        }

        return $data;
    }

    public function getUserPostsFormatted($data)
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('links', function($item) {
                $viewUrl = route('example-blog.posts.show', [$item->id]);
                $editUrl = route('example-blog.posts.edit', [$item->id]);
                $deleteUrl = route('example-blog.posts.destroy', [$item->id]);
                return compact('viewUrl', 'editUrl', 'deleteUrl');
            })
            ->addColumn('options', function($item) {
                $viewUrl = route('example-blog.posts.show', [$item->id]);
                $editUrl = route('example-blog.posts.edit', [$item->id]);
                $output = "
                    <a class='btn btn-secondary btn-sm' href='{$viewUrl}'>
                        View
                    </a>
                    <a class='btn btn-success btn-sm' href='{$editUrl}'>
                        Edit
                    </a>
                    <a class='btn btn-danger btn-sm' href='{$item->id}'>
                        Delete
                    </a>
                ";
                return trim($output);
            })
            ->rawColumns(['options'])
            ->editColumn('status', function($item) {
                return $item->status == 'published' ? 'PUBLISHED' : $item->status;
            })
            ->setRowClass(function ($item) {
                return $item->status == 'published' ? 'post-published' : '';
            })
            ->toJson();
    }
}
