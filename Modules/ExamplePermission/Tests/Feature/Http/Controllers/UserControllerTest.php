<?php

namespace Modules\ExamplePermission\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
// use Modules\ExamplePermission\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public $itemColumn;
    public $itemUserColumn;
    public $itemAttributes;
    public $useDatatables = false;

    public function setUp(): void
    {
        parent::setUp();

        $this->setBaseRoute('example-permission.users');
        $this->setBaseModel(User::class);

        $attributes = [

        ];

        $this->itemUserColumn = 'user_id';
        $this->itemColumn = 'name';
        $this->itemAttributes = $attributes;
        $this->useDatatables = 1;
    }

    /** @test */
    public function guest_cannot_read_users()
    {
        $user = create($this->base_model, $this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(302);
        $response->assertRedirect('login');
        $response->assertDontSee($user->{$this->itemColumn});
    }


    /** @test */
    public function authenticated_user_can_read_all_their_users()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($this->itemAttributes);

        $response = $this->readAllItems();

        $response->assertStatus(200);
        if (!$this->useDatatables) {
            $response->assertViewIs('examplepermission::users.index');
        } else {
            $response->assertViewIs('examplepermission::users.datatables-index');
        }

        $response->assertViewHas('users');

        if (!$this->useDatatables) {
            $response->assertSee($user->{$this->itemColumn});
        }
    }

    /** @test */
    // public function authenticated_user_cannot_read_others_users()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $user = $this->newItem($this->itemAttributes);

    //     $response = $this->readAllItems();

    //     $response->assertStatus(200);
    //     $response->assertDontSee($user->{$this->itemColumn});
    // }

    /** @test */
    public function guest_cannot_create_a_new_user()
    {
        $response = $this->visitCreatePage();

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_create_a_new_user()
    {
        $this->signIn();

        $response = $this->visitCreatePage();

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::users.create');
		$response->assertViewHas('user');

        $data = raw($this->base_model, $this->itemAttributes);
        $data['password'] = 12345678;
        $data['password_confirmation'] = 12345678;
        unset($data[$this->itemUserColumn]);
        $response = $this->createItem($data);

        $model = new $this->base_model;
        $expectedUserCount = 1;
        $currentUserCount = 1;
        $expectedUserTotal = $expectedUserCount + $currentUserCount;
        $this->assertEquals($expectedUserTotal, $model->all()->count());
        // $this->assertDatabaseHas($model->getTable(), $data);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_created'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_created'));
        $response->assertStatus(302);
    }

    /** @test */
    public function guest_cannot_view_a_user()
    {
        $user = $this->newItem($this->itemAttributes);

        $response = $this->readItem($user->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authenticated_user_can_view_a_user()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($this->itemAttributes);

        $response = $this->readItem($user->id);

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::users.show');
		$response->assertViewHas('user');
    }

    /** @test */
    // public function authenticated_user_cannot_view_others_user()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $user = $this->newItem($this->itemAttributes);

    //     $response = $this->readItem($user->id);

    //     $response->assertStatus(403);
    // }

    /** @test */
    public function guest_cannot_update_the_user()
    {
        $user = create($this->base_model, $this->itemAttributes);

        $response = $this->visitEditPage($user->id);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    /** @test */
    public function authorized_user_can_update_the_user()
    {
        $this->signIn();

        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($this->itemAttributes);
        $oldName = $user->{$this->itemColumn};
        $newName = $this->faker->sentence;
        $user->{$this->itemColumn} = $newName;

        $userData = $user->toArray();
        $userData['password'] = '12345678';
        $userData['password_confirmation'] = '12345678';

        $response = $this->visitEditPage($user->id);

        $response->assertStatus(200);
        $response->assertViewIs('examplepermission::users.edit');
		$response->assertViewHas('user');

        $response = $this->updateItem($user->id, $userData);
        // dump($response);

        $model = new $this->base_model;
        $expectedUserCount = 1;
        $currentUserCount = 1;
        $expectedUserTotal = $expectedUserCount + $currentUserCount;

        $this->assertEquals($expectedUserTotal, $model->all()->count());
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
    // public function authorized_user_cannot_update_others_user()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $user = $this->newItem($this->itemAttributes);
    //     $newName = $this->faker->sentence;
    //     $user->{$this->itemColumn} = $newName;

    //     $response = $this->updateItem($user->id, $user->toArray());

    //     $response->assertStatus(403);
    // }

    /** @test */
    public function authorized_user_can_delete_the_user()
    {
        $this->signIn();
        // $this->itemAttributes[$this->itemUserColumn] = $this->user->id;
        $user = $this->newItem($this->itemAttributes);

        $response = $this->deleteItem($user->id);

        $model = new $this->base_model;
        $this->assertEquals(1, $model->all()->count());
        $this->assertDatabaseMissing($model->getTable(), [
            // $this->itemUserColumn => $this->user->id,
            $this->itemColumn => $user->{$this->itemColumn},
        ]);

        // $response->assertSessionHas(['successMessage']);
        // $response->assertSessionHas('successMessage', __('my_app.messages.data_deleted'));

        $response->assertSessionHas(['flash_notification']);
        $session = session('flash_notification')[0];
        $this->assertEquals($session['message'], __('my_app.messages.data_deleted'));
        $response->assertStatus(302);
    }

    /** @test */
    // public function authorized_user_cannot_delete_others_user()
    // {
    //     $this->signIn();
    //     $otherUser = create(User::class);
    //     $this->itemAttributes[$this->itemUserColumn] = $otherUser->id;
    //     $user = $this->newItem($this->itemAttributes);

    //     $response = $this->deleteItem($user->id);

    //     $model = new $this->base_model;
    //     $this->assertEquals(1, $model->all()->count());
    //     $this->assertDatabaseHas($model->getTable(), [
    //         $this->itemUserColumn => $otherUser->id,
    //         $this->itemColumn => $user->{$this->itemColumn},
    //     ]);
    //     $response->assertStatus(403);
    // }
}
