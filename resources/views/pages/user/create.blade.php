@extends('layouts.layout')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{$pageName}} Table</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#"></a></div>
                <div class="breadcrumb-item"><a href="#"></a></div>
                <div class="breadcrumb-item"></div>
            </div>
        </div>

        <div class="section-body">
            <!-- <h2 class="section-title">Table</h2>
            <p class="section-lead">Example of some Bootstrap table components.</p> -->

            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <form method="post" action="{{route('user.store')}}">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4>{{$pageName}}</h4>
                                <div class="card-header-action">
                                    <a href="javascript:history.back()" class="btn btn-info">Kembali</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <x-alert></x-alert>
                                <div class="form-group">
                                    <label>Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-envelope"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="email" id="name" class="form-control phone-number">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Nama Pegawai</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="name" id="name" class="form-control phone-number">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Level</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <select name="level" class="form-control">
                                            <option>Pilih...</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Kasat">Kasat</option>
                                            <option value="Unsur Penilai">Unsur Penilai / Wakapolres</option>
                                        </select>

                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Kata Sandi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <input type="password" name="password" id="name"
                                            class="form-control phone-number">
                                    </div>

                                </div>


                            </div>
                            <div class="card-footer text-right">
                                <nav class="d-inline-block">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </nav>
                            </div>

                        </div>
                    </form>
                </div>



            </div>
    </section>
</div>
<script>
    document.onreadystatechange = () => {
        if (document.readyState === 'complete') {
            $('#alert').fadeOut(5000);
        }
    }

</script>
@endsection
