<?php

namespace Botble\webrobotdashboard\Providers;

use ApiHelper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Blog\Models\Post;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\webrobotdashboard\Http\Middleware\RedirectIfMember;
use Botble\webrobotdashboard\Http\Middleware\RedirectIfNotMember;
use Botble\webrobotdashboard\Models\Member;
use Botble\webrobotdashboard\Models\Task;
use Botble\webrobotdashboard\Models\Project;
use Botble\webrobotdashboard\Models\MemberActivityLog;
use Botble\webrobotdashboard\Repositories\Caches\MemberActivityLogCacheDecorator;
use Botble\webrobotdashboard\Repositories\Caches\MemberCacheDecorator;
use Botble\webrobotdashboard\Repositories\Caches\ProjectCacheDecorator;
use Botble\webrobotdashboard\Repositories\Caches\TaskCacheDecorator;
use Botble\webrobotdashboard\Repositories\Eloquent\MemberActivityLogRepository;
use Botble\webrobotdashboard\Repositories\Eloquent\MemberRepository;
use Botble\webrobotdashboard\Repositories\Eloquent\ProjectRepository;
use Botble\webrobotdashboard\Repositories\Eloquent\TaskRepository;
use Botble\webrobotdashboard\Repositories\Interfaces\MemberActivityLogInterface;
use Botble\webrobotdashboard\Repositories\Interfaces\MemberInterface;
use Botble\webrobotdashboard\Repositories\Interfaces\ProjectInterface;
use Botble\webrobotdashboard\Repositories\Interfaces\TaskInterface;
use EmailHandler;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Language;
use SocialService;
use Theme;
class MemberServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    /**
     * @throws BindingResolutionException
     */
    public function register()
    {
        config([
            'auth.guards.member' => [
                'driver' => 'session',
                'provider' => 'members',
            ],
            'auth.providers.members' => [
                'driver' => 'eloquent',
                'model' => Member::class,
            ],
            'auth.passwords.members' => [
                'provider' => 'members',
                'table' => 'member_password_resets',
                'expire' => 60,
            ],
        ]);

        $router = $this->app->make('router');

        $router->aliasMiddleware('member', RedirectIfNotMember::class);
        $router->aliasMiddleware('member.guest', RedirectIfMember::class);

        
        $this->app->bind(ProjectInterface::class, function () {
            return new ProjectCacheDecorator(new ProjectRepository(new Project()));
        });

        $this->app->bind(TaskInterface::class, function () {
            return new TaskCacheDecorator(new TaskRepository(new Task()));
        });


        $this->app->bind(MemberInterface::class, function () {
            return new MemberCacheDecorator(new MemberRepository(new Member()));
        });


        $this->app->bind(MemberActivityLogInterface::class, function () {
            return new MemberActivityLogCacheDecorator(new MemberActivityLogRepository(new MemberActivityLog()));
        });

        add_filter(IS_IN_ADMIN_FILTER, [$this, 'setInAdmin'], 24);
    }

    public function boot()
    {
        $this->setNamespace('plugins/webrobotdashboard')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general', 'permissions', 'assets', 'email'])
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web'])
            ->loadMigrations()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-core-member',
                'priority' => 22,
                'parent_id' => null,
                'name' => 'plugins/webrobotdashboard::member.menu_name',
                'icon' => 'fa fa-users',
                'url' => route('member.index'),
                'permissions' => ['member.index'],
            ]);
        });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-core-member-projects',
                'priority' => 22,
                'parent_id' => null,
                'name' => 'plugins/webrobotdashboard::project.menu_name',
                'icon' => 'fa fa-projects',
                'url' => route('project.index'),
                'permissions' => ['project.index'],
            ]);
        });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-core-member-tasks',
                'priority' => 22,
                'parent_id' => null,
                'name' => 'plugins/webrobotdashboard::task.menu_name',
                'icon' => 'fa fa-tasks',
                'url' => route('task.index'),
                'permissions' => ['task.index'],
            ]);
        });

    
        if (class_exists('ApiHelper') && ApiHelper::enabled()) {
            ApiHelper::setConfig([
                'model' => Member::class,
                'guard' => 'member',
                'password_broker' => 'members',
                'verify_email' => true,
            ]);
        }

        $this->app->booted(function () {
            
          
            EmailHandler::addTemplateSettings(MEMBER_MODULE_SCREEN_NAME, config('plugins.weborobot-dashboard.email', []));

            if (defined('SOCIAL_LOGIN_MODULE_SCREEN_NAME') && !$this->app->runningInConsole() && Route::has('public.member.login')) {
                SocialService::registerModule([
                    'guard' => 'member',
                    'model' => Member::class,
                    'login_url' => route('public.member.login'),
                    'redirect_url' => route('public.member.dashboard'),
                ]);
            }
            if (defined('THEME_OPTIONS_MODULE_SCREEN_NAME') && !$this->app->isDownForMaintenance()) {
                Theme::asset()
                    ->usePath(false)
                    ->add(
                        'tabs-css',
                        asset('vendor/core/plugins/weborobotdashboard/css/tabs.css'),
                        [],
                        [],
                        '1.0.0'
                    );

                    Theme::asset()
                    ->usePath(false)
                    ->add(
                        'appreact',
                        asset('vendor/core/plugins/weborobotdashboard/js/appreact.js'),
                        [],
                        [],
                        '1.0.0'
                    );
            }
        });

        add_filter('social_login_before_saving_account', function ($data, $oAuth, $providerData) {
            if (Arr::get($providerData, 'model') == Member::class && Arr::get($providerData, 'guard') == 'member') {
                $firstName = implode(' ', explode(' ', $oAuth->getName(), -1));
                Arr::forget($data, 'name');
                $data = array_merge($data, [
                    'first_name' => $firstName,
                    'last_name' => trim(str_replace($firstName, '', $oAuth->getName())),
                ]);
            }

            return $data;
        }, 49, 3);

        $this->app->register(EventServiceProvider::class);

        add_action(BASE_ACTION_INIT, function () {
            if (defined('GALLERY_MODULE_SCREEN_NAME') && request()->segment(1) == 'account') {
                \Gallery::removeModule(Post::class);
            }
        }, 12, 2);

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 49);

        if (is_plugin_active('language') && is_plugin_active('language-advanced')) {
            add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
                if (is_in_admin() &&
                    request()->segment(1) === 'account' &&
                    Auth::guard('member')->check() &&
                    Language::getCurrentAdminLocaleCode() != Language::getDefaultLocaleCode() &&
                    $data &&
                    $data->id &&
                    LanguageAdvancedManager::isSupported($data)
                ) {
                    $refLang = null;

                    if (Language::getCurrentAdminLocaleCode() != Language::getDefaultLocaleCode()) {
                        $refLang = '?ref_lang=' . Language::getCurrentAdminLocaleCode();
                    }

                    $form->setFormOption(
                        'url',
                        route('public.member.language-advanced.save', $data->id) . $refLang
                    );
                }

                return $form;
            }, 9999, 2);
        }
    }

    /**
     * @param $isInAdmin
     * @return bool
     */
    public function setInAdmin($isInAdmin): bool
    {
        return request()->segment(1) === 'account' || $isInAdmin;
    }

    /**
     * @param string|null $data
     * @return string
     */
    public function addSettings(?string $data = null): string
    {
        return $data . view('plugins/webrobotdashboard::settings')->render();
    }
}
