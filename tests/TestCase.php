<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $base_route = null;
    protected $base_model = null;
    protected $user = null;

    protected function signIn($user = null)
    {
        $user = $user ?? create(User::class);
        $this->actingAs($user);
        $this->user = $user;
        return $this;
    }

    protected function setBaseRoute($route)
    {
        $this->base_route = $route;
    }

    protected function setBaseModel($model)
    {
        $this->base_model = $model;
    }

    public function newItem($attributes = [])
    {
        return create($this->base_model, $attributes);
    }

    public function readAllItems($routeParams = [], $url = null)
    {
        $route = $this->base_route;
        $url = $url ?? route("$route.index", $routeParams);
        $response = $this->get($url);
        return $response;
    }

    public function readItem($routeParams = [], $url = null)
    {
        $route = $this->base_route;
        $url = $url ?? route("$route.show", $routeParams);
        $response = $this->get($url);
        return $response;
    }

    public function createItem($attributes = [], $routeParams = [], $url = null)
    {
        $route = $this->base_route;
        $model = $this->base_model;
        $data = raw($model, $attributes);
        $url = $url ?? route("$route.store", $routeParams);
        $response = $this->post($url, $data);
        return $response;
    }

    public function updateItem($routeParams = [], $attributes = [], $url = null)
    {
        $route = $this->base_route;
        $model = $this->base_model;
        $data = raw($model, $attributes);
        $url = $url ?? route("$route.update", $routeParams);
        $response = $this->put($url, $data);
        return $response;
    }

    public function deleteItem($routeParams = [], $url = null)
    {
        $route = $this->base_route;
        $url = $url ?? route("$route.destroy", $routeParams);
        $response = $this->delete($url);
        return $response;
    }

    public function visitCreatePage($routeParams = [], $url = null)
    {
        $route = $this->base_route;
        $url = $url ?? route("$route.create", $routeParams);
        $response = $this->get($url);
        return $response;
    }

    public function visitEditPage($routeParams = [], $url = null)
    {
        $route = $this->base_route;
        $url = $url ?? route("$route.edit", $routeParams);
        $response = $this->get($url);
        return $response;
    }
}
