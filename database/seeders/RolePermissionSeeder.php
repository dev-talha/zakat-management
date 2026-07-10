<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions grouped by module
        $permissions = [
            // Dashboard
            'dashboard.view',
            // Users
            'users.view', 'users.create', 'users.edit', 'users.delete',
            // Roles
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            // Branches
            'branches.view', 'branches.create', 'branches.edit', 'branches.delete',
            // Donors
            'donors.view', 'donors.create', 'donors.edit', 'donors.delete', 'donors.kyc',
            // Beneficiaries
            'beneficiaries.view', 'beneficiaries.create', 'beneficiaries.edit', 'beneficiaries.delete',
            'beneficiaries.verify', 'beneficiaries.approve', 'beneficiaries.reject',
            // Cases
            'cases.view', 'cases.create', 'cases.edit', 'cases.assign', 'cases.approve', 'cases.close',
            // Verification
            'verification.view', 'verification.create', 'verification.review',
            // Collections
            'collections.view', 'collections.create', 'collections.reconcile',
            // Campaigns
            'campaigns.view', 'campaigns.create', 'campaigns.edit', 'campaigns.delete',
            // Funds
            'funds.view', 'funds.manage', 'funds.ledger',
            // Distribution
            'distributions.view', 'distributions.create', 'distributions.approve', 'distributions.release',
            // Reports
            'reports.view', 'reports.export', 'reports.financial',
            // Settings
            'settings.view', 'settings.edit',
            // Complaints
            'complaints.view', 'complaints.create', 'complaints.manage',
            // Audit
            'audit.view', 'audit.export',
            // AI
            'ai.view', 'ai.manage',
            // Blockchain
            'blockchain.view', 'blockchain.manage',
            // Shariah
            'shariah.review', 'shariah.approve',
            // Notifications
            'notifications.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Super Admin - gets all permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // System Admin
        $sysAdmin = Role::create(['name' => 'system_admin']);
        $sysAdmin->givePermissionTo([
            'dashboard.view', 'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'branches.view', 'branches.create',
            'branches.edit', 'settings.view', 'settings.edit', 'notifications.manage',
            'audit.view',
        ]);

        // Finance Admin
        $financeAdmin = Role::create(['name' => 'finance_admin']);
        $financeAdmin->givePermissionTo([
            'dashboard.view', 'funds.view', 'funds.manage', 'funds.ledger',
            'collections.view', 'collections.reconcile', 'distributions.view',
            'distributions.approve', 'distributions.release', 'reports.view',
            'reports.export', 'reports.financial', 'audit.view',
        ]);

        // Zakat Officer
        $zakatOfficer = Role::create(['name' => 'zakat_officer']);
        $zakatOfficer->givePermissionTo([
            'dashboard.view', 'beneficiaries.view', 'beneficiaries.verify',
            'beneficiaries.approve', 'beneficiaries.reject', 'cases.view',
            'cases.edit', 'cases.approve', 'distributions.view', 'distributions.create',
            'funds.view', 'reports.view',
        ]);

        // Shariah Board
        $shariahBoard = Role::create(['name' => 'shariah_board']);
        $shariahBoard->givePermissionTo([
            'dashboard.view', 'shariah.review', 'shariah.approve',
            'cases.view', 'funds.view', 'distributions.view', 'reports.view',
        ]);

        // Auditor (read-only)
        $auditor = Role::create(['name' => 'auditor']);
        $auditor->givePermissionTo([
            'dashboard.view', 'audit.view', 'audit.export', 'funds.view',
            'collections.view', 'distributions.view', 'reports.view',
            'reports.export', 'blockchain.view',
        ]);

        // Branch Manager
        $branchManager = Role::create(['name' => 'branch_manager']);
        $branchManager->givePermissionTo([
            'dashboard.view', 'users.view', 'donors.view', 'beneficiaries.view',
            'cases.view', 'cases.assign', 'collections.view', 'distributions.view',
            'reports.view', 'complaints.view',
        ]);

        // Collection Officer
        $collectionOfficer = Role::create(['name' => 'collection_officer']);
        $collectionOfficer->givePermissionTo([
            'dashboard.view', 'donors.view', 'donors.create',
            'collections.view', 'collections.create', 'campaigns.view',
        ]);

        // Distribution Officer
        $distOfficer = Role::create(['name' => 'distribution_officer']);
        $distOfficer->givePermissionTo([
            'dashboard.view', 'distributions.view', 'distributions.create',
            'beneficiaries.view', 'cases.view',
        ]);

        // Field Agent
        $fieldAgent = Role::create(['name' => 'field_agent']);
        $fieldAgent->givePermissionTo([
            'dashboard.view', 'cases.view', 'verification.view', 'verification.create',
            'beneficiaries.view',
        ]);

        // Supervisor
        $supervisor = Role::create(['name' => 'supervisor']);
        $supervisor->givePermissionTo([
            'dashboard.view', 'cases.view', 'cases.assign',
            'verification.view', 'verification.review', 'beneficiaries.view',
        ]);

        // Donor
        $donor = Role::create(['name' => 'donor']);
        $donor->givePermissionTo(['dashboard.view']);

        // Beneficiary
        $beneficiary = Role::create(['name' => 'beneficiary']);
        $beneficiary->givePermissionTo(['dashboard.view']);

        // Volunteer — performs initial (union-scoped) verification
        $volunteer = Role::create(['name' => 'volunteer']);
        $volunteer->givePermissionTo([
            'dashboard.view', 'cases.view', 'beneficiaries.view',
            'verification.view', 'verification.create',
        ]);

        // Organization Admin — performs final verification for its volunteers
        $organization = Role::create(['name' => 'organization']);
        $organization->givePermissionTo([
            'dashboard.view', 'cases.view', 'beneficiaries.view',
            'verification.view', 'verification.review',
        ]);

        // Customer Support
        $support = Role::create(['name' => 'customer_support']);
        $support->givePermissionTo([
            'dashboard.view', 'complaints.view', 'complaints.manage',
            'donors.view', 'beneficiaries.view',
        ]);

        // Data Entry Operator
        $dataEntry = Role::create(['name' => 'data_entry']);
        $dataEntry->givePermissionTo([
            'dashboard.view', 'donors.view', 'donors.create',
            'beneficiaries.view', 'beneficiaries.create', 'collections.view', 'collections.create',
        ]);

        // Compliance Officer
        $compliance = Role::create(['name' => 'compliance_officer']);
        $compliance->givePermissionTo([
            'dashboard.view', 'donors.kyc', 'beneficiaries.view',
            'audit.view', 'audit.export', 'reports.view',
        ]);
    }
}
