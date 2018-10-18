<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class ChatPage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/chat';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     **/
    public function typeMessage(Browser $browser, $body = null)
    {
        $browser->type('@body', $body)
            ->pause(500);
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     **/
    public function sendMessage(Browser $browser)
    {
        // where we want to type specific keys since we cant click a button
        $browser->keys('@body', ['{enter}']);
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@body' => 'textarea[id="body"]',
        ];
    }
}
