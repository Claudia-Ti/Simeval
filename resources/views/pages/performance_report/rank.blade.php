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

            <div class="row">'
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="form" action="{{url('/performance_rank')}}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm12">
                                        <div class="form-group">
                                            <label>Periode Penilaian</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                                <select name="idPeriod" class="form-control" id="id_period">
                                                    @foreach($period as $p)

                                                    <option value="{{$p->id}}">{{$p->p_name}}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-12">
                                        <div class="form-group">
                                            <label>--</label>
                                            <div class="input-group">
                                                
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>

                                        </div>
                                       
                                    </div>
                                    @if(session('error'))
                                    <div class="col-md-7 col-lg-7 col-sm-12">
                                     <x-alert></x-alert>
                                    </div>
                                    @endif
                                    {{-- @enderror --}}
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
               
                @if(isset($data))
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{$pageName}}</h4>
                            <div class="card-header-action">
                                <a href="{{url('performance_rank_print_rekap/'.$selectedPeriod->id)}}" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> Print Rekap</a>
                                <a href="{{url('performance_rank_print/'.$selectedPeriod->id)}}" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print Detail</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <x-alert></x-alert>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-md data-table">
                                    <tr>
                                        <th>#</th>
                                        <th>Pegawai</th>
                                        <th>Periode</th>
                                        <th>Nilai</th>
                                        <th>Predikat</th>


                                    </tr>
                                    <?php $index=(($data->currentPage()-1)*$data->perPage())+1;?>


                                    @foreach($data as $d)
                                    <tr>
                                        <td style="width:50px">{{$index}}</td>
                                        <td>{{$d->officerName}} : {{$d->officerPosition}} : {{$d->officerUnit}}</td>
                                        <td>{{$d->periodName}}</td>
                                        <?php
                                            $mark = "Buruk"; $initial = "-";
                                            foreach($category as $c){
                                                if($d->rank_value>=$c->min_value){
                                                    $mark = $c->name;
                                                    $initial = $c->initial;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <td align="right"><b>{{number_format($d->rank_value/100,2,',')}}</b></td>
                                        <td><b>{{$mark}} : {{$initial}}</b></td>
                                    </tr>
                                    <tr><td colspan='5'>
                                        @php $totalPreference = 0;@endphp
                                        <table class="table table-bordered table-md data-table">
                                            <tr><th>Kriteria</th><th>Bobot</th><th>Tipe</th><th>Nilai</th><th>Normalisasi</th><th>Nilai Preference</th></tr>
                                        @foreach($d['detail'] as $detail)
                                            <tr>
                                                <td>{{$detail->criteriaName}}</td>
                                                <td align="right">{{number_format($detail->criteriaWeight,2,',')}}</td>
                                                <td>{{$detail->criteriaType}}</td>
                                                <td align="right">{{number_format($detail->perf_value,2,',')}}</td>
                                                <td align="right">{{number_format($detail->norm_value,2,',')}}</td>
                                                <td align="right">{{number_format($detail->preference_value,2,',')}}</td>
                                            </tr>
                                            @php $totalPreference +=$detail->preference_value; @endphp
                                        @endforeach
                                        <tr><td colspan='5'>Total Nilai Preference</td><td align="right">{{number_format($totalPreference,2,',')}}</td></tr>
                                        </table>
                                    </td></tr>
                                    <?php $index+=1;?>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <nav class="d-inline-block">
                                {{$data->links()}}
                                <!-- <ul class="pagination mb-0">
                        <li class="page-item disabled">
                          <a class="page-link" href="#" tabindex="-1"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1 <span class="sr-only">(current)</span></a></li>
                        <li class="page-item">
                          <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                          <a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                        </li>
                      </ul> -->
                            </nav>
                        </div>
                    </div>
                </div>
                @endif


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

<script type="text/javascript">
    $('.show_confirm').click(function (event) {
        var form = $(this).closest("form");
        event.preventDefault();
        new swal({
                title: `Are you sure you want to delete this record?`,
                text: "If you delete it, it will be gone forever.",
                icon: "warning",

                buttons: ["Cancel", "Yes!"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                    alert("Will Delete Value : " + JSONStringify(willDelete));
                } else {
                    //alert("Something");
                }
            });
    });

</script>
@endsection
