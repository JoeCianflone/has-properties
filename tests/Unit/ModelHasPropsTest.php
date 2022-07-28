<?php declare(strict_types=1);

use function Pest\Faker\faker;
use JoeCianflone\HasProperties\Tests\Dummy\EnumTestStub;
use JoeCianflone\HasProperties\Tests\Dummy\ModelNoProps;
use JoeCianflone\HasProperties\Tests\Dummy\ModelZeroProps;
use JoeCianflone\HasProperties\Tests\Dummy\ModelStubFilledGuarded;
use JoeCianflone\HasProperties\Exceptions\PropArrayMissingException;
use JoeCianflone\HasProperties\Exceptions\PropMassAssignmentException;

it('throws exception if no props are set', function (): void {
    $m = new ModelNoProps();
})->throws(PropArrayMissingException::class);

it('does not run on empty array', function (): void {
    $m = new ModelZeroProps();
    expect($m->getAttributes())->toBeArray()->toHaveCount(0);
    expect($m->getFillable())->toBeArray()->toHaveCount(0);
    expect($m->getGuarded())->toBeArray()->toHaveCount(1);
});

it('hides attributes marked as hidden', function ($user): void {
    expect($user->toArray())->not()->toHaveKeys(['password']);
})->with('users');

it('sets a default value to props with attribute', function ($user): void {
    expect($user->is_enum)->toEqual(EnumTestStub::ONE);
    expect($user->id)->toEqual('1123');
})->with('users');

it('sets a new value after the default was set', function ($user): void {
    expect($user->id)->toEqual('1123');
    $newUUID = faker()->uuid();

    $user->id = $newUUID;
    expect($user->id)->toEqual($newUUID);
})->with('users');

it('sets casts a value correctly', function ($user): void {
    $user->is_boolean = 1;
    expect($user->is_boolean)->toBeBool();

    $user->is_enum = EnumTestStub::TWO;
    expect($user->is_enum)->toEqual(EnumTestStub::TWO);
})->with('users');

it('marks attributes as fillable by default', function ($user): void {
    expect($user->getFillable())->toHaveCount(5);
    expect($user->getFillable())->not()->toHaveKey('email');
})->with('users');

it('marks attributes as guarded if passed guarded', function ($user): void {
    expect($user->getGuarded())->toHaveCount(1);
    expect($user->getFillable())->not()->toHaveKey('email');
})->with('users');

it('throws exception if marked as guarded and fillable', function (): void {
    $m = new ModelStubFilledGuarded();
})->throws(PropMassAssignmentException::class);

it('marks attributes as guarded by default', function ($user): void {
    expect($user->getGuarded())->toHaveCount(5);
    expect($user->getGuarded())->not()->toHaveKey('email');
})->with('guardedUsers');

it('marks attributes as unguarded and fillable by default', function ($user): void {
    expect($user->getFillable())->toHaveCount(6);
    expect($user->getGuarded())->toBeEmpty();
})->with('unguardedUsers');

it('will not add double-add guarded', function ($user): void {
    expect($user->getGuarded())->toHaveCount(5);
    expect(collect($user->getGuarded())->duplicates()->toArray())->toBeEmpty();
})->with('guardedUsers');

it('will not add double-add filled', function ($user): void {
    expect($user->getFillable())->toHaveCount(5);
    expect(collect($user->getFillable())->duplicates()->toArray())->toBeEmpty();
})->with('users');
