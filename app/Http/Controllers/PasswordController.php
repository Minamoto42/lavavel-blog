<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordController extends Controller
{

    /**
     * Show the form to request a password reset link.
     *
     * @return Factory|View|Application
     */
    public function showLinkRequestForm(): Factory|View|Application
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        // 1、验证邮箱
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // 2、获取对应的用户
        $user = User::where('email', $email)->first();

        // 3、如果用户不存在
        if (is_null($user)) {
            session()->flash('danger', 'Email not found.');
            return redirect()->back()->withInput();
        }

        // 4、生成 Token，会在视图 emails.reset_link 里拼接链接
        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        // 5、将 Token 存储到数据库中, 使用 updateOrInsert 方法来确保每个用户(邮箱)只有一个重置密码的 Token
        DB::table('password_resets')->updateOrInsert(['email' => $email], [
            'email' => $email,
            'token' => $token,
            'created_at' => new Carbon,
        ]);

        // 6、将 Token 链接发送给用户
        Mail::send('emails.reset_link', compact('token'), function ($message) use ($email) {
            $message->to($email)->subject('忘记密码');
        });

        session()->flash('success', 'The reset email was sent successfully, please check it.');
        return redirect()->back();
    }

    /**
     * Show the form to reset the password.
     *
     * @param string $token
     * @return Factory|View|Application
     */
    public function showResetForm(string $token): Factory|View|Application
    {
        return view('auth.passwords.reset', compact('token'));
    }

    /**
     * Reset the password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function reset(Request $request): RedirectResponse
    {
        // 1、验证
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);
        $email = $request->email;
        $token = $request->token;
        $expires = 60 * 10; // 10 分钟

        // 2、获取用户
        $user = User::where('email', $email)->first();

        // 3、if用户不存在
        if (is_null($user)) {
            session()->flash('danger', 'Email not found.');
            return redirect()->back()->withInput();
        }

        // 4、读取重置密码记录
        $record = (array)DB::table('password_resets')->where('email', $email)->first();

        // 5、记录存在
        if ($record) {
            // 5.1、检查是否过期
            if (Carbon::parse($record['created_at'])->addSeconds($expires)->isPast()) {
                session()->flash('danger', 'The reset link has expired.');
                return redirect()->back();
            }

            // 5.2、检查 Token 是否正确
            if (!hash_equals($record['token'], $token)) {
                session()->flash('danger', 'The reset link is incorrect.');
                return redirect()->back();
            }

            // 5.3、更新密码
            $user->update(['password' => bcrypt($request->password)]);

            // 5.4、提示更新成功
            session()->flash('success', 'Password reset successfully, please login with the new password.');
            return redirect()->route('login');
        }

        // 6、if记录不存在
        session()->flash('danger', 'The reset link is incorrect.');
        return redirect()->back();
    }
}
