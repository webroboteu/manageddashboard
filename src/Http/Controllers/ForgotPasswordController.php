<?php

namespace Botble\webrobotdashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\ACL\Traits\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use SeoHelper;
use Theme;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function showLinkRequestForm()
    {
        SeoHelper::setTitle(trans('plugins/webrobotdashboard::member.forgot_password'));

        if (view()->exists(Theme::getThemeNamespace() . '::views.member.auth.passwords.email')) {
            return Theme::scope('webrobotdashboard.auth.passwords.email')->render();
        }

        return view('plugins/webrobotdashboard::auth.passwords.email');
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('members');
    }
}
