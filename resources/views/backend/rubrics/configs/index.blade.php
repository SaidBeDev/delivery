
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Liste des Paramètres</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th>Config</th>
                <th class="text-center">Prix (Da)</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($data['list_configs'] as $config)
                    <tr>
                        <td>
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left flex2">
                                        <div class="widget-heading">{{ trans('frontend.' . $config->name) }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <input type="text" name="price" value="{{ $config->content }}" class="form-control" style="width: 90px; display: inline-block" />
                            <a href="#" class="btn btn-success btn-sm price" data-configId={{ $config->id }}><i class="fa fa-check"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-block text-center card-footer">

    </div>
</div>

@endsection

@section('scripts')

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

    <script>
        $(document).ready(function() {

            /* Change Price */
            $('a.price').click(function() {
                elem = $(this).siblings('.form-control');
                configId = $(this).attr('data-configId');

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.configs.update', ['id' => 'wilayaID']) }}".replace('wilayaID', configId),
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        content:   elem.val()
                    }
                }).done(function(response) {
                    if (response.success) {
                        new Noty({
                            timeout: 5000,
                            progressBar: true,
                            type: 'success',
                            theme: 'sunset',
                            text: response.message
                        }).show();
                    }
                }).fail(function(response) {
                    new Noty({
                        timeout: 5000,
                        progressBar: true,
                        type: 'error',
                        theme: 'sunset',
                        text: response.message
                    }).show();
                });
            });

        });
    </script>
@endsection
