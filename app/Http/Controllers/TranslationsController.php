<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

use App\Models\Translation;
use App\Models\Set;

class TranslationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function translations(Request $request):Renderable
    {
        $user = Auth::user();
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', $user->id)
            ->limit(1)
            ->get(['id', 'name'])
            ->toArray()[0];

        $translations = Translation::where('set', $set['id'])
            ->orderBy('id', 'desc')
            ->get(['id', 'language', 'code', 'value'])
            ->toArray();
        return view('translations.translations', [
            'token' => $user->api_token,
            'dangerous_actions_key' => $user->dangerous_actions_key,
            'translations' => $translations,
            'set' => $set,
        ]);
    }

    public function add(Request $request):RedirectResponse
    {
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $translation = new Translation;
        $translation->set = $set['id'];
        $translation->code = $request->get('code');
        $translation->language = $request->get('language');
        $translation->value = $request->get('value');
        $translation->save();

        return redirect()->route('translations', (int)$set['id']);
    }

    public function delete(Request $request):RedirectResponse
    {
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $translation = Translation
            ::where('id', $request->route('data_id'))
            ->where('set', $set['id'])
            ->limit(1);

        if ($translation->count() === 0) {
            return redirect()->route('translations', (int)$set['id'])->with('error', 'Error: Data item not found.');
        }

        $translation->delete();
        return redirect()->route('translations', (int)$set['id'])->with('status', 'Success: The data item was deleted.');
    }

    public function update(Request $request):RedirectResponse
    {
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        Translation
            ::where('id', $request->route('translation_id'))
            ->where('set', $set['id'])
            ->update([
                'language' => $request->get('language'),
                'name' => $request->get('name'),
                'value' => $request->get('value'),
            ]);

        return redirect()->route('translations', $set['id']);
    }

}
