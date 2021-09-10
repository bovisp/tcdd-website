<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\Part;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentBuilder\PartResource;

class PartsController extends Controller
{
    public function show (Part $part) {
        return new PartResource($part);
    }

    public function destroy(Part $part)
    {
        $typeClassName = 'App\\' . ucfirst(request('type')) . 'Part';

        $partType = $typeClassName::wherePartId($part->id)->first();

        $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst(request('type'));

        (new $destroyClassName($partType))->delete();

        $part->delete();

        return;
    }
}
