<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function __construct()
    {
        // 除了 show、create、store、index 方法，其他方法都需要登录
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index']
        ]);

        // 只允许未登录用户访问注册页面, 即 create 方法
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * Show all users.
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for signup.
     *
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        return view('users.create');
    }

    /**
     * Show user information.
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function show(User $user): View|Factory|Application
    {
        return view('users.show', compact('user'));
    }

    /**
     * Store a new user.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);
        session()->flash('success', 'Congratulations on your successful registration!');
        return redirect()->route('users.show', [$user]);
    }

    /**
     * Show the form for editing user information.
     *
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(User $user): Factory|View|Application
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * Update user information.
     *
     * @param User $user
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     * @throws AuthorizationException
     */
    public function update(User $user, Request $request): RedirectResponse
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', 'Update user information successful!');
        return redirect()->route('users.show', $user);
    }
}

