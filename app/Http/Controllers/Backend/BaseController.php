<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected string $guard;
    protected string $routePrefix;

    public function __construct()
    {
        $this->guard = getCurrentGuard();
        $this->routePrefix = $this->guard ? $this->guard . '.' : '';
    }

    protected function routeWithGuard(string $routeName, array $parameters = []): string
    {
        return route($this->routePrefix . $routeName, $parameters);
    }
}
