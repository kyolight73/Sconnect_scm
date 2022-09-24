<?php
namespace App\Services;

use Illuminate\Support\Facades\Gate;

class PermissionGateAndPolicyAccess{

    public function setGateAndPolicyAccess(){

        $this->defineMedia();
        $this->defineAdmin();


    }
    public function defineMedia(){
        Gate::define('origin-product','App\Policies\MediaPolicy@view');
        Gate::define('platform','App\Policies\MediaPolicy@platform');
        Gate::define('topic','App\Policies\MediaPolicy@topic');
        Gate::define('channel-type','App\Policies\MediaPolicy@channel_type');
        Gate::define('channel','App\Policies\MediaPolicy@channel');
        Gate::define('video','App\Policies\MediaPolicy@video');
    }
    public function defineAdmin(){
        Gate::define('department','App\Policies\AdminPolicy@department');
        Gate::define('setting','App\Policies\AdminPolicy@setting');
        Gate::define('staff','App\Policies\AdminPolicy@staff');
        Gate::define('config','App\Policies\AdminPolicy@config');
    }


}
