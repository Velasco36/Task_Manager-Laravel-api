<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Note;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\PasswordReset;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function userFactory()
    {
        return User::factory()->state([
            'id' => 1
,            'email' => 'admin@admin.com',
            'password' => Hash::make('admin_password'), // Replace with a strong password
        ]);

    }
    public function it_registers_a_user_successfully()
    {
        $data = [
            'name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'msg',
                'user' => [
                    'id',
                    'name',
                    'last_name',
                    'username',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }
    public function test_logs_in_a_user_successfully()
    {
        $user = User::factory()->create();

        $data = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'msg',
                'token'
            ]);
    }

    /** @test */
    public function it_returns_the_user_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')
            ->getJson('/api/userProfile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function it_updates_user_profile_successfully()
    {
        // Creamos un usuario para probar la actualización
        $user = User::factory()->create();

        // Creamos datos aleatorios para actualizar el perfil del usuario
        $data = [
            'id' => $user->id,
            'name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
        ];

        // Realizamos la solicitud PATCH a la ruta /api/updateProfile
        $response = $this->actingAs($user, 'api')
        ->patchJson('/api/updateProfile', $data);

        // Verificamos que la respuesta sea exitosa y tenga la estructura esperada
        $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'msg' => 'User Date',
            'date' => [
                'id' => $data['id'],
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'email' => $data['email'],
            ]
        ]);

        // Verificamos que los datos del usuario se hayan actualizado correctamente en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $data['id'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
        ]);
    }

    public function test_logout_in_a_user()
    {
        // Crear un usuario
        $user = User::factory()->create();

        // Autenticar al usuario y obtener el token
        $token = JWTAuth::fromUser($user);

        // Hacer la solicitud de logout con el token
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/logout');

        // Verificar que la respuesta sea la esperada
        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);

        // Verificar que el usuario esté desconectado
        $this->assertGuest();
    }
    public function test_forget_password_sends_email()
    {
        // Crear un usuario
        $user = User::factory()->create();

        // Realizar la solicitud de olvido de contraseña
        $response = $this->postJson('/api/forget-password', ['email' => $user->email]);

        // Verificar que la respuesta sea la esperada
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'msg' => 'Please check your mail to reset your Password'
            ]);


    }


 public function test_reset_password()
    {
        // Crear una contraseña de prueba
        $password = 'miguel';
        $newPassword = 'newPassword123';

        // Crear un usuario
        $user = User::factory()->create([
            'password' => Hash::make($password)
        ]);

        // Datos para la solicitud de restablecimiento de contraseña
        $data = [
            'id' => $user->id,
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ];

        // Realizar la solicitud POST para restablecer la contraseña
        $response = $this->postJson('/reset', $data);

        // Verificar que la respuesta sea la esperada
        $response->assertStatus(200)
            ->assertSee('password change');

        // Verificar que la contraseña del usuario haya sido actualizada en la base de datos
        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    }

