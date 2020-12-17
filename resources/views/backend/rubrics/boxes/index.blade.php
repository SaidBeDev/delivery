
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Liste des coulis</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Nom complet</th>
                <th class="text-center">Ville</th>
                <th class="text-center">Prix (Da)</th>
                <th class="text-center">Affecter à</th>
                <th class="">Status</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($data['list_boxes'] as $box)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->index + 1 }}</td>
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
                        <td class="text-center">
                            <select name="assigned_user_id" class="form-control man-select">
                                @foreach ($data['list_delivrers'] as $man)
                                    <option name="assigned_user_id"  value="{{ $man->id }}" data-userId="{{ $man->id }}" data-boxId="{{ $box->id }}" {{ $box->assigned_user_id == $man->id ? 'selected' : '' }}>{{ $man->full_name }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td class="text-center">
                            <select name="box_status_id" class="form-control box-select">
                                @foreach ($data['box_status'] as $status)
                                    <option name="box_status_id"  value="{{ $status->id }}" data-bgcolor="{{ $status->background_color }}" data-color="white" data-statusId="{{ $status->id }}" data-boxId="{{ $box->id }}" {{ $box->box_status->id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        {{-- <td class="text-center">
                            <div class="badge badge-warning">Pending</div>
                        </td> --}}
                        <td class="text-center">
                            <a href="{{ route('admin.boxes.edit', ['id' => $box->id]) }}" class="btn btn-primary">Modifier</a>
                            {{-- <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModal">Supprimer</a> --}}
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

@section('styles')
    {!! Html::style('node_modules/select2/dist/css/select2.min.css') !!}

    <style>
        .select2-container--default .select2-selection--single .select2-selection__arrow,
        .select2-container .select2-selection--single {
            height: 35px
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-weight: 600;
            line-height: 35px
        }
    </style>
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

    <script>
        $(document).ready(function() {

            $('.box-select').select2({
                minimumResultsForSearch: Infinity
            });

            bgColor = $('.box-select option:selected').attr('data-bgcolor');
            color   = $('.box-select option:selected').attr('data-color');

            console.log(bgColor);
            $('.select2-container--default .select2-selection--single').css('background-color', bgColor);
            $('.select2-container--default .select2-selection--single .select2-selection__rendered').css('color', color);
            $('.select2-container--default .select2-selection--single .select2-selection__arrow b').css('border-color', color + ' transparent');

            $('.box-select').change(function() {
                bgColor = $('.box-select option:selected').attr('data-bgcolor');
                color   = $('.box-select option:selected').attr('data-color');

                $('.select2-container--default .select2-selection--single').css('background-color', bgColor);
                $('.select2-container--default .select2-selection--single .select2-selection__rendered').css('color', color);
                $('.select2-container--default .select2-selection--single .select2-selection__arrow b').css('border-color', color + ' transparent');
            });


            $('select.man-select').change(function() {
                boxId  = $('select.man-select option:selected').attr('data-boxId');
                userId = $('select.man-select option:selected').attr('data-userId');

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.assignBox', ['id' => 'boxId', 'userId' => 'userID']) }}".replace('boxId', boxId).replace('userID', userId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
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

            /**
             * Change box status
             */
            $('select.box-select').change(function() {
                boxId  = $('select.box-select option:selected').attr('data-boxId');
                statusId = $('select.box-select option:selected').attr('data-statusId');

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.changeStatus', ['id' => 'boxId', 'userId' => 'statusID']) }}".replace('boxId', boxId).replace('statusID', statusId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
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
            }); /* end */

        });
    </script>
@endsection

{{-- Modals --}}
{{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Voulez-vous vraiment supprimer ce compte?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <a href="{{ route('admin.users.destroy', ['id' => $box->id]) }}" class="btn btn-danger btn-sm">Supprimer</a>
            </div>
        </div>
    </div>
</div>
 --}}
