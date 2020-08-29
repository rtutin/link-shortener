<?php

namespace App;

use Stevebauman\Location\Facades\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Tuupola\Base58;
use Carbon\Carbon;
use Exception;

class Link extends Model
{
    public $timestamps = false;
    protected $fillable = ['url', 'expiration_to'];
    public $base58 = null;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->base58 = new Base58;
    }

    public static function findByShortLink($shortLink)
    {
        $base58 = new Base58;
        return Link::find($base58->decodeInteger($shortLink));
    }

    public function toShort()
    {
        return $this->base58->encodeInteger($this->id);
    }

    public function fromShort($shortLink)
    {
        return $this->base58->decodeInteger($shortLink);
    }

    public function getSecret()
    {
        return $this->base58->encode(hash('ripemd160', $this->id . env('APP_KEY')));
    }

    public function isExpiried()
    {
        if ($this->expiration_to !== null) {
            return Carbon::parse($this->expiration_to)->lessThan(Carbon::now());
        }

        return false;
    }

    public function addTTL($ttl)
    {
        $expirationTo = Carbon::createFromTimestamp(time() + $ttl);
        $this->expiration_to = $expirationTo->toDateTimeString();
    }

    public function requests()
    {
        return $this->hasMany('App\Request');
    }

    public function addRequest($userAgent)
    {
        $location = Location::get();
        $request = new \App\Request;
        $request->link_id = $this->id;
        $request->created_at = Carbon::now();
        $request->country = $location->countryName;
        $request->city = $location->cityName;
        $request->user_agent = $userAgent;

        if ($request->save()) {
            return $request;
        }

        return null;
    }
}
