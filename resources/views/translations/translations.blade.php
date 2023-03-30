@extends('layouts.admin')

@section('container-class')
    container
@endsection

@section('body-class')
    col-md-12
@endsection

@section('admin-title')
    <div>
        <span><a class="btn btn-link p-0" href="{{ route('sets') }}">Dashboard</a>/Set/</span><strong>{{ $set['name'] }}</strong>
    </div>
@endsection

@section('admin-body')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <hr>
                <span>---Translations get link---</span>
                <p class="mb-1">
                    <strong id="appLinkText">{{ route('translations.get', [$set['id'], $token]) }}/[language]</strong>
                </p>
                <input style="display: none" id="copyLinkButton" type="submit" value="Copy Link">

                <hr class="mt-4 mb-4">

                <strong>---Add translation:---</strong>
                <div class="container">
                    <form class="row" method="POST" action="{{ route('translation.add', $set['id']) }}" >
                        {{ csrf_field() }}
                        <textarea rows="5" name="value" placeholder="Value" class="col-md-12" required></textarea>
                        <input name="language" type="text" value="" placeholder="Language" class="col-md-2" required>
                        <input name="code" type="text" value="" placeholder="Code" class="col-md-7" required>
                        <input type="submit" value="Add" class="col-md-3">
                    </form>
                </div>

                <hr class="mt-4 mb-4">

                <table class="table table-hover table-sm">
                    <thead class="table-primary">
                        <tr>
                            <th class="table-secondary align-middle text-center">Code/Language</th>
                            @foreach($allLanguages as $language)
                                <th class="align-middle text-center">{{ $language }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($translations as $language => $translation)
                            <tr>
                                <td class="table-primary fw-bold align-middle text-center">{{ $allCodes[$loop->index] }}</td>
                                @foreach($translation as $code => $value)
                                    <td>
                                        <form action="{{ route('translation.update', [$set['id'], $value['id']]) }}" type="post">
                                            <input name="language" type="hidden" value="{{ $language }}">
                                            <input name="code" type="hidden" value="{{ $code }}">
                                            <textarea class="w-100" name="value">{{ $value['value'] }}</textarea>
                                        </form>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <hr>
            <div class="col-md-6">
                @include('components.dangerous_action_form')
            </div>
        </div>
    </div>
@endsection
