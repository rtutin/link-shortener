<?php

namespace App\Http\Controllers;

use Stevebauman\Location\Facades\Location;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Link;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'url' => 'required|url',
            'ttl' => 'integer|min:0|nullable',
        ]);

        $link = new Link;

        $link->url = $validatedData['url'];

        if ($validatedData['ttl'] !== null) {
            $link->addTTL($validatedData['ttl']);
        }

        if (!$link->save()) {
            abort(500);
        }

        return redirect()->route('stats', [
            'shortLink' => $link->toShort(),
            'secret' => $link->getSecret()
        ]);

    }

    public function goto($shortLink, Request $r)
    {
        $link = Link::findByShortLink($shortLink);

        if ($link === null) {
            abort(404);
        }

        if ($link->isExpiried()) {
            abort(404);
        }

        if ($link->addRequest($r->header('User-Agent')) === null) {
            abort(500);
        }
        
        return redirect($link->url);
    }

    public function show($shortLink, $secret, Request $request)
    {
        $link = Link::findByShortLink($shortLink);

        if ($link === null) {
            abort(404);
        }

        if ($link->getSecret() !== $secret) {
            abort(403);
        }
        
        return view('stats', ['link' => $link]);
    }
}
