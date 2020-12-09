<?php

namespace Modules\ExampleDocuments\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;

class UserMetaController extends Controller
{
    public function index()
    {
        $users = User::with('meta');
        if ($color = request('color')) {
            $users = $users->whereMeta('color', 'like', '%'.$color.'%');
        }
        if ($size = request('size')) {
            $users = $users->whereMeta('size', $size);
        }
        if ($minAge = request('min_age')) {
            $users = $users->whereMetaNumeric('age', '>=', $minAge);
        }
        if ($maxAge = request('max_age')) {
            $users = $users->whereMetaNumeric('age', '<=', $maxAge);
        }

        $users = $users->latest()->paginate();
        return view('exampledocuments::metas.users.index')->with([
            'users'  => $users,
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $meta = $user->getAllMeta();

        return view('exampledocuments::metas.users.show')->with([
            'user'  => $user,
            'meta'  => $meta,
        ]);
    }

    public function metaStore(Request $request, User $user)
    {
        $validated = request()->validate([
            'name' => 'required',
            'value' => 'required',
        ]);

        $user->setMeta($validated['name'], $validated['value']);

        session()->flash('successMessage', __('my_app.messages.data_created'));
        return redirect()->back();
    }

    public function metaDestroy(Request $request, User $user)
    {
        $metaKey = request('key');
        $user->removeMeta($metaKey);

        session()->flash('successMessage', __('my_app.messages.data_deleted'));
        return redirect()->back();
    }
}
