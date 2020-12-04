<?php

namespace Modules\ExamplePermission\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExamplePermission\Models\Permission;

class PermissionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;
    public $useDatatables = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-permission.permissions');
        $this->setBaseModel(Permission::class);

        $attributes = [

        ];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
        $this->useDatatables = 1;
    }

    /** @test */
    public function guest_cannot_read_permissions()
    {
        $permission = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($permission->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_permissions()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        if (!$this->useDatatables) {
            $response->assertViewIs('examplepermission::permissions.index');
        } else {
            $response->assertViewIs('examplepermission::permissions.datatables-index');
        }

        $response->assertViewHas('permissions');

        if (!$this->useDatatables) {
            $response->assertSee($permission->{$this->itemColumn});
        }
    }

    /** @test */
    // public function authenticated_user_cannot_read_others_permissions()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $permission = $this->newItem($this->itemAttributes);

    //     $response = $this->readAllItems();

    //     $response->assertStatus(200);
    //     $response->assertDontSee($permission->{$this->itemColumn});
    // }

    /** @test */
    public function guest_cannot_create_a_new_permission()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_permission()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::permissions.create');
		$response->assertViewHas('permission');

        $data = raw($this->base_model, $this->itemAttributes);
        unset($data[$this->itemUserColumn]);
        $response = $this->createItem($data);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), $data);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_created'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_created'));
        $response->assertStatus(302);
    }

    /** @test */
    public function guest_cannot_view_a_permission()
    {
        $permission = $this->newItem($this->itemAttributes);

        $response = $this->readItem($permission->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_permission()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($this->itemAttributes);

        $response = $this->readItem($permission->id);

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::permissions.show');
		$response->assertViewHas('permission');
    }

    /** @test */
    // public function authenticated_user_cannot_view_others_permission()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $permission = $this->newItem($this->itemAttributes);

    //     $response = $this->readItem($permission->id);

    //     $response->assertStatus(403);
    // }

    /** @test */
    public function guest_cannot_update_the_permission()
    {
        $permission = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($permission->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_permission()
    {
        $this->signIn();

        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($this->itemAttributes);
        $oldName = $permission->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $permission->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage($permission->id);

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::permissions.edit');
		$response->assertViewHas('permission');

        $response = $this->updateItem($permission->id, $permission->toArray());

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseHas($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $newName,
        ]);
        $this->assertDatabaseMissing($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $oldName,
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_updated'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_updated'));
        $response->assertStatus(302);
    }

    /** @test */
    // public function authorized_user_cannot_update_others_permission()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $permission = $this->newItem($this->itemAttributes);
    //     $newName = $this->faker->sentence;
    //     $permission->{$this->itemColumn} = $newName;

    //     $response = $this->updateItem($permission->id, $permission->toArray());

    //     $response->assertStatus(403);
    // }

    /** @test */
    public function authorized_user_can_delete_the_permission()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $permission = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($permission->id);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $permission->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    // public function authorized_user_cannot_delete_others_permission()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $permission = $this->newItem($this->itemAttributes);

    //     $response = $this->deleteItem($permission->id);

    //     $model = new $this->base_model;
    //     $this->assertEquals(1, $model->all()->count());
    //     $this->assertDatabaseHas($model->getTable(), [
    //         $this->itemUserColumn => $otherUser->id,
    //         $this->itemColumn => $permission->{$this->itemColumn},
    //     ]);
    //     $response->assertStatus(403);
    // }
}
