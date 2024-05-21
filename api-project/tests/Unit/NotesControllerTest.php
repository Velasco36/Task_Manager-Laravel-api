<?php

use App\Models\User;
use App\Models\Note;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NotesTest extends TestCase
{
    public function userFactory()
    {
        return User::factory()->state([
            'id' => '1',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin_password'),
        ]);
    }

    public function test_creates_note_with_valid_data()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $data = [
            'title' => 'A descriptive note title',
            'body' => 'This is some note content.',
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/notes_creacte', $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Note created successfully',
            ]);
    }

    public function test_Update_note_with_valid_data()
    {
        // Crear usuario y generar token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Crear una nota para actualizar
        $note = Note::factory()->create([
            'title' => 'Old title',
            'body' => 'Old body',
            'user_id' => $user->id,
        ]);

        // Datos actualizados
        $data = [
            'title' => 'A descriptive note title',
            'body' => 'This is some note content.',
        ];

        // Realizar la solicitud de actualización
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson("/api/note-update/{$note->id}", $data);

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Note updated successfully',
            ]);
    }

      public function test_Destroy_note_with_valid_data()
    {
        // Crear usuario y generar token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Crear una nota para actualizar
        $note = Note::factory()->create([
            'title' => 'Old title',
            'body' => 'Old body',
            'user_id' => $user->id,
        ]);



        // Realizar la solicitud de actualización
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson("/api/note-delete/{$note->id}",);

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Note deleted successfully',
            ]);
    }

        public function test_Detail_note_with_valid_data()
        {
            // Crear usuario y generar token
            $user = User::factory()->create();
            $token = JWTAuth::fromUser($user);

            // Crear una nota para actualizar
            $note = Note::factory()->create([
                'title' => 'Old title',
                'body' => 'Old body',
                'user_id' => $user->id,
            ]);

            // Realizar la solicitud de actualización
            $response = $this->withHeaders([
                'Authorization' => "Bearer $token",
            ])->getJson("/api/note-detail/{$note->id}",);
        $response->assertStatus(200)
            ->assertJson([
                'id' => $note->id,
                'title' => $note->title,
                'body' => $note->body,
                'user_id' => $note->user_id,
                'created_at' => $note->created_at->toJSON(),
                'updated_at' => $note->updated_at->toJSON(),
            ]);
        }


    public function test_Filter_note_with_valid_data()
    {
        // Crear usuario y generar token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Crear una nota para actualizar
        $note = Note::factory()->create([
            'title' => 'Old title',
            'body' => 'Old body',
            'user_id' => $user->id,
        ]);

        // Realizar la solicitud de filtrado
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson("/api/note-filter/title");

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'notes' => [
                    [
                        'id' => $note->id,
                        'title' => $note->title,
                        'body' => $note->body,
                        'user_id' => $note->user_id,
                        'created_at' => $note->created_at->toJSON(),
                        'updated_at' => $note->updated_at->toJSON(),
                    ],
                ],
            ]);
    }

    public function test_Search_note_with_valid_data()
    {
        // Crear usuario y generar token
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        // Crear una nota para actualizar
        $note = Note::factory()->create([
            'title' => 'Old title',
            'body' => 'Old body',
            'user_id' => $user->id,
        ]);
        $data = ['search' => 'Old'];

        // Realizar la solicitud de filtrado
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson("/api/search-notes", $data);

        // Verificar la respuesta
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'results' => [
                    [
                        'id' => $note->id,
                        'title' => $note->title,
                        'body' => $note->body,
                        'user_id' => $note->user_id,
                        'created_at' => $note->created_at->toJSON(),
                        'updated_at' => $note->updated_at->toJSON(),
                    ],
                ],
            ]);
    }
}
