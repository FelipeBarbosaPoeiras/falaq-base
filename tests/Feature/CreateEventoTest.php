<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEventoTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_open_the_event_form(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('eventos.create'))
            ->assertOk()
            ->assertSee('name="titulo"', false)
            ->assertSee('name="descricao"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('hover:bg-blue-700', false)
            ->assertDontSee('id="titulo-error"', false)
            ->assertDontSee('border-red-500', false);
    }

    public function test_blank_submission_shows_errors_below_both_fields(): void
    {
        $this->actingAs(User::factory()->create())
            ->from(route('eventos.create'))
            ->post(route('eventos.store'), [])
            ->assertRedirect(route('eventos.create'))
            ->assertSessionHasErrors(['titulo', 'descricao']);

        $this->get(route('eventos.create'))
            ->assertOk()
            ->assertSeeInOrder(['name="titulo"', 'id="titulo-error"', 'Informe o título do evento.', 'name="descricao"', 'id="descricao-error"', 'Informe a descrição do evento.'], false)
            ->assertSee('border-red-500', false)
            ->assertSee('text-red-500', false)
            ->assertSee('aria-invalid="true"', false);

        $this->assertDatabaseCount('eventos', 0);
    }

    public function test_validation_failure_preserves_and_escapes_all_values(): void
    {
        $titulo = '<script>alert("evento")</script>';
        $descricao = 'Descrição com <b>conteúdo</b> & detalhes';

        $this->actingAs(User::factory()->create())
            ->from(route('eventos.create'))
            ->post(route('eventos.store'), [
                'titulo' => $titulo,
                'descricao' => $descricao,
                'data_evento' => 'data-invalida',
            ])
            ->assertRedirect(route('eventos.create'))
            ->assertSessionHasErrors('data_evento')
            ->assertSessionHasInput('titulo', $titulo)
            ->assertSessionHasInput('descricao', $descricao);

        $this->get(route('eventos.create'))
            ->assertOk()
            ->assertSee('value="'.e($titulo).'"', false)
            ->assertSee(e($descricao).'</textarea>', false)
            ->assertSee('value="data-invalida"', false)
            ->assertDontSee($titulo, false);

        $this->assertDatabaseCount('eventos', 0);
    }

    public function test_valid_submission_creates_an_event_owned_by_the_authenticated_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user)->post(route('eventos.store'), [
            'titulo' => 'Encontro AskLive',
            'descricao' => 'Uma conversa sobre tecnologia.',
            'data_evento' => '',
            'user_id' => $otherUser->id,
        ]);

        $evento = Evento::sole();
        $response->assertRedirect(route('eventos.show', $evento->id));
        $this->assertDatabaseHas('eventos', [
            'titulo' => 'Encontro AskLive',
            'descricao' => 'Uma conversa sobre tecnologia.',
            'data_evento' => null,
            'user_id' => $user->id,
        ]);
    }
}
