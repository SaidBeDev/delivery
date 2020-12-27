
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Liste des coulis</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center"></th>
                <th class="text-center">#</th>
                <th>Nom complet</th>
                <th class="text-center">Ville</th>
                <th class="text-center">Prix (Da)</th>
                <th class="">Status</th>
                @if (in_array(Auth::user()->profile_type->name, ["superAdmin", "distributor"]))
                    <th class="text-center">Actions</th>
                @endif
            </tr>
            </thead>
            <tbody>
                @foreach ($data['list_boxes'] as $box)
                    <tr>
                        <td class="text-center text-muted font-weight-bold"><input type="checkbox" name="print[]" class="checkbox" value="{{ $box->id }}"/></td>
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

                        <td class="text-center" data-boxId="{{ $box->id }}">
                            <div class="badge" style="background-color: grey; color: #fff; padding: 10px">Non recevé</div>
                        </td>
                        {{-- <td class="text-center">
                            <div class="badge badge-warning">Pending</div>
                        </td> --}}
                        <td class="text-center">
                            <a href="{{ route('admin.boxes.edit', ['id' => $box->id]) }}" class="btn btn-success mb-md-1"><i class="fa fa-pen"></i></a>
                            <a href="#" class="btn btn-danger delete" data-boxId="{{ $box->id }}"><i class="fa fa-trash-alt"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-block text-center card-footer">
        <a href="" class="btn btn-success print">Imprimer</a>
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

    @if (in_array(Auth::user()->profile_type->name, ["superAdmin", "deliveryMan"]))
        <script>
            $(document).ready(function() {

                $('.box-select').select2({
                    minimumResultsForSearch: Infinity
                });

                /* Delete a box confirmation */
                $('a.delete').click(function() {
                    elem = $(this);
                    var stat = window.confirm("Voulez vos vraiment supprimer ce Coulis?");

                    if (stat) {
                        boxId = $(this).attr('data-boxId');

                        // CSRF TOKEN Setup
                        jQuery.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });

                        // Ajax requests
                        $.ajax({
                            url: "{{ route('admin.boxes.destroy', ['id' => 'codeX']) }}".replace('codeX', boxId),
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            }
                        }).done(function(response) {
                            if (response.success) {
                                elem.closest('tr').remove();

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
                    }
                });

                /* Print multiple */
                $('.checkbox').change(function() {
                    if($('.checkbox:checked').length > 4) {

                        $('.checkbox:not(:checked)').each(function(key) {
                            $(this).attr('disabled', 'disabled').css('cursor', 'not-allowed');
                        })
                    }else {
                        $('.checkbox:not(:checked)').each(function(key) {
                            $(this).attr('disabled', false).css('cursor', 'pointer');
                        })
                    }
                });

                $(document).on('click', 'a.print', function() {
                    arr = [];
                    $('.checkbox:checked').each(function(key) {
                        arr.push($(this).val());
                    });

                    if(arr != []) {
                        window.location.href = "{{ route('admin.showAll', ['id' => 'listId']) }}".replace('listId', arr.toString());
                    }
                })

                $('a.print').click(function(e) {
                    e.preventDefault();

                    var selectedBoxes = [];
                    $('.checkbox:checked').each(function(i) {
                        selectedBoxes.push($(this).val());
                    });

                })

                $('.box-select').map(function() {
                    boxId = $(this).closest('td').attr('data-boxId');


                    bgColor = $('td[data-boxId="' + boxId + '"] .box-select option:selected').attr('data-bgcolor');
                    color   = $('td[data-boxId="' + boxId + '"] .box-select option:selected').attr('data-color');

                    $('td[data-boxId="' + boxId + '"] .select2-container--default .select2-selection--single').css('background-color', bgColor);
                    $('td[data-boxId="' + boxId + '"] .select2-container--default .select2-selection--single .select2-selection__rendered').css('color', color);
                    $('td[data-boxId="' + boxId + '"] .select2-container--default .select2-selection--single .select2-selection__arrow b').css('border-color', color + ' transparent');

                });



                $('.box-select').change(function() {
                    boxId   = $(this).closest('td').attr('data-boxId');
                    bgColor = $('td[data-boxId="' + boxId + '"] .box-select option:selected').attr('data-bgcolor');
                    color   = $('td[data-boxId="' + boxId + '"] .box-select option:selected').attr('data-color');

                    $('td[data-boxId="' + boxId + '"] .select2-container--default .select2-selection--single').css('background-color', bgColor);
                    $('td[data-boxId="' + boxId + '"] .select2-container--default .select2-selection--single .select2-selection__rendered').css('color', color);
                    $('td[data-boxId="' + boxId + '"] .select2-container--default .select2-selection--single .select2-selection__arrow b').css('border-color', color + ' transparent');
                });


                $('select.man-select').change(function() {
                    boxId  = $(this).closest('td').attr('data-boxId');
                    userId = $('td[data-boxId="' + boxId + '"] select.man-select option:selected').attr('data-userId');

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
                    boxId    = $(this).closest('td').attr('data-boxId');
                    statusId = $('td[data-boxId="' + boxId + '"] select.box-select option:selected').attr('data-statusId');

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
    @endif
@endsection
