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
                 })
                 ->logout();
        });
    }

    /**
     * @test A user can send a multiline message.
     *
     * @return void
     */
    public function a_user_can_send_a_multiline_message()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new ChatPage)
                    ->typeMessage('Multiline Message')
                    ->keys('@body', '{shift}', '{enter}')
                    ->append('@body', 'New line')
                    ->sendMessage()
                    ->assertSeeIn('@chatMessages', "Multiline Message\nNew line")
                    ->logout();
        });
    }

    /**
     * @test A user can't send an empty message.
     *
     * @return void
     */
    public function a_user_cant_send_an_empty_message()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new ChatPage);
                foreach (['          ', ''] as $empty) {
                    $browser->typeMessage($empty)
                    ->sendMessage()
                    ->assertDontSeeIn('@chatMessages', $user->name);
                }
                //go down a few lines
                $browser->keys('@body', '{shift}', '{enter}')
                ->keys('@body', '{shift}', '{enter}')
                ->sendMessage()
                ->assertDontSeeIn('@body', $user->name)
                ->logout();
        });
    }

    /**
     * @test Messages are ordered by latest first.
     *
     * @return void
     */
    public function messages_are_ordered_by_latest_first()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new ChatPage);
            foreach (['Message One', 'Message Two', 'Message Three'] as $message) {
                $browser->typeMessage($message)
                ->sendMessage()
                ->waitFor('@firstChatMessage')// make sure we have the element available before we check it
                ->assertSeeIn('@firstChatMessage', $message);
            }
            $browser->logout();
        });
    }

    /**
     * @test A user's message is highlighted as their own.
     *
     * @return void
     */
    public function a_users_message_is_highlighted_as_their_own()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(new ChatPage)
                    ->typeMessage('My Message')
                    ->sendMessage()
                    ->waitFor('@ownMessage')
                    ->with('@ownMessage', function ($message) use ($user) {
                        $message->assertSee('My Message')
                        ->assertSee($user->name);
                    })
                    ->logout();
        });
    }
}
