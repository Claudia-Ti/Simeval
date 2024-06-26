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
                    <form method="post" action="{{route('criteria.update',$data->id)}}">
                        @csrf
                        @method('put')
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
                                    <label>Periode</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <select name="id_period" class="form-control">
                                            @foreach($period as $p)
                                            
                                            
                                            <option @if($p->id==$data->id_period) selected @endif value="{{$p->id}}">{{$p->p_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Pegawai</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <select name="id_officer" class="form-control">
                                            @foreach($officer as $p)
                                            
                                            
                                            <option @if($p->id==$data->id_officer) selected @endif value="{{$p->id}}">{{$p->o_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Nama Kriteria</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-cube"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="c_name" id="name" value={{$data->c_name}} class="form-control phone-number">
                                    </div>

                                </div>
                                <div class="form-group">
                                  <label>Tipe</label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <div class="input-group-text">
                                              <i class="fa fa-calendar"></i>
                                          </div>
                                      </div>
                                      <select name="c_type" class="form-control">
                                          <option >Pili...</option>
                                          <option @if($data->c_type=='Benefit') selected @endif  value="Benefit">Benefit</option>
                                          <option @if($data->c_type=='Cost') selected @endif value="Cost">Cost</option>
                                      </select>

                                  </div>

                              </div>
                                <div class="form-group">
                                    <label>Bobot (Weight)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-balance-scale"></i>
                                            </div>
                                        </div>
                                        <input type="number" name="weight" id="weight" value="{{$data->weight}}"
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
