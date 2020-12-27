@php
    $box = $data['box'];
@endphp
@extends('backend.layouts.master')

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Modifier une Coulis</h5>
            {!! Form::open([
                'method' => 'PUT',
                'url' => route('admin.boxes.update', $box->id)
            ]) !!}

                @if ($errors->any())
                    <div class="form-row">
                        <div class="alert alert-danger">
                            <div class="col-xl-12">
                                <div class="col-xl-6">
                                    <div class="uk-alert uk-alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom01">Nom complet de client</label>
                        <input type="text" name="full_name" value="{{ $box->full_name }}" class="form-control" id="validationCustom01" placeholder="Nom complet" value="" required="">
                        <div class="valid-feedback">
                            C'est bon!
                        </div>
                        <div class="invalid-feedback">
                            Veuillez entrer le nom complet
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="validationCustom04">N° Telephone</label>
                        <input type="text" name="tel" value="{{ $box->tel }}" class="form-control" id="validationCustom04" placeholder="055329841" required="">
                        <div class="valid-feedback">
                            C'est bon!
                        </div>
                        <div class="invalid-feedback">
                            Entrer une valide numero de telephone.
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="validationCustom05">Adresse</label>
                        <input type="text" name="address" value="{{ $box->address }}" class="form-control" id="validationCustom05" placeholder="Adresse" required="">
                        <div class="valid-feedback">
                            C'est bon!
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-4">
                        <label for="">Service</label>
                        <select name="service_id" class="form-control selectpicker" >
                            <option name="service_id" value="" selected>Non spécifié</option>
                            @foreach ($data['list_services'] as $service)
                                <option name="service_id"  value="{{ $service->id }}"  {{ $box->service_id == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="validationCustom05">Prix de coulis (Da)</label>
                        <input type="text" name="price" value="{{ $box->price }}" class="form-control" id="validationCustom05" placeholder="5000" required="">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="validationCustom05">Note</label>
                        <input type="text" name="note" value="{{ $box->note }}" class="form-control" id="validationCustom05" placeholder="entrer une remarque">
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom03" style="display: block">Ville/Wilaya</label>
                        <select name="daira_id" class="daira-select form-control selectpicker" data-live-search="true"  data-style="btn-info" data-width="auto">
                            @foreach ($data['list_wilayas'] as $wilaya)
                                <optgroup label="{{ $wilaya->name }}">
                                    @foreach ($wilaya->dairas as $daira)
                                        <option value="{{ $daira->id }}" {{ $box->daira_id == $daira->id ? 'selected' : '' }}>{{ $daira->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Enregistrer</button>

                {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('.daira-select').change(function() {
                $(this).selectpicker('destroy');
                $(this).selectpicker('show');
                $('.daira-select').selectpicker({
                    'dropupAuto': false
                });
            });

        });
    </script>
@endsection
