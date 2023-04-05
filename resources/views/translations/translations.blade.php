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
                    <div class="row">
                        <div class="col-md-8">
                            <form class="row" method="POST" action="{{ route('translation.add', $set['id']) }}" >
                                {{ csrf_field() }}
                                <textarea rows="5" name="value" placeholder="Value" class="col-md-12" required></textarea>
                                <input name="language" type="text" value="" placeholder="Language" class="col-md-2" required>
                                <input name="code" type="text" value="" placeholder="Code" class="col-md-7" required>
                                <input type="submit" value="Add" class="col-md-3">
                            </form>
                        </div>
                        <div class="col-md-4">
                            <form
                                class="d-flex flex-column h-100 justify-content-center align-items-center"
                                method="POST"
                                action="{{ route('translation.upload', $set['id']) }}"
                                enctype="multipart/form-data"
                            >
                                {{ csrf_field() }}
                                <input name="translations" type="file" class="w-50 mb-2" required>
                                <input name="language" type="text" placeholder="Language" class="w-50 mb-2" required>
                                <input type="submit" value="Upload" class="w-50">
                            </form>
                        </div>
                    </div>
                </div>
                <hr class="mt-4 mb-4">
                <div class="col-md-12 mb-4">
                    <h5 id="hiddenFieldsTitle" class="fw-bold d-none">Hidden languages:</h5>
                    <div data-language="Code/Language" class="hidden-fields d-none btn btn-warning"><strong class="pe-none">Code/Language+</strong></div>
                    @foreach($allLanguages as $key => $language)
                        <div data-language="{{ $language }}" class="hidden-fields d-none btn btn-warning">{{ $language }}<strong class="pe-none">+</strong></div>
                    @endforeach
                </div>

                <table id="translationsTable" class="table table-hover table-sm">
                    <thead class="table-primary">
                        <tr>
                            <th class="table-secondary align-middle text-center">Code/Language</th>
                            @foreach($allLanguages as $language)
                                <th class="align-middle text-center">{{ $language }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($translations as $code => $translation)
                            <tr>
                                <td class="table-primary fw-bold align-middle text-center">{{ $allCodes[$loop->index] }}</td>
                                @foreach($translation as $language => $value)
                                    <td class="p-0">
                                        <form action="{{ route('translation.update', $set['id']) }}" method="post">
                                            {{ csrf_field() }}
                                            <input name="translation_id" type="hidden" value="{{ $value['id'] }}">
                                            <input name="language" type="hidden" value="{{ $language }}">
                                            <input name="code" type="hidden" value="{{ $code }}">
                                            <textarea class="form-control" rows="10" class="w-100" name="value">{{ $value['value'] }}</textarea>
                                        </form>
                                        <form class="delete-form" method="POST" action="{{ route('translation.delete', $set['id']) }}">
                                            {{ csrf_field() }}
                                            <input name="translation_id" type="hidden" value="{{ $value['id'] }}">
                                            <input name="dangerous_actions_key" class="dangerous-action-key-value" type="text" value="" hidden>
                                            <input class="col-md-12 pt-0 pb-0 dangerous-action-button" type="submit" value="Delete" disabled>
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
            <div class="col-md-5">
                @include('components.dangerous_action_form')
            </div>
        </div>
    </div>
@endsection
