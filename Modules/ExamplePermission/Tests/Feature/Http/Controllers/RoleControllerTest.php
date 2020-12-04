<?php

namespace Modules\ExamplePermission\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Modules\ExamplePermission\Models\Role;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;
    public $useDatatables = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-permission.roles');
        $this->setBaseModel(Role::class);

        $attributes = [

        ];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
        $this->useDatatables = 1;
    }

    /** @test */
    public function guest_cannot_read_roles()
    {
        $role = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($role->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_roles()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        if (!$this->useDatatables) {
            $response->assertViewIs('examplepermission::roles.index');
        } else {
            $response->assertViewIs('examplepermission::roles.datatables-index');
        }

        $response->assertViewHas('roles');

        if (!$this->useDatatables) {
            $response->assertSee($role->{$this->itemColumn});
        }
    }

    /** @test */
    // public function authenticated_user_cannot_read_others_roles()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $role = $this->newItem($this->itemAttributes);

    //     $response = $this->readAllItems();

    //     $response->assertStatus(200);
    //     $response->assertDontSee($role->{$this->itemColumn});
    // }

    /** @test */
    public function guest_cannot_create_a_new_role()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_role()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::roles.create');
		$response->assertViewHas('role');

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
    public function guest_cannot_view_a_role()
    {
        $role = $this->newItem($this->itemAttributes);

        $response = $this->readItem($role->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_role()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($this->itemAttributes);

        $response = $this->readItem($role->id);

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::roles.show');
		$response->assertViewHas('role');
    }

    /** @test */
    // public function authenticated_user_cannot_view_others_role()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $role = $this->newItem($this->itemAttributes);

    //     $response = $this->readItem($role->id);

    //     $response->assertStatus(403);
    // }

    /** @test */
    public function guest_cannot_update_the_role()
    {
        $role = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($role->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_role()
    {
        $this->signIn();

        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($this->itemAttributes);
        $oldName = $role->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $role->{$this->itemColumn} = $newName;

        $response = $this->visitEditPage($role->id);

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::roles.edit');
		$response->assertViewHas('role');

        $response = $this->updateItem($role->id, $role->toArray());

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
    // public function authorized_user_cannot_update_others_role()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $role = $this->newItem($this->itemAttributes);
    //     $newName = $this->faker->sentence;
    //     $role->{$this->itemColumn} = $newName;

    //     $response = $this->updateItem($role->id, $role->toArray());

    //     $response->assertStatus(403);
    // }

    /** @test */
    public function authorized_user_can_delete_the_role()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $role = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($role->id);

        $model = new $this->base_model;
        $this->assertEquals(0, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $role->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    // public function authorized_user_cannot_delete_others_role()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $role = $this->newItem($this->itemAttributes);

    //     $response = $this->deleteItem($role->id);

    //     $model = new $this->base_model;
    //     $this->assertEquals(1, $model->all()->count());
    //     $this->assertDatabaseHas($model->getTable(), [
    //         $this->itemUserColumn => $otherUser->id,
    //         $this->itemColumn => $role->{$this->itemColumn},
    //     ]);
    //     $response->assertStatus(403);
    // }
}
