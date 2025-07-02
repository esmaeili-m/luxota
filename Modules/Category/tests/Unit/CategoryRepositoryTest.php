<?php

namespace Modules\Category\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Modules\Category\App\Models\Category;
use Modules\Category\Repositories\CategoryRepository;
use Modules\Category\Services\CategoryService;
use Tests\TestCase;

uses(TestCase::class);
uses(Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration::class);

beforeEach(function () {
    $this->repository = new CategoryRepository();
});

it('can fetch all categories', function () {
    $categories = $this->repository->all();
    expect($categories->count())->toBeGreaterThanOrEqual(3);
});

it('can find a Category by id', function () {
    $category = Category::factory()->create();
    $found = $this->repository->find($category->id);
    expect($found)->not()->toBeNull();
    expect($found->id)->toEqual($category->id);
});
