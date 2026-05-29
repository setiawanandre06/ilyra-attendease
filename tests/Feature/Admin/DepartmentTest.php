<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    private user $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // setup roles
        $this->artisan('db:seed', ['--class' => 'Database\Seeders\UserSeeder']);

        // login as admin
        $this->admin = User::where('email', 'admin@attendease.com')->first();
        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_departments(): void
    {
        $response = $this->get(route('admin.departments.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.departments.index');
    }

    public function test_admin_can_create_department(): void
    {
        $response = $this->post(route('admin.departments.store'), [
            'name' => 'IT Department',
            'manager_id' => null,
        ]);

        $response->assertRedirect(route('admin.departments.index'));
        $this->assertDatabaseHas('departments', ['name' => 'IT Department']);
    }

    public function test_department_name_must_be_unique(): void
    {
        Department::create(['name' => 'Backend Developer']);

        $response = $this->post(route('admin.departments.store'), [
            'name' => 'Backend Developer',
            'manager_id' => null,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_department(): void
    {
        $department = Department::create(['name' => 'HR']);

        $response = $this->put(route('admin.departments.update', $department), [
            'name' => 'Human Resources',
            'manager_id' => null,
        ]);

        $response->assertRedirect(route('admin.departments.index'));
        $this->assertDatabaseHas('departments', ['name' => 'Human Resources']);
    }

    public function test_admin_can_delete_department(): void
    {
        $department = Department::create(['name' => 'To Delete']);

        $response = $this->delete(route('admin.departments.destroy', $department));

        $response->assertRedirect(route('admin.departments.index'));
        $this->assertDatabaseMissing('departments', ['name' => 'To Delete']);
    }

    public function test_employee_cannot_access_departments(): void
    {
        $employee = User::where('email', 'karyawan@attendease.com')->first();
        $this->actingAs($employee);

        $response = $this->get(route('admin.departments.index'));
        $response->assertStatus(403);
    }
}
