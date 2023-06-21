<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Serializers\ItemSerializer;
use App\Serializers\ItemsSerializer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;

class ItemController extends Controller
{
    private $validationRules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'url' => 'required|url',
        'description' => 'required|string',
    ];

    private function createCommonMarkConverter(): CommonMarkConverter
    {
        return new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
    }

    public function index(): JsonResponse
    {
        $items = Item::all();

        return response()->json(['items' => (new ItemsSerializer($items))->getData()]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, $this->validationRules);

        $converter = $this->createCommonMarkConverter();

        $item = Item::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'url' => $request->input('url'),
            'description' => $converter->convert($request->input('description'))->getContent(),
        ]);

        $serializer = new ItemSerializer($item);

        return response()->json(['item' => $serializer->getData()]);
    }

    public function show($id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $serializer = new ItemSerializer($item);

        return response()->json(['item' => $serializer->getData()]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $this->validate($request, $this->validationRules);

        $converter = $this->createCommonMarkConverter();

        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->name = $request->input('name');
        $item->url = $request->input('url');
        $item->price = $request->input('price');
        $item->description = $converter->convert($request->input('description'))->getContent();
        $item->save();

        return response()->json(['item' => (new ItemSerializer($item))->getData()]);
    }
}
