<?php

it('renders the home page and every icon-driven section', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Build faster with')
        ->assertSee('Everything you need, out of the box')
        ->assertSee('Built for speed, themed for you')
        ->assertSee('Production-grade from the start')
        ->assertSee('Yours to fork, extend and ship')
        ->assertSee('The boring parts, solved')
        ->assertSee('Why not just...')
        ->assertSee('Ready to build something great?');
});
