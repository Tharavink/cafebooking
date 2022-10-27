<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected $model;

    public function __construct(User $model) {
        $this->model = $model;
    }

    public function index($request)
    {
        $users = User::with('type')->where('type_id', '!=', 1);

        if ($request->type_id) {
            $users->where('type_id', $request->type_id);
        }

        if ($request->keyword) {
            $keyword = $request->keyword;
            $users->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%'.$keyword.'%')->orWhere('email', 'like', '%'.$keyword.'%');
            });
        }

        return $users->get();
    }
}