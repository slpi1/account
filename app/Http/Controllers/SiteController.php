<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteCreateRequest;
use App\Models\Site;

class SiteController extends Controller
{
    public function index()
    {
        return Site::all();
    }

    public function create(SiteCreateRequest $request)
    {
        return Site::create([
            'name'        => $request->input('name'),
            'host'        => $request->input('host'),
            'description' => $request->input('description'),
        ]);
    }
}
