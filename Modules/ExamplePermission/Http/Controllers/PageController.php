<?php

namespace Modules\ExamplePermission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PageController extends Controller
{

    public function index()
    {
        return view('examplepermission::pages.index');
    }

    public function superAdmin()
    {
        /** @var $user \App\User */
        $user = auth()->user();
        $hasAccess = $user->hasRole('p-super-admin');

        $pageName = $hasAccess ? 'Super Admin' : 404;
        return view('examplepermission::pages.page')->with([
            'pageName' => $pageName,
        ]);
    }

    public function admin()
    {
        /** @var $user \App\User */
        $user = auth()->user();
        $hasAccess = $user->hasRole(['p-super-admin', 'p-admin']);

        $pageName = $hasAccess ? 'Admin' : 404;
        return view('examplepermission::pages.page')->with([
            'pageName' => $pageName,
        ]);
    }

    public function normal()
    {
        /** @var $user \App\User */
        $user = auth()->user();
        $hasAccess = $user->hasRole(['p-super-admin', 'p-admin', 'p-writer']);

        $pageName = $hasAccess ? 'Normal' : 404;
        return view('examplepermission::pages.page')->with([
            'pageName' => $pageName,
        ]);
    }
}
