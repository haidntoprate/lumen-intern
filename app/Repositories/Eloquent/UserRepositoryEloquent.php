<?php

namespace App\Repositories\Eloquent;

use App\Models\User;

use App\Repositories\UserRepository;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }
    
    public function boot()
    {

    }

}
