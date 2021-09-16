<?php

namespace App\Http\Controllers;


use App\Http\Requests\countryRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;



class CountryController extends Controller
{
    public function index(CountryRequest $request)
    {
        $columns = ['country','state','country_code','phone_number'];

        $column = $request->input('column');
        $length = $request->input('length');
        $dir = $request->input('dir');
        $searchCountry = $request->input('searchCountry');
        $searchState = $request->input('searchState');

        $query = Country::buildQuery($searchCountry, $searchState);
        $countries = $query->orderBy($columns[$column],$dir)->cursorPaginate($length); //cursor pagination has less information, but more efficient
        return ['data' => $countries, 'sync' => $request->input('sync')];

    }
    public function getCountryNames()
    {
        $countries = Cache::rememberForever('countries', function() {
            return Country::select('country')->get();
        });

        return ['data' => $countries];
    }
}
