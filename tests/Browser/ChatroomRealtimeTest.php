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
           $browsers = [$browserOne, $browserTwo, $browserThree];
           foreach ($browsers as $index => $browser) {
               $browser->loginAs($users->get($index))
                ->assertAuthenticated()
                ->visit(new ChatPage);
           }

           $browserOne->typeMessage('Hi There.')
           ->sendMessage();

           foreach (array_slice($browsers, 1, 2) as $index => $browser) {
               $browser->waitFor('@firstChatMessage')
               ->with('@chatMessages', function ($messages) use ($users) {
                   $messages->assertSee('Hi There.')
                   ->assertSee($users->get(0)->name)
                   ->assertMissing('@ownMessage');
               });
           }

           $browserThree->typeMessage('Hello :)')
           ->sendMessage();

           foreach (array_slice($browsers, 0, 1) as $index => $browser) {
               $browser->waitForText('Hello :)')
               ->with('@chatMessages', function ($messages) use ($users) {
                   $messages->assertSee('Hello :)')
                   ->assertSee($users->get(2)->name);
               });
           }


        });
    }
}
