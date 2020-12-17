
@extends('backend.layouts.master')

@section('content')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">Nouveau utilisateur</h5>
            {!! Form::open([
                'method' => 'POST',
                'url' => route('admin.users.store')
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
                    <div class="col-md-3 mb-3">
                        <label for="">Type de compte</label>
                        <select name="profile_type_id" class="profile-select form-control selectpicker"  data-style="btn-primary">
                            @foreach ($data['profile_types'] as $type)
                                <option name="profile_type_id"  value="{{ $type->id }}" data-slug="{{ $type->name }}"  {{ $loop->index == 1 ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom01">Nom complet</label>
                        <input type="text" name="full_name" class="form-control" id="validationCustom01" placeholder="Nom complet" value="" required="">
                        <div class="valid-feedback">
                            C'est bon!
                        </div>
                        <div class="invalid-feedback">
                            Veuillez entrer le nom complet
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustomUsername">Nom d'utilisateur</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                            </div>
                            <input type="text" name="username" class="form-control" id="validationCustomUsername" placeholder="Username" aria-describedby="inputGroupPrepend" required="">
                            <div class="invalid-feedback">
                                Veuillez choisir un nom d'utilisateur unique et valide.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label for="validationCustom02">Mot de passe</label>
                        <input type="text" name="password" class="form-control" id="validationCustom02" placeholder="Mot de passe" required="">
                        <div class="valid-feedback">
                            C'est bon!
                        </div>
                        <div class="invalid-feedback">
                            Le mot de passe n'est pas accepté
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="validationCustom04">N° Telephone</label>
                        <input type="text" name="tel" class="form-control" id="validationCustom04" placeholder="055329841" required="">
                        <div class="valid-feedback">
                            C'est bon!
                        </div>
                        <div class="invalid-feedback">
                            Entrer une valide numero de telephone.
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom05">Adresse</label>
                        <input type="text" name="address" class="form-control" id="validationCustom05" placeholder="Adresse" required="">
                        <div class="valid-feedback">
                            C'est bon!
                        </div>
                        <div class="invalid-feedback">
                            Please provide a valid zip.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="validationCustom03" style="display: block">Ville/Wilaya</label>
                        <select name="daira_id" class="daira-select form-control selectpicker" data-live-search="true"  data-style="btn-info" data-width="auto">
                            @foreach ($data['list_wilayas'] as $wilaya)
                                <optgroup label="{{ $wilaya->name }}">
                                    @foreach ($wilaya->dairas as $daira)
                                        <option value="{{ $daira->id }}" {{ $loop->index == 1 ? 'selected' : '' }}>{{ $daira->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">Service</label>
                        <select name="service_id" class="form-control selectpicker" >
                            @foreach ($data['list_services'] as $service)
                                <option name="service_id"  value="{{ $service->id }}"  {{ $loop->index == 1 ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3" style="display: none">
                        <label for="">Type de vehicule</label>
                        <select name="vehicle_type_id" class="vehicle-select form-control selectpicker" >
                            @foreach ($data['vehicle_types'] as $type)
                                <option name="vehicle_type_id"   {{ $loop->index == 1 ? 'selected' : '' }} value="{{ $type->id }}">{{ $type->name }}</option>
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
            $('select.profile-select').change(function() {
                if($('select.profile-select option:selected').text() == "deliveryMan") {
                    $('select.vehicle-select').closest('.col-md-3').show();
                }else {
                    $('select.vehicle-select').closest('.col-md-3').hide();
                }
            });

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
