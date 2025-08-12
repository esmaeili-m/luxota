<?php

namespace Modules\Product\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Modules\Product\App\Models\Product;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Services\ProductService;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);
uses(Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration::class);

beforeEach(function () {
    Storage::fake('public');
    $this->mockedRepo = Mockery::mock(ProductRepository::class);
    $this->service = new ProductService($this->mockedRepo);
});

test('it stores product with multilingual fields and uploaded image', function () {
    $fakeImage = UploadedFile::fake()->image('product.jpg');

    $data = [
        'title' => ['fa' => 'محصول', 'en' => 'Product'],
        'description' => ['fa' => 'توضیحات محصول', 'en' => 'Product description'],
        'slug' => 'product',
        'image' => $fakeImage,
        'last_version_update_date' => now(),
        'version' => 1.0,
        'product_code' => 1001,
        'order' => 1,
        'show_price' => true,
        'payment_type' => true,
        'status' => true,
        'category_id' => 1,
    ];

    // Mock the Uploader service
    Mockery::mock('alias:App\Services\Uploader')
        ->shouldReceive('uploadImage')
        ->once()
        ->with($fakeImage, 'products')
        ->andReturn('products/fake.jpg');

    $this->mockedRepo
        ->shouldReceive('create')
        ->once()
        ->with([
            'title' => ['fa' => 'محصول', 'en' => 'Product'],
            'description' => ['fa' => 'توضیحات محصول', 'en' => 'Product description'],
            'slug' => 'product',
            'image' => 'products/fake.jpg',
            'last_version_update_date' => $data['last_version_update_date'],
            'version' => 1.0,
            'product_code' => 1001,
            'order' => 1,
            'show_price' => true,
            'payment_type' => true,
            'status' => true,
            'category_id' => 1,
        ])
        ->andReturn(new Product([
            'slug' => 'product',
            'title' => ['fa' => 'محصول', 'en' => 'Product'],
            'description' => ['fa' => 'توضیحات محصول', 'en' => 'Product description'],
            'image' => 'products/fake.jpg',
            'last_version_update_date' => $data['last_version_update_date'],
            'version' => 1.0,
            'product_code' => 1001,
            'order' => 1,
            'show_price' => true,
            'payment_type' => true,
            'status' => true,
            'category_id' => 1,
        ]));

    $result = $this->service->create($data);

    expect($result)->toBeInstanceOf(Product::class)
        ->and($result->slug)->toBe('product')
        ->and($result->title['fa'])->toBe('محصول')
        ->and($result->image)->toBe('products/fake.jpg')
        ->and($result->version)->toBe(1.0)
        ->and($result->product_code)->toBe(1001)
        ->and($result->order)->toBe(1)
        ->and($result->show_price)->toBe(true)
        ->and($result->payment_type)->toBe(true);
});

test('it generates slug automatically when not provided', function () {
    $data = [
        'title' => ['fa' => 'محصول جدید', 'en' => 'New Product'],
        'description' => ['fa' => 'توضیحات', 'en' => 'Description'],
        'category_id' => 1,
    ];

    $this->mockedRepo
        ->shouldReceive('create')
        ->once()
        ->with(Mockery::on(function ($data) {
            return $data['slug'] === 'new-product';
        }))
        ->andReturn(new Product($data));

    $result = $this->service->create($data);

    expect($result)->toBeInstanceOf(Product::class);
});

test('it sets default values automatically when not provided', function () {
    $data = [
        'title' => ['fa' => 'محصول', 'en' => 'Product'],
        'description' => ['fa' => 'توضیحات', 'en' => 'Description'],
        'category_id' => 1,
    ];

    $this->mockedRepo
        ->shouldReceive('create')
        ->once()
        ->with(Mockery::on(function ($data) {
            return isset($data['last_version_update_date']) &&
                   isset($data['product_code']) &&
                   isset($data['order']) &&
                   isset($data['version']) &&
                   $data['status'] === true &&
                   $data['show_price'] === true &&
                   $data['payment_type'] === true;
        }))
        ->andReturn(new Product($data));

    $result = $this->service->create($data);

    expect($result)->toBeInstanceOf(Product::class);
});

test('it updates product with new image', function () {
    $fakeImage = UploadedFile::fake()->image('new-product.jpg');
    $existingProduct = new Product([
        'id' => 1,
        'image' => 'products/old-image.jpg',
        'product_code' => 1001,
        'order' => 1,
        'last_version_update_date' => now(),
    ]);

    $data = [
        'title' => ['fa' => 'محصول به‌روزرسانی شده', 'en' => 'Updated Product'],
        'description' => ['fa' => 'توضیحات جدید', 'en' => 'New description'],
        'image' => $fakeImage,
        'version' => 1.1,
        'show_price' => false,
        'payment_type' => false,
        'category_id' => 1,
    ];

    $this->mockedRepo
        ->shouldReceive('find')
        ->once()
        ->with(1)
        ->andReturn($existingProduct);

    Mockery::mock('alias:App\Services\Uploader')
        ->shouldReceive('deleteImage')
        ->once()
        ->with('products/old-image.jpg');

    Mockery::mock('alias:App\Services\Uploader')
        ->shouldReceive('uploadImage')
        ->once()
        ->with($fakeImage, 'products')
        ->andReturn('products/new-image.jpg');

    $this->mockedRepo
        ->shouldReceive('update')
        ->once()
        ->andReturn(true);

    $result = $this->service->update(1, $data);

    expect($result)->toBeInstanceOf(Product::class);
});

test('it returns null when updating non-existent product', function () {
    $this->mockedRepo
        ->shouldReceive('find')
        ->once()
        ->with(999)
        ->andReturn(null);

    $result = $this->service->update(999, []);

    expect($result)->toBeNull();
});

test('it deletes product successfully', function () {
    $product = new Product(['id' => 1]);

    $this->mockedRepo
        ->shouldReceive('find')
        ->once()
        ->with(1)
        ->andReturn($product);

    $product->shouldReceive('delete')
        ->once()
        ->andReturn(true);

    $result = $this->service->delete(1);

    expect($result)->toBeTrue();
});

test('it returns false when deleting non-existent product', function () {
    $this->mockedRepo
        ->shouldReceive('find')
        ->once()
        ->with(999)
        ->andReturn(null);

    $result = $this->service->delete(999);

    expect($result)->toBeFalse();
});

test('it handles string image path correctly', function () {
    $data = [
        'title' => ['fa' => 'محصول', 'en' => 'Product'],
        'description' => ['fa' => 'توضیحات', 'en' => 'Description'],
        'image' => 'products/existing-image.jpg',
        'category_id' => 1,
    ];

    $this->mockedRepo
        ->shouldReceive('create')
        ->once()
        ->with(Mockery::on(function ($data) {
            return $data['image'] === 'products/existing-image.jpg';
        }))
        ->andReturn(new Product($data));

    $result = $this->service->create($data);

    expect($result)->toBeInstanceOf(Product::class);
});

test('it handles video_script field correctly', function () {
    $data = [
        'title' => ['fa' => 'محصول', 'en' => 'Product'],
        'description' => ['fa' => 'توضیحات', 'en' => 'Description'],
        'video_script' => 'Sample video script content',
        'category_id' => 1,
    ];

    $this->mockedRepo
        ->shouldReceive('create')
        ->once()
        ->with(Mockery::on(function ($data) {
            return $data['video_script'] === 'Sample video script content';
        }))
        ->andReturn(new Product($data));

    $result = $this->service->create($data);

    expect($result)->toBeInstanceOf(Product::class);
}); 