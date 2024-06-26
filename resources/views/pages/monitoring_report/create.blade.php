@extends('layouts.layout')
@section('content')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{$pageName}}</h1>
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
                    <form method="post" action="{{route('monitoring_report.store')}}">
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
                                    <label>Pegawai</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <select name="id_officer" class="form-control">
                                            @foreach($officer as $o)

                                            <option value="{{$o->id}}">{{$o->o_name}} : {{$o->position}} : {{$o->unit}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Periode</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <select name="id_period" class="form-control" id="id_period" onchange="javascript:getCriteria(this.value)">
                                            @foreach($period as $p)

                                            <option value="{{$p->id}}">{{$p->p_name}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Kriteria</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                        <select name="id_mon_crit" class="form-control" id="id_criteria">
                                            
                                        </select>

                                    </div>

                                </div>

                                <div class="form-group">
                                    <label>Nilai Performance</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-cubes"></i>
                                            </div>
                                        </div>
                                        <input type="number" name="mon_value" id="name"
                                            class="form-control phone-number">
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fa fa-file"></i>
                                            </div>
                                        </div>
                                        <input type="text" name="notes" id="name" class="form-control phone-number">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>

    function getCriteria(id) {
        $.get("{{url('api/monitoring_criteria_by_period')}}/" + id, function (data) {
            // alert(id);
            // alert(JSON.stringify(data));
            createOptions('id_criteria', data['data']);
            
        });
    }
    function createOptions(id, data) {
        var selectElement = document.getElementById(id);
        while (selectElement.options.length) {
            selectElement.remove(0);
        }
        for (let i = 0; i < data.length; i++) {
            var tmp = new Option(data[i]["mc_name"], data[i]["id"]);
            selectElement.options.add(tmp);
        }
    }
    function selectDefault(id,selected){
        const selectElement = document.getElementById(id);
        const options = Array.from(selectElement.options);
        const optionToSelect = options.find(item=>item.value===selected);
        optionToSelect.selected = true;
        return optionToSelect.value;
    }
    $(document).ready(function(){
        // alert(document.getElementById('id_period').options[0].selected);
        getCriteria($('#id_period').val());
    });
</script>
@endsection
