<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer = Role::where('slug','admin')->first();
        $manager = Role::where('slug', 'user')->first();
        $createTasks = Permission::where('slug','manage-users')->first();
        $manageUsers = Permission::where('slug','create-products')->first();

        $this->faker = Faker::create();

        for($i = 1;$i <= 25;$i ++) {
            $user1 = new User();
            $user1->name = $this->faker->name;
            $user1->email = $this->faker->freeEmail;
            $user1->password =Hash::make(12345);
            $user1->save();
            $user1->roles()->attach($developer);
            $user1->permissions()->attach($createTasks);
        }
        for($i = 1;$i <= 25;$i ++) {
            $user1 = new User();
            $user1->name = $this->faker->name;
            $user1->email = $this->faker->freeEmail;
            $user1->password =Hash::make(12345);
            $user1->save();
            $user1->roles()->attach($manager);
            $user1->permissions()->attach($manageUsers);
        }
    }
}
