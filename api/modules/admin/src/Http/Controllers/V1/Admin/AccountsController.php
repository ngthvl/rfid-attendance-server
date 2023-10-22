<?php

namespace Tamani\Admin\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\Admin\Http\Resources\AdminResource;
use Tamani\Admin\Models\Admin;

class AccountsController extends Controller
{
    public function index()
    {
        $users = QueryBuilder::for(Admin::class)->paginate();

        return AdminResource::collection($users);
    }
}
