<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public static function buildQuery( $searchCountry = null, $searchState = null)
    {

        $query = Self::select('id','country','state','country_code','phone_number');
        if($searchCountry) {
            $query->searchCountry($searchCountry);
        }
        if($searchState != null) {
            $query->searchState($searchState);
        }
        return $query;

    }
    public function scopeSearchCountry($query,$searchCountry)
    {
        return $query->where(function($query) use ($searchCountry) {
                $query->where('country', 'like', '%' . $searchCountry . '%');
            });
    }
    public function scopeSearchState($query,$searchState)
    {
        return $query->where(function($query) use ($searchState) {
            $query->where('state','=',$searchState);
        });
    }
}
