@extends('layout')

@section('content')

    <importer inline-template importer="{{ $importer->name() }}">

        <div class="card">
            <div class="head">
                <h1>Import from {{ $importer->title() }}</h1>
            </div>
        </div>

        <template v-if="!success && !importing">
            <div class="card flat-bottom">
                <div class="head">
                    <h1>Export</h1>
                </div>
            </div>
            <div class="card flat-top">
                {!! markdown($importer->instructions()) !!}
            </div>

            <div class="card flat-bottom">
                <div class="head">
                    <h1>Import</h1>
                </div>
            </div>
            <div class="card flat-top">
                <div class="form-group">
                    <label>Import JSON</label>
                    <small class="help-block"> Paste the JSON file that you got from Step 1. </small>
                    <textarea class="form-control" v-model="json"></textarea>
                </div>
                <button class="btn btn-primary" :disabled="!json || importing || success" @click.prevent="upload">Import</button>
            </div>
        </template>

        <template v-if="importing">
            <div class="card flat-bottom">
                <div class="head">
                    <h1>Importing...</h1>
                </div>
            </div>
            <div class="card flat-top">
                <div class="loading loading-basic">
                    <span class="icon icon-circular-graph animation-spin"></span> Please wait...
                </div>
            </div>
        </template>

        <template v-if="success">
            <div class="card flat-bottom">
                <div class="head">
                    <h1>Import Complete</h1>
                </div>
            </div>
            <div class="card flat-top">
                <p>Your import has been completely successfully.</p>
            </div>
        </template>

    </importer>

@endsection
