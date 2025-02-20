<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StatusesController extends Controller
{

    /**
     * StatusesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Published status.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);

        session()->flash('success', 'Published successfully!');
        return redirect()->back();
    }

    /**
     * Delete status.
     *
     * @param Status $status
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Status $status): RedirectResponse
    {
        $this->authorize('destroy', $status);
        $status->delete();

        session()->flash('success', 'Deleted successfully!');
        return redirect()->back();
    }
}
