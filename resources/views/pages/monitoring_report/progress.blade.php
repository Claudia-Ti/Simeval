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
                            <form class="form" action="{{url('/monitoring_progress/progress')}}" method="POST">
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
                                <a href="{{url('monitoring_progress_rekap_print/'.$selectedPeriod->id)}}" target="_blank" class="btn btn-success"><i class="fa fa-print"></i> Print Rekap</a>
                                <a href="{{url('monitoring_progress_print/'.$selectedPeriod->id)}}" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <x-alert></x-alert>
                            @php $lastId = ""; @endphp
                            <div class="table-responsive">
                            
                            <?php 
                            $index=1;//(($data->currentPage()-1)*$data->perPage())+1;
                            $max = count($data);
                            $counter = 0;
                            $totalPencapaian =0;
                            $totalBobot = 0;
                            foreach($data as $d){
                           
                                if($lastId!=$d->id_officer){
                                    if($lastId!=""){
                                        $mark = "Buruk";
                                    $initial = "-";
                                    foreach($category as $c){
                                        $value = ((($totalPencapaian/$totalBobot)*1.1)*100);
                                        if($value>=$c->min_value){
                                            $mark = $c->name;$initial = $c->initial;
                                            break;
                                        }
                                    }
                                    echo "<tr><td colspan='5'>".$mark." : ".$initial."</td><th>".$totalBobot."</th><th>".$totalPencapaian."</th><th>
                                            ".number_format(((($totalPencapaian/$totalBobot) * 1.1)*100),2)." %</th></tr>";
                                        echo "</table>";
                                        $totalBobot = 0;
                                        $totalPencapaian = 0;
                                    }
                                    $lastId = $d->id_officer;
                                    echo '<table class="table table-bordered table-striped table-md data-table">
                                        <tr>
                                            <th>#</th>
                                            <th>Pegawai</th>
                                            <th>Periode</th>
                                            <th>Kriteria</th>
                                            <th>Nilai Monitoring</th>
                                            <th>Bobot Kriteria</th>
                                            <th>Nilai Pencapaian</th>
                                            <th>Nilai %</th>
                                        </tr>';
                                }
                                
                                    echo "<tr>
                                            <td style='width:50px'>".$index."</td>
                                            <td>".$d->officerName." : ".$d->officerPosition." : ".$d->officerUnit."</td>
                                            <td>".$d->periodName."</td>
                                            <td>".$d->criteriaName."</td>
                                            <td>".$d->mon_value."</td>
                                            <td>".$d->criteriaWeight."</td>
                                            <td>".$d->preference_value."</td>
                                            <td align='right'>".number_format((($d->preference_value/$d->criteriaWeight) *1.1)*100,2)." %</td>
                                        </tr>";
                                        $totalPencapaian +=$d->preference_value;
                                        $totalBobot += $d->criteriaWeight;
                                $counter+=1;
                                if($counter==$max){
                                    $mark = "Buruk";
                                    $initial = "-";
                                    foreach($category as $c){
                                        $value = ((($totalPencapaian/$totalBobot)*1.1)*100);
                                        if($value>=$c->min_value){
                                            $mark = $c->name;$initial = $c->initial;
                                            break;
                                        }
                                    }
                                    echo "<tr><td colspan='5'>".$mark." : ".$initial."</td><th>".$totalBobot."</th><th>".$totalPencapaian."</th><th>
                                            ".number_format(((($totalPencapaian/$totalBobot) * 1.1)*100),2)." %</th></tr>";
                                    echo "</table>";
                                }
                                
                                $index+=1;
                            } ?>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            {{-- <nav class="d-inline-block">
                                {{$data->links()}}
                                
                            </nav> --}}
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
                    alert("Something");
                }
            });
    });

</script>
@endsection
