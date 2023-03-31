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

    private function getAllArrayValueByKey(array $array, string $key): array {
        return array_values(array_unique(array_map(
            static function($elem) use ($key) {return $elem[$key];}, $array
        )));
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
            ->orderBy('language', 'asc')
            ->get(['id', 'language', 'code', 'value'])
            ->toArray();


        $allLanguages = $this->getAllArrayValueByKey($translations, 'language');
        $allCodes = $this->getAllArrayValueByKey($translations, 'code');

        $translationsTable = [];
        foreach ($allCodes as $code) {
            foreach ($allLanguages as $language) {
                foreach ($translations as $translation) {
                    if ($translation['language'] === $language && $translation['code'] === $code) {
                        $translationsTable[$code][$language] = [
                            'value' => $translation['value'],
                            'id' => $translation['id']
                        ];
                        break;
                    }
                }
                if (empty($translationsTable[$code][$language])) {
                    $translationsTable[$code][$language] = [
                        'value' => '',
                        'id' => null
                    ];
                }
            }
        }

        return view('translations.translations', [
            'token' => $user->api_token,
            'dangerous_actions_key' => $user->dangerous_actions_key,
            'set' => $set,
            'allLanguages' => $allLanguages,
            'allCodes' => $allCodes,
            'translations' => $translationsTable,
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
        if (empty($request->get('translation_id'))) {
            return redirect()->route('translations', (int)$request->route('set_id'))->with('error', 'Error: Data item not found.');
        }

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $translation = Translation
            ::where('id', $request->get('translation_id'))
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
        if (empty($request->get('translation_id'))) {
            $this->add($request);
            return redirect()->route('translations', $request->route('set_id'));
        }

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $translation = Translation
            ::where('id', $request->get('translation_id'))
            ->where('set', $set['id']);
        if (empty($request->get('value'))) {
            $translation->delete();
        } else {
            $translation->update([
                'language' => $request->get('language'),
                'code' => $request->get('code'),
                'value' => $request->get('value'),
            ]);
        }

        return redirect()->route('translations', $set['id']);
    }

}
