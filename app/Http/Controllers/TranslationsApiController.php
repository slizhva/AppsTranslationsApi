<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Set;
use App\Models\Translation;

class TranslationsApiController extends Controller
{

    public function get(Request $request):JsonResponse
    {
        $user = User
            ::where('api_token', $request->route('token'))
            ->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'wrong token',
            ]);
        }

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', $user->id)
            ->limit(1)
            ->get(['id', 'name'])
            ->toArray()[0];

        $translations = Translation::where('set', $set['id'])
            ->get(['id', 'language', 'code', 'value'])
            ->toArray();

        if (!isset($translations[0])) {
            return response()->json([
                'status' => false,
                'message' => 'wrong set id',
            ]);
        }
        return response()->json([
            'status' => true,
            'value' => $translations
        ]);
    }
}
