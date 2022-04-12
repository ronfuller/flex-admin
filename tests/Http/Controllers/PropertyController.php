<?php

namespace Psi\FlexAdmin\Tests\Http\Controllers;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Tests\Models\Property;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $count = $request->input('count', 100);

        return response()->json(Flex::forIndex(Property::class)->withoutFilters()->toArray(createRequest(['perPage' => $count])));
    }
}
