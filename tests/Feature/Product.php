<?php

it('has product page', function () {
    $response = $this->get('/product');

    $response->assertStatus(200);
});
