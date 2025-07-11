<?php

namespace Modules\Language\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Language\App\resources\LanguageResource;
use Modules\Language\Services\LanguageService;

class LanguageController extends Controller
{
    protected LanguageService $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }
    public function index(LanguageService $service): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $languages = $service->getAll();
        return LanguageResource::collection($languages);
    }

}
