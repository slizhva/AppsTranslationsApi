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
            <div class="col-md-8">
                <hr>
                <span>---Translations get link---</span>
                <p class="mb-1">
                    <strong id="appLinkText">{{ route('translations.get', [$set['id'], $token]) }}</strong>
                </p>
                <input style="display: none" id="copyLinkButton" type="submit" value="Copy Link">

                <hr class="mt-4 mb-4">

                <strong>---Translations:---</strong>
                <form class="row" method="POST" action="{{ route('translation.add', $set['id']) }}" >
                    {{ csrf_field() }}
                    <input name="language" type="text" value="" placeholder="Language" class="col-md-9" required>
                    <input name="code" type="text" value="" placeholder="Code" class="col-md-9" required>
                    <input name="value" type="text" value="" placeholder="Value" class="col-md-9" required>
                    <input type="submit" value="Add" class="col-md-3">
                </form>
                <div class="row border-bottom border-top bg-light">
                    <div class="col-md-1 border-start">Language</div>
                    <div class="col-md-3 border-start">Code</div>
                    <div class="col-md-6 border-start">Value</div>
                    <div class="col-md-2 border-start border-end"></div>
                </div>
                @foreach($translations as $translation)
                    <div class="row border-bottom align-items-center">
                        <div class="col-md-1 border-start">
                            <input name="language" type="text" value="{{ $translation['language'] }}" placeholder="Language" class="col-md-9" required>
                        </div>
                        <div class="col-md-3 border-start">
                            <input name="code" type="text" value="{{ $translation['code'] }}" placeholder="Code" class="col-md-9" required>
                        </div>
                        <div class="col-md-6 border-start">
                            <input name="value" type="text" value="{{ $translation['value'] }}" placeholder="Value" class="col-md-9" required>
                        </div>
                        <div class="col-md-2 border-start border-end">
                            <form method="POST" action="{{ route('translation.delete', $translation['id']) }}">
                                {{ csrf_field() }}
                                <input name="dangerous_actions_key" class="dangerous-action-key-value" type="text" value="" hidden>
                                <input class="col-md-12 pt-0 pb-0 dangerous-action-button" type="submit" value="Delete" disabled>
                            </form>
                        </div>
                    </div>
                @endforeach
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
