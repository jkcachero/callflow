<?php

use App\Models\User;

it('returns true for isAgent only when role is agent', function () {
    $user = new User(['role' => 'agent']);

    expect($user->isAgent())->toBeTrue();
    expect($user->isSupervisor())->toBeFalse();
    expect($user->isAdmin())->toBeFalse();
});

it('returns true for isSupervisor only when role is supervisor', function () {
    $user = new User(['role' => 'supervisor']);

    expect($user->isAgent())->toBeFalse();
    expect($user->isSupervisor())->toBeTrue();
    expect($user->isAdmin())->toBeFalse();
});

it('returns true for isAdmin only when role is admin', function () {
    $user = new User(['role' => 'admin']);

    expect($user->isAgent())->toBeFalse();
    expect($user->isSupervisor())->toBeFalse();
    expect($user->isAdmin())->toBeTrue();
});
