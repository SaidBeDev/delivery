
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Journal</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Client</th>
                <th class="text-center">Ville</th>
                <th class="text-center">Prix Total (Da)</th>
                <th class="text-center" style="background-color: limegreen; color: #fff">Prix D'intérêt (Da)</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($data['list_boxes'] as $box)
                    <tr>
                        <td class="text-center text-muted font-weight-bold">{{ $box->code }}</td>
                        <td>
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    {{-- <div class="widget-content-left mr-3">
                                        <div class="widget-content-left">
                                            <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                                        </div>
                                    </div> --}}
                                    <div class="widget-content-left flex2">
                                        <div class="widget-heading"><a href="{{ route('admin.boxes.show', ['id' => $box->id]) }}">{{ $box->full_name }}</a></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{ $box->daira->name . ', ' . $box->daira->wilaya->name }}</td>
                        <td class="text-center">{{ $box->total_price }}</td>
                        <td class="text-center font-weight-bold" style="background-color: #c3ffc3; color: forestgreen;">
                            {{ (int)$box->total_price - (int)$box->price }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-block text-center card-footer">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover" style="width: 50%; margin: auto">
            <thead>
            <tr>
                <th class="text-center" style="background-color: limegreen; color: #fff">Prix D'intérêt Total (Da)</th>
                <th class="text-center" style="background-color: #c3ffc3; color: forestgreen">{{ $data['total_price'] }}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
    {{-- Scripts --}}
    {!! Html::script('node_modules/select2/dist/js/select2.min.js') !!}

    <script>
        $(document).ready(function(){
            @if(!empty(session()->has('success')))
                new Noty({
                    timeout: 5000,
                    progressBar: true,
                    type: 'success',
                    theme: 'sunset',
                    text: "{{ session('success') }}"
                }).show();
            @elseif(session()->has('error'))
                new Noty({
                        timeout: 5000,
                        progressBar: true,
                        type: 'error',
                        theme: 'sunset',
                        text: "Une erreur est survenue"
                    }).show();
            @endif
        });
    </script>
@endsection
