<?php

namespace Botble\webrobotdashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\ACL\Traits\RegistersUsers;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\webrobotdashboard\Models\Member;
use Botble\webrobotdashboard\Repositories\Interfaces\MemberInterface;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use SeoHelper;
use Theme;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = null;

    /**
     * @var MemberInterface
     */
    protected $memberRepository;

    /**
     * Create a new controller instance.
     *
     * @param MemberInterface $memberRepository
     */
    public function __construct(MemberInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * Show the application registration form.
     *
     * @return Factory|View|Response
     */
    public function showRegistrationForm()
    {
        SeoHelper::setTitle(__('Register'));

        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(__('Register'), route('public.member.register'));

        if (view()->exists(Theme::getThemeNamespace() . '::views.member.auth.register')) {
            return Theme::scope('member.auth.register')->render();
        }

        return view('plugins/webrobotdashboard::auth.register');
    }

    /**
     * Confirm a user with a given confirmation code.
     *
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @param MemberInterface $memberRepository
     * @return BaseHttpResponse
     */
    public function confirm($id, Request $request, BaseHttpResponse $response, MemberInterface $memberRepository)
    {
        if (!URL::hasValidSignature($request)) {
            abort(404);
        }

        $member = $memberRepository->findOrFail($id);

        $member->confirmed_at = Carbon::now();
        $this->memberRepository->createOrUpdate($member);

        $this->guard()->login($member);

        return $response
            ->setNextUrl(route('public.member.dashboard'))
            ->setMessage(trans('plugins/webrobotdashboard::member.confirmation_successful'));
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return auth('member');
    }

    /**
     * Resend a confirmation code to a user.
     *
     * @param Request $request
     * @param MemberInterface $memberRepository
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function resendConfirmation(Request $request, MemberInterface $memberRepository, BaseHttpResponse $response)
    {
        $member = $memberRepository->getFirstBy(['email' => $request->input('email')]);
        if (!$member) {
            return $response
                ->setError()
                ->setMessage(__('Cannot find this account!'));
        }

        $this->sendConfirmationToUser($member);

        return $response
            ->setMessage(trans('plugins/webrobotdashboard::member.confirmation_resent'));
    }

    /**
     * Send the confirmation code to a user.
     *
     * @param Member $member
     */
    protected function sendConfirmationToUser($member)
    {
        // Notify the user
        $notificationConfig = config('plugins.webrobotdashboard.general.notification');
        if ($notificationConfig) {
            $notification = app($notificationConfig);
            $member->notify($notification);
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function register(Request $request, BaseHttpResponse $response)
    {
        $this->validator($request->input())->validate();

        event(new Registered($member = $this->create($request->input())));

        if (setting('verify_account_email', config('plugins.webrobotdashboard.general.verify_email'))) {
            $this->sendConfirmationToUser($member);

            return $this->registered($request, $member)
                ?: $response->setNextUrl($this->redirectPath())
                    ->setMessage(trans('plugins/webrobotdashboard::member.confirmation_info'));
        }

        $member->confirmed_at = Carbon::now();
        $this->memberRepository->createOrUpdate($member);
        $this->guard()->login($member);

        return $this->registered($request, $member)
            ?: $response->setNextUrl($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'first_name' => 'required|max:120',
            'last_name' => 'required|max:120',
            'email' => 'required|email|max:255|unique:members',
            'password' => 'required|min:6|confirmed',
        ];

        if (is_plugin_active('captcha') && setting('enable_captcha') && setting(
            'member_enable_recaptcha_in_register_page',
            0
        )) {
            $rules += ['g-recaptcha-response' => 'required|captcha'];
        }

        return Validator::make($data, $rules, [
            'g-recaptcha-response.required' => __('Captcha Verification Failed!'),
            'g-recaptcha-response.captcha' => __('Captcha Verification Failed!'),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return Member
     */
    protected function create(array $data)
    {
        return $this->memberRepository->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * @return Factory|Application|View
     */
    public function getVerify()
    {
        return view('plugins/webrobotdashboard::auth.verify');
    }
}
