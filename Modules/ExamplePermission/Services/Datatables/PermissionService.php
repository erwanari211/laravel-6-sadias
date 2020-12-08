<?php

namespace Modules\ExamplePermission\Services\Datatables;

use Illuminate\Support\Str;
use Modules\ExamplePermission\Models\Permission;
use Modules\ExamplePermission\Http\Resources\PermissionResource;
use DataTables;

class PermissionService
{
    public $model;
    public $data;

    public function __construct()
    {
        $this->model = new Permission;
    }

    public function getData()
    {
        $user = auth()->user();
        $data = $this->model->query();

        $data = $this->filterData($data);
        $result = $this->formatData($data);
        return $result;
    }

    public function filterData($data)
    {
        return $data;
    }

    public function formatData($data)
    {
        $label = [
            'view' => __('my_app.crud.show'),
            'edit' => __('my_app.crud.edit'),
            'delete' => __('my_app.crud.delete'),
        ];

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('links', function($item) {
                $viewUrl = route('example-permission.permissions.show', [$item->id]);
                $editUrl = route('example-permission.permissions.edit', [$item->id]);
                $deleteUrl = route('example-permission.permissions.destroy', [$item->id]);
                return compact('viewUrl', 'editUrl', 'deleteUrl');
            })
            ->addColumn('options', function($item) use ($label) {
                $viewUrl = route('example-permission.permissions.show', [$item->id]);
                $editUrl = route('example-permission.permissions.edit', [$item->id]);
                $deleteUrl = route('example-permission.permissions.destroy', [$item->id]);

                $viewBtn = "<a class='btn btn-secondary btn-sm mb-1' href='{$viewUrl}'>{$label['view']}</a>";
                $editBtn = "<a class='btn btn-success btn-sm mb-1' href='{$editUrl}'>{$label['edit']}</a>";
                $deleteBtn = "
                    <form method='POST' action='" . $deleteUrl . "' style='display: inline'>
                        <input name='_method' type='hidden' value='DELETE'>
                        <input name='_token' type='hidden' value='" . csrf_token() . "'>
                        <button class='btn btn-danger btn-sm mb-1' type='submit' onclick='return confirm(\"{$label['delete']}?\")'>
                            {$label['delete']}
                        </button>
                    </form>
                ";
                $output = "
                    {$viewBtn}
                    {$editBtn}
                    {$deleteBtn}
                ";
                return trim($output);
            })
            ->rawColumns(['options'])
            ->editColumn('status', function($item) {
                return $item->active == 'active' ? 'ACTIVE' : 'INACTIVE';
            })
            ->setRowClass(function ($item) {
                return $item->active == 'active' ? 'active-class' : 'inactive-class';
            })
            ->toJson();
    }
}
