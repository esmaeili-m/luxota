<?php

namespace Modules\Country\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Country\App\Http\Requests\CreateCountryRequest;
use Modules\Country\App\Http\Requests\UpdateCountryRequest;
use Modules\Country\App\resources\CountryResource;
use Modules\Country\Services\CountryService;

class CountryController extends Controller
{
    protected CountryService $service;

    public function __construct(CountryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $input = $request->only($request->only(['status','en','abb','phone_code','zone_id','currency_id','per_page','paginate']));
        $input['paginate'] = !$request->has('paginate') || $request->boolean('paginate');
        $countries = $this->service->getCountries($input);
        return CountryResource::collection($countries);


    }

    public function update(UpdateCountryRequest $request, $id): CountryResource
    {
        $country = $this->service->update($id, $request->validated());

        if (!$country) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new CountryResource($country);
    }


}
