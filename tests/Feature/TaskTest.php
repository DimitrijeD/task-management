<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
        $this->task = Task::factory()->create(['project_id' => $this->project->id]);

        Auth::login($this->user);
        $this->withHeaders([ 'Accept' => 'application/json', ]);
    }

    public function test_must_be_auth_user()
    {
        Auth::logout($this->user);

        $this->post("/api/task/new", [])->assertJsonFragment([
            'message' => __("Unauthenticated."),
        ]);

        $this->patch("/api/task/update", [])->assertJsonFragment([
            'message' => __("Unauthenticated."),
        ]);

        $this->delete("/api/task/delete/1")->assertJsonFragment([
            'message' => __("Unauthenticated."),
        ]);
    }

    public function test_create_task()
    {
        $data = [
            'name' => 'name',
            'description' => 'description',
            'priority' => 1,
            'project_id' => $this->project->id
        ];

        $response = $this->post("/api/task/new", $data);
        
        $response->assertJsonStructure([
            'id', 'name', 'description', 'priority', 'project_id', 'created_at', 'updated_at'
        ]);

        $this->assertDatabaseHas($this->task->getTable(), [
            'id' => $response->decodeResponseJson()['id'],
        ]);
    }


    public function test_update_task()
    {
        $data = [
            'name' => 'name',
            'description' => 'description',
            'priority' => 1,
            'task_id' => $this->task->id
        ];

        $response = $this->patch('/api/task/update', $data);
        
        $response->assertJsonFragment([
            'id' => $data['task_id'],
            'name' => $data['name'],
            'project_id' => $this->project->id,
            'description' => $data['description'],
            'priority' => $data['priority'],
            'description' => $data['description'],
        ]);

        $responseArr = $response->decodeResponseJson();

        $this->assertDatabaseHas($this->task->getTable(), [
            'id' => $responseArr['id'],
            'name' => $responseArr['name'],
            'priority' => $responseArr['priority'],
            'description' => $responseArr['description'],
        ]);
    }


    public function test_delete_task()
    {
        $this->delete("/api/task/delete/{$this->task->id}");

        $this->assertDatabaseMissing($this->task->getTable(),[
            'id' => $this->task->id
        ]);
    }

    public function test_task_can_be_migrated_to_another_proj()
    {
        $diffProj = Project::factory()->create();

        $data = [
            'project_id' => $diffProj->id,
            'task_id' => $this->task->id
        ];

        $response = $this->patch('/api/task/update', $data);
        
        $response->assertJsonFragment([
            'id' => $data['task_id'],
            'project_id' => $data['project_id'],
        ]);

        $responseArr = $response->decodeResponseJson();

        $this->assertDatabaseHas($this->task->getTable(), [
            'id' => $responseArr['id'],
            'project_id' => $responseArr['project_id'],
        ]);
    }

}
