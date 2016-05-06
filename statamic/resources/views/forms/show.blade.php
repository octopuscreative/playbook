@extends('layout')

@section('content')

    <div class="card flat-bottom">
        <div class="head">
            <h1>{{ $form->title() }}</h1>

            @can('super')
                <a href="{{ route('form.edit', ['form' => $form->name()]) }}" class="btn">{{ t('configure') }}</a>
            @endcan

            <div class="btn-group">
                <a href="{{ route('form.export', ['type' => 'csv', 'form' => $form->name()]) }}?download=true"
                   type="button" class="btn btn-default">{{ t('export') }}</a>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">{{ translate('cp.toggle_dropdown') }}</span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('form.export', ['type' => 'csv', 'form' => $form->name()]) }}?download=true">{{ t('export_csv') }}</a></li>
                    <li><a href="{{ route('form.export', ['type' => 'json', 'form' => $form->name()]) }}?download=true">{{ t('export_json') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card flat-top">
        @if (! empty($form->metrics()))
        <div class="metrics">
            @foreach($form->metrics() as $metric)
            <div class="metric simple">
                <div class="count">
                    <small>{{ $metric->label() }}</small>
                    <h2>{{ $metric->result() }}</h2>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if (count($form->submissions()) > 0)
        <table class="control you-can-select-this">
            <thead>
                <tr>
                    <th>{{ t('date') }}</th>
                    @foreach ($form->columns() as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($form->submissions() as $submission)
                    <tr>
                        <td><a href="{{ route('form.submission.show', [$form->name(), $submission->id()]) }}">{{ $submission->formattedDate() }}</a></td>
                        @foreach($submission->columns() as $name => $label)
                            <td>
                                @if(! is_array($submission->get($name)))
                                    {{ $submission->get($name) }}
                                @else
                                    @foreach($submission->get($name) as $key => $value)
                                        {{ $value }}<br>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                        <td class="column-actions">
                            <div class="btn-group">
                                <button type="button" class="btn-more dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon icon-dots-three-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('form.submission.show', [$form->name(), $submission->id()]) }}">Show</a></li>
                                    <li class="warning"><a href="{{ route('form.submission.delete', [$form->name(), $submission->id()]) }}">Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-results">
            <span class="icon icon-documents"></span>
            <h2>{{ $form->title() }}</h2>
            <h3>{{ trans('cp.empty_responses') }}</h3>
        </div>
        @endif
    </div>

@endsection
