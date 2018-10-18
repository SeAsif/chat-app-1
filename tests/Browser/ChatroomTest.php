<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\ChatPage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChatroomTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test A user can send a message.
     *
     * @return void
     */
    public function a_user_can_send_a_message()
    {
        $user = factory(User::class)->create();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->assertAuthenticated()
                ->visit(new ChatPage)
                ->typeMessage('Hi there.')
                ->sendMessage()
                ->assertInputValue('@body', '')// should be empty after message is sent
                ->with('.chat__messages', function ($messages) use ($user) {
                     $messages->assertSee('Hi there')
                     ->assertSee($user->name); // always clear cache
                 });
        });
    }
}
