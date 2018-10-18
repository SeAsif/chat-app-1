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
     * @test A user can see messages from other users.
     *
     * @return void
     */
    public function a_user_can_see_messages_from_other_users()
    {
        $users = factory(User::class, 3)->create();
        
        $this->browse(function ($browserOne, $browserTwo, $browserThree) use ($users) {
            $browserOne->loginAs($users->get(0))
                ->assertAuthenticated()
                ->visit(new ChatPage);
  
            $browserTwo->loginAs($users->get(1))
                ->assertAuthenticated()
                ->visit(new ChatPage);

            $browserThree->loginAs($users->get(2))
                ->assertAuthenticated()
                ->visit(new ChatPage);

            $browserOne->typeMessage('Hi there')
            ->sendMessage();

            $browserTwo->pause(1000)->with('@chatMessages', function ($messages) use ($users) {
                $messages->assertSee('Hi there')
                    ->assertSee($users->get(0)->name);
            });

            $browserThree->pause(1000)->with('@chatMessages', function ($messages) use ($users) {
                $messages->assertSee('Hi there')
                    ->assertSee($users->get(0)->name);
            });
        });
    }
}
