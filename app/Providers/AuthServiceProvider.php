<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
         'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
//        $this->registerPolicies();
//
//        $permissions = [
//            //admin
//            'dashboard_admin',
//            'manage_expert',
//            'expert_assignment',
//            'manage_ticket_admin',
//            'view_all_requests',
//            'view_request_without_expert', //get_request_without_expert
//            'view_counts',
//            'manage_users',
//            'view_request_delete',
//            'confirm_report',
//            'view_report',
//            'confirm_committee',
//            'view_committee',
//
//            //expert
//            'dashboard_expert',
//            'manage_check_document',
//            'manage_start_assessment',
//            'manage_evaluation_report',
//            'manage_committee',
//            'manage_credit',
//            'manage_ticket_expert',
//            'view_request', //get_request_with_expert/{id}
//            'view_current_requests_expert',
//            'view_count_projects',
//
//            //user
//            'dashboard_user',
//            'manage_profile_genuine',
//            'manage_profile_legal',
//            'view_expert',
//            'view_steps',
//            'manage_ticket_user',
//            'manage_request',
//            'request_delete',
//            'view_current_requests_user',
//            'update_user'
//        ];
//        foreach ($permissions as $permission) {
//            Permission::firstOrCreate(['name' => $permission]);
//        }
//        $roles = [
//            'admin',
//            'expert',
//            'user',
//        ];
//        foreach ($roles as $role) {
//            Role::firstOrCreate(['name' => $role]);
//        }
//        $role1 = Role::findByName('admin');
//        $role1->givePermissionTo('dashboard_admin');
//        $role1->givePermissionTo('manage_expert');
//        $role1->givePermissionTo('expert_assignment');
//        $role1->givePermissionTo('manage_ticket_admin');
//        $role1->givePermissionTo('view_all_requests');
//        $role1->givePermissionTo('view_request_without_expert');
//        $role1->givePermissionTo('view_counts');
//        $role1->givePermissionTo('manage_users');
//        $role1->givePermissionTo('view_request_delete');
//        $role1->givePermissionTo('confirm_report');
//        $role1->givePermissionTo('view_report');
//        $role1->givePermissionTo('confirm_committee');
//        $role1->givePermissionTo('view_committee');
//
//        $role2 = Role::findByName('expert');
//        $role2->givePermissionTo('dashboard_expert');
//        $role2->givePermissionTo('manage_check_document');
//        $role2->givePermissionTo('manage_start_assessment');
//        $role2->givePermissionTo('manage_evaluation_report');
//        $role2->givePermissionTo('manage_committee');
//        $role2->givePermissionTo('manage_credit');
//        $role2->givePermissionTo('manage_ticket_expert');
//        $role2->givePermissionTo('view_request');
//        $role2->givePermissionTo('view_current_requests_expert');
//        $role2->givePermissionTo('view_count_projects');
//
//        $role3 = Role::findByName('user');
//        $role3->givePermissionTo('dashboard_user');
//        $role3->givePermissionTo('manage_profile_genuine');
//        $role3->givePermissionTo('manage_profile_legal');
//        $role3->givePermissionTo('view_expert');
//        $role3->givePermissionTo('view_steps');
//        $role3->givePermissionTo('manage_ticket_user');
//        $role3->givePermissionTo('manage_request');
//        $role3->givePermissionTo('request_delete');
//        $role3->givePermissionTo('view_current_requests_user');
//        $role3->givePermissionTo('update_user');
    }
}
