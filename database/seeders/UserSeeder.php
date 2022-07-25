<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;

class UserSeeder extends Seeder
{
    const NUM_PROJ = 2;
    const NUM_TASKS_PER_PROJ = 5;
    const DEFAULT_USER = ['email' => "test@test"];

    /**
     * If DEFAULT_USER doesn't exist it will create him and use him for this seeder.
     * If he does exist, seeder will create new user for this seed.
     * 
     * Seeder creates NUM_PROJ this many projects associated with this user.
     * Seeder creates NUM_TASKS_PER_PROJ this many tasks for each project with unique priority.
     *
     * @return void
     */
    public function run()
    {
        if(!$user = User::where(self::DEFAULT_USER)->first()){
            $user = User::factory()->create(self::DEFAULT_USER);
        } else {
            $user = User::factory()->create();
        }

        for($i = 0; $i < self::NUM_PROJ; $i++){
            $project = Project::factory()->create([
                'user_id' => $user->id
            ]);

            for($j = 0; $j < self::NUM_TASKS_PER_PROJ; $j++){
                $task = Task::factory()->create([
                    'project_id' => $project->id,
                    'priority' => $j,
                ]);
            }
        }
    }

}
