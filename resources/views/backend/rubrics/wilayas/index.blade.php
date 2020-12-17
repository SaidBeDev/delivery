
@extends('backend.layouts.master')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Liste des Wilayas</div>
    <div class="table-responsive">
        <table class="align-middle mb-0 table table-borderless table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Wilaya</th>
                <th class="text-center">Prix (Da)</th>
                <th class="text-center">Livraison</th>
                <th class="text-center">Autres Services</th>
                <th class=""></th>
            </tr>
            </thead>
            <tbody>
                <select name="service_id" class="form-control default-services" style="display: none">
                    @foreach ($data['list_services'] as $service)
                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                    @endforeach
                </select>
                @foreach ($data['list_wilayas'] as $wilaya)
                    <tr>
                        <td class="text-center text-muted">{{ $wilaya->code }}</td>
                        <td>
                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    {{-- <div class="widget-content-left mr-3">
                                        <div class="widget-content-left">
                                            <img width="40" class="rounded-circle" src="assets/images/avatars/4.jpg" alt="">
                                        </div>
                                    </div> --}}
                                    <div class="widget-content-left flex2">
                                        <div class="widget-heading">{{ $wilaya->name }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <input type="text" name="price" value="{{ $wilaya->price }}" data-wilayaId="{{ $wilaya->id }}" class="form-control" />
                            <a href="#" class="btn btn-success btn-sm price"><i class="fa fa-check"></i></a>
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-secondary status" data-wilayaId="{{ $wilaya->id }}" data-isActive={{ $wilaya->availability }}>{{ $wilaya->availability ? 'Désactiver' : 'Activer' }}</a>
                            <small style="font-size: 20px">
                                <i class="fa fa-dot-circle" style="color: {{ $wilaya->availability ? '#28a745' : 'red' }}"></i>
                            </small>
                        </td>
                        <td class="text-center badges" data-wilayaId="{{ $wilaya->id }}">
                            @foreach ($wilaya->services as $service)
                                <span class="badge badge-dark">{{ $service->name }}
                                    <a class="delete" data-serviceId={{ $service->id }}>
                                        <i class="fa fa-times-circle"></i>
                                    </a>
                                </span>
                            @endforeach
                        </td>
                        <td class="text-center service">
                            @php
                                $listServices = [];
                               foreach ($wilaya->services as $service) {
                                   array_push($listServices, $service->id);
                               }
                            @endphp
                            <a href="#" class="btn btn-primary showDiag" data-toggle="modal" data-target="#addService" title="Ajouter" data-wilayaId="{{ $wilaya->id }}"
                                data-listServices="{{ implode(',', $listServices) }}"
                                data-placement="top">
                                    <i class="fa fa-plus"></i>
                            </a>
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


{{-- Modals --}}
<div class="modal fade" id="addService" tabindex="-1" role="dialog" data-action="" data-wilayaId="" data-servicesId="" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter une service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <select name="service_id" class="form-control service-select">
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <a href="#" class="btn btn-primary save">Ajouter</a>
            </div>
        </div>
    </div>
</div>



@section('styles')
    {!! Html::style('node_modules/select2/dist/css/select2.min.css') !!}

    <style>
        input[name='price'] {
            width: 60px;
            display: inline-block;
        }

        .badges span{
            padding: 5px 10px
        }
        .badges span a{
            cursor: pointer;
        }
        .badges span a:hover{
            color: yellow !important
        }
    </style>
@endsection

@section('scripts')
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
            $('.service-select').select2({
                minimumResultsForSearch: Infinity
            });
            $('a.showDiag').click(function() {
                wilayaId     = $(this).attr('data-wilayaId');
                existServices = $('.default-services option');
                listServices = $(this).attr('data-listServices');
                oldServices  = listServices != '' ? listServices.split(',') : [];
                newService   = '';
                diff         = [];
                removedOptions = [];
                newOptions = [];

                // Actions
                $('#addService').attr('data-wilayaId', wilayaId);
                $('#addService[data-wilayaId="' + wilayaId + '"] select option').map(function(key, option) {
                    option.remove();
                });
                $('#addService a.save').removeClass('disabled');

                existServices.map(function(key, option) {
                    if(jQuery.inArray(option.value, oldServices) === -1) {
                        newOptions.push(option);
                    }
                });

                newOptions.map(function(obj) {
                    option = document.createElement('option');
                    option.value = obj.value;
                    option.innerText = obj.innerText;

                    $('#addService[data-wilayaId="' + wilayaId + '"] select').append(option);
                });

                //


                // Save action begin
                $('#addService a.save').one('click', function() {
                    parent     = $(this).closest('#addService');
                    newService = $('#addService[data-wilayaId="' + wilayaId + '"] select option:selected').val();
                    newServiceName = $('#addService[data-wilayaId="' + wilayaId + '"] select option:selected').text();

                    //
                    $(this).addClass('disabled');

                    // CSRF TOKEN Setup
                    jQuery.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

                    // Ajax requests
                    $.ajax({
                        url: "{{ route('admin.addService', ['id' => 'wilayaID']) }}".replace('wilayaID', wilayaId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            service_id: newService
                        }
                    }).done(function(response) {
                        if (response.success) {
                            newListServices = oldServices.push(newService).toString();
                            $('a.showDiag[data-wilayaId="'+ wilayaId +'"]').attr('data-listServices', newListServices);
                            $('#addService').modal('hide');

                            var span = $('<span class="badge badge-dark"></span>');
                            var link = $('<a class="delete" data-serviceId="' + newService + '"><i class="fa fa-times-circle"></i></a>');

                            span.text(newServiceName + ' ');
                            span.append(link);

                            $('td.badges[data-wilayaId="'+ wilayaId +'"]').append(span);

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

            $(document).on('click', 'a.delete', function() {
                status = window.confirm('Voulez vous vraiment supprimer cette service?');

                if(status === 'true') {
                    parent    = $(this).closest('td.badges');
                    elem      = $(this).closest('span.badge');
                    wilayaId  = parent.attr('data-wilayaId');
                    serviceId = $(this).attr('data-serviceId');

                    listServices = $('a.showDiag[data-wilayaId="'+ wilayaId +'"]').attr('data-listServices');
                    oldServices  = listServices != '' ? listServices.split(',') : [];

                    // CSRF TOKEN Setup
                    jQuery.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });

                    // Ajax requests
                    $.ajax({
                        url: "{{ route('admin.deleteService', ['id' => 'wilayaID']) }}".replace('wilayaID', wilayaId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            service_id: serviceId
                        }
                    }).done(function(response) {
                        if (response.success) {

                            newListServices = $.grep(oldServices, function(value) {
                                return value != serviceId;
                            }).toString();
                            console.log(newListServices);
                            $('a.showDiag[data-wilayaId="'+ wilayaId +'"]').attr('data-listServices', newListServices);

                            elem.remove();

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
            })

            /* Toggle availability */
            $('a.status').click(function() {
                elem = $(this);
                icon = $(this).siblings('small').children('i');
                wilayaId = elem.attr('data-wilayaId');
                isActive = elem.attr('data-isActive');
                availability = isActive == 1 ? 0 : 1;
                newColor = availability ? '#28a745' : 'red';
                newText  = availability ? 'Désactiver' : 'Activer';

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.toggleAvailablity', ['id' => 'wilayaID']) }}".replace('wilayaID', wilayaId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        availability: availability
                    }
                }).done(function(response) {
                    if (response.success) {
                        elem.attr('data-isActive', availability);
                        elem.text(newText);
                        icon.css('color', newColor);

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

            /* Change Price */
            $('a.price').click(function() {
                elem = $(this).siblings('.form-control');
                wilayaId = elem.attr('data-wilayaId');

                // CSRF TOKEN Setup
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                // Ajax requests
                $.ajax({
                    url: "{{ route('admin.changePrice', ['id' => 'wilayaID']) }}".replace('wilayaID', wilayaId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        price:   elem.val()
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
