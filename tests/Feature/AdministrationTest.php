<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\SettingsModel;
use App\Models\ActivityLogsModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class AdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $staffUser;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Admin
        $admin = new User();
        $admin->name = 'Admin Admin';
        $admin->email = 'admin_adm@example.com';
        $admin->password = bcrypt('password123');
        $admin->is_role = 1;
        $admin->status = 1;
        $admin->is_deleted = 0;
        $admin->save();
        $this->adminUser = $admin;

        // 2. Create Staff
        $staff = new User();
        $staff->name = 'Staff Staff';
        $staff->email = 'staff_adm@example.com';
        $staff->password = bcrypt('password123');
        $staff->is_role = 2;
        $staff->status = 1;
        $staff->is_deleted = 0;
        $staff->save();
        $this->staffUser = $staff;
    }

    /**
     * Test admin can view and update system configurations.
     */
    public function test_admin_can_update_settings()
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/admin/settings');

        $response->assertStatus(200);
        $response->assertSee('System Administration Settings');

        $postData = [
            'pharmacy_name' => 'Super Health Pharmacy',
            'address' => '456 Lane, Dhaka',
            'phone' => '01711111111',
            'email' => 'super@example.com',
            'website' => 'superhealth.com',
            'currency' => 'Tk',
            'tax_percentage' => 5,
            'invoice_prefix' => 'SUPINV-',
            'purchase_prefix' => 'SUPPUR-',
            'low_stock_threshold' => 15,
            'expiry_alert_days' => 45,
            'theme' => 'dark'
        ];

        $postResponse = $this->actingAs($this->adminUser)
            ->post('/admin/settings', $postData);

        $postResponse->assertRedirect();
        
        // Assert setting saved in database
        $this->assertEquals('Super Health Pharmacy', SettingsModel::getValue('pharmacy_name'));
        $this->assertEquals('15', SettingsModel::getValue('low_stock_threshold'));
    }

    /**
     * Test admin can view audit logs.
     */
    public function test_admin_can_view_activity_logs()
    {
        // Log an activity
        ActivityLogsModel::log('Custom test activity logged');

        $response = $this->actingAs($this->adminUser)
            ->get('/admin/activity-logs');

        $response->assertStatus(200);
        $response->assertSee('Custom test activity logged');
    }

    /**
     * Test admin can run dynamic database backup exporter.
     */
    public function test_admin_can_generate_backup()
    {
        $response = $this->actingAs($this->adminUser)
            ->post('/admin/backup');

        $response->assertRedirect();

        // Check if database backup directory and file exists
        $backupDir = storage_path('app/backups');
        $files = File::files($backupDir);
        $this->assertNotEmpty($files);

        // Cleanup backups
        foreach ($files as $file) {
            File::delete($file);
        }
    }

    /**
     * Test staff users cannot access admin endpoints.
     */
    public function test_staff_cannot_access_settings_logs_and_backups()
    {
        // 1. Settings block
        $r1 = $this->actingAs($this->staffUser)->get('/admin/settings');
        $r1->assertRedirect('admin/dashboard');

        // 2. Logs block
        $r2 = $this->actingAs($this->staffUser)->get('/admin/activity-logs');
        $r2->assertRedirect('admin/dashboard');

        // 3. Backups block
        $r3 = $this->actingAs($this->staffUser)->get('/admin/backup');
        $r3->assertRedirect('admin/dashboard');
    }
}
