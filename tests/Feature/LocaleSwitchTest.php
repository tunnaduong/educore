<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleSwitchTest extends TestCase
{
    /** @test */
    public function it_switches_locale_and_persists_in_cookie_and_session()
    {
        $response = $this->get('/lang/en');

        $response->assertStatus(302);
        $response->assertCookie('locale', 'en');
        $this->assertEquals('en', session('locale'));

        $this->get('/test-locale')
            ->assertJsonFragment([
                'current_locale' => 'en',
                'session_locale' => 'en',
                'cookie_locale' => 'en',
            ]);
    }
}
