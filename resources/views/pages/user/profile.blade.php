@extends('layouts.layout')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{$pageName}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#"></a></div>
                <div class="breadcrumb-item"><a href="#">{{$pageName}}</a></div>
            </div>
        </div>

        <div class="section-body">
            <!-- <h2 class="section-title">Table</h2>
            <p class="section-lead">Example of some Bootstrap table components.</p> -->
            <x-alert></x-alert>   
            <div class="row">
                
                <div class="col-12 col-md-6 col-lg-6">
                    <form method="post" action="{{route('user.update.profile')}}">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h4>{{$pageName}}</h4>
                               
                            </div>
                            <div class="card-body">
                                
                                <div class="form-group">
                                    <label>Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-envelope"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="email" id="name" value="{{$user->email}}" readonly class="form-control phone-number">
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
                                        <input type="text" name="name" id="name" value="{{$user->name}}" class="form-control phone-number">
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
                                        <input type="text" name="level" readonly value="{{$user->level}}" class="form-control"/>

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
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card card-warning">
                        <div class="card-header" style="background-image:linear-gradient(to bottom right,black 40%,orange)">
                            <h4 style="color:white">Ubah Kata Sandi</h4>
                        </div>
                        <form class="form" method="POST" action="{{route('user.update.password')}}">
                            @csrf
                        <div class="card-body">
                           
                                <div class="form-group">
                                    <label>Kata Sandi Lama</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <input type="password" name="old_pwd" id="old_pwd"  class="form-control phone-number">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Kata Sandi Baru</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <input type="password" name="new_pwd" id="new_pwd" class="form-control phone-number">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Kata Sandi Baru</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-lock"></i>
                                            </div>
                                        </div>
                                        <input type="password" name="new_pwd_confirmation"  class="form-control"/>

                                    </div>

                                </div>
                           
                        </div>
                        <div class="card-footer text-right">
                            <nav class="d-inline-block">
                                <button type="submit" class="btn btn-danger">Ubah Kata Sandi</button>
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
