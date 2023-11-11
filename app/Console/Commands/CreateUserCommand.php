<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateUserCommand extends Command
{
    protected $signature = 'user:create';

    protected $description = 'create new user';

    public function handle()
    {
        $roles = Role::all();
        $user['name'] = $this->ask('enter your name');
        $user['email'] = $this->ask('enter your email');
        $user['password'] = $this->secret('enter your password');

        $role = $this->choice('enter role', $roles->pluck('name')->toArray(), 1);

        $validator = Validator::make($user + ['role' => $role], [
           'name' => 'required',
           'email' => 'required|email|unique:users,email',
           'password' => ['required', Password::default()],
           'role' => 'required|exists:roles,name'
        ]);

        if($validator->fails()){
            foreach ($validator->errors()->all() as $error){
                $this->error($error);
            }

            return -1;
        }

        $role = $roles->where('name', $role)->first();
        DB::transaction(function () use ($user, $role) {
            $user = User::create($user);
            $user->roles()->attach($role->id);
        });

        $this->info('user created successfully');

        return 0;
    }
}
