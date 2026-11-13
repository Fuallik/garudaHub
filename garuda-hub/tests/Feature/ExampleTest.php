<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_redirects_to_project_list(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('projects.index'));
    }
}
