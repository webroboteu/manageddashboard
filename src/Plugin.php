<?php

namespace Botble\webrobotdashboard;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('member_activity_logs');
        Schema::dropIfExists('member_password_resets');
        Schema::dropIfExists('members');
        Schema::dropIfExists('member_project');
        Schema::dropIfExists('member_project_tasks');
        Setting::query()
            ->whereIn('key', [
                'verify_account_email',
                'member_enable_recaptcha_in_register_page',
            ])
            ->delete();
    }
}
