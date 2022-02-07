<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Psi\FlexAdmin\Tests\Models\User;
use Psi\FlexAdmin\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
uses(DatabaseTransactions::class)->in('Feature');

/**
 * Set the currently logged in user for the application.
 *
 * @return TestCase
 */
function actingAs(User $user, string $driver = null)
{
    return test()->actingAs($user, $driver);
}

function createRequest(array $params = []): Request
{
    return  Request::create('http://test.com', 'GET', $params);
}
