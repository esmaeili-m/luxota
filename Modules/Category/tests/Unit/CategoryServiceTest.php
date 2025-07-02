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

uses(TestCase::class, RefreshDatabase::class);
uses(Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration::class);

beforeEach(function () {
    Storage::fake('public');
    $this->mockedRepo = Mockery::mock(CategoryRepository::class);
    $this->service = new CategoryService($this->mockedRepo);
});

test('it stores category with multilingual fields and uploaded image', function () {
    $fakeImage = UploadedFile::fake()->image('book.jpg');

    $data = [
        'title' => ['fa' => 'کتاب‌ها', 'en' => 'Books'],
        'subtitle' => ['fa' => 'زیرعنوان فارسی', 'en' => 'English Subtitle'],
        'slug' => 'books',
        'image' => $fakeImage,
        'status' => true,
        'parent_id' => null,
    ];

    // شبیه‌سازی کلاس کامل با namespace درست
    Mockery::mock('alias:App\Services\Uploader')
        ->shouldReceive('uploadImage')
        ->once()
        ->with($fakeImage, 'categories')
        ->andReturn('categories/fake.jpg');
    $this->mockedRepo
        ->shouldReceive('create')
        ->once()
        ->with([
            'title' => ['fa' => 'کتاب‌ها', 'en' => 'Books'],
            'subtitle' => ['fa' => 'زیرعنوان فارسی', 'en' => 'English Subtitle'],
            'slug' => 'books',
            'image' => 'categories/fake.jpg',
            'status' => true,
            'parent_id' => null,
        ])
        ->andReturn(new Category([
            'slug' => 'books',
            'title' => ['fa' => 'کتاب‌ها', 'en' => 'Books'],
            'subtitle' => ['fa' => 'زیرعنوان فارسی', 'en' => 'English Subtitle'],
            'image' => 'categories/fake.jpg',
            'status' => true,
        ]));

    $result = $this->service->create($data);

    expect($result)->toBeInstanceOf(Category::class)
        ->and($result->slug)->toBe('books')
        ->and($result->title['fa'])->toBe('کتاب‌ها')
        ->and($result->image)->toBe('categories/fake.jpg');
});
