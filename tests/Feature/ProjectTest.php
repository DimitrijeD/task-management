<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectTest extends TestCase
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

        $this->get("/api/projects")->assertJsonFragment([
            'message' => __("Unauthenticated."),
        ]);

        $this->post("/api/project/new", [])->assertJsonFragment([
            'message' => __("Unauthenticated."),
        ]);

        $this->patch("/api/project/update", [])->assertJsonFragment([
            'message' => __("Unauthenticated."),
        ]);

        $this->delete("/api/project/delete/1")->assertJsonFragment([
            'message' => __("Unauthenticated."),
        ]);
    }

    public function test_create_proj()
    {
        $response = $this->post("/api/project/new", [ 'name' => "proj name", ]);
        
        $response->assertJsonStructure([
            'id', 'name', 'created_at', 'updated_at', 'tasks' => [],
        ]);

        $this->assertDatabaseHas($this->project->getTable(), [
            'id' => $response->decodeResponseJson()['id'],
        ]);
    }

    public function test_get_all_users_projects_with_tasks()
    {
        $response = $this->get("/api/projects");

        $response->assertJsonCount($this->user->projects->count());

        $response->assertJsonFragment([
            'id' => $this->task->id,
            'name' => $this->task->name,
        ]);
    }

    public function test_delete_proj()
    {
        $this->delete("/api/project/delete/{$this->project->id}");

        $this->assertDatabaseMissing($this->project->getTable(), [
            'id' => $this->project->id
        ]);
    }

    public function test_update_proj()
    {
        $data = [
            'name'=> 'name of neew proj',
            'project_id' => $this->project->id
        ];

        $response = $this->patch("/api/project/update", $data);
        
        $response->assertJsonFragment(['name' => $data['name']]);

        $this->assertDatabaseHas($this->project->getTable(), [
            'id' => $this->project->id,
            'name' => $data['name']
        ]);
    }

    public function test_on_delete_proj_deletes_related_tasks()
    {
        $this->delete("/api/project/delete/{$this->project->id}");

        $this->assertTrue($this->project->tasks->count() == 0);
    }

}
