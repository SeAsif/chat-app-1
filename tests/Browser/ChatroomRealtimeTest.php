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

    /**
     * @test A user can see messages from other users.
     *
     * @return void
     */
    public function users_are_added_to_the_online_list_when_joining()
    {
        $users = factory(User::class, 2)->create();

        //$test_user = $users->get(0);
        //$test_user->password = bcrypt('zaq123');
        
        $this->browse(function ($browserOne, $browserTwo) use ($users) {
            $browserOne->loginAs($users->get(0))
            ->visit(new ChatPage)// use with when element cant be found...
            ->with('@onlineList', function ($online) use ($users){
                $online->waitForText($users->get(0)->name)// starts at the top, so at times loosely checks
                ->assertSee($users->get(0)->name)
                ->assertSee('1 user online');
            });

            $browserTwo->loginAs($users->get(1))
            ->visit(new ChatPage)
            ->with('@onlineList', function ($online) use ($users){
                $online->waitForText($users->get(1)->name)
                ->assertSee($users->get(1)->name)
                ->assertSee('2 users online');
            });
        });
    }
}
