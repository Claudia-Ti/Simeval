<html>
    <head>
        <title>Monitoring Report</title>
        <style>
            table{
                width:100%;
                border-collapse: collapse;
                border:1px solid black;
                padding: 10px;
            }
            td,tr,th{
                border-collapse: collapse;
                border: 1px solid black;
                padding: 10px;
            }
            /* .inner_table{
                margin: 10px;
            } */
        </style>
    </head>
    <body>
        @php 
            $index=1;
            $max = count($data);
            $counter = 0;
            $totalPencapaian = 0;
            $totalBobot = 0;
            $lastId = "";
        @endphp
        @foreach($data as $d)
            @if($lastId!=$d->id_officer)
                @if($lastId!="")
                    <tr><td colspan='5'>
                        @php 
                            $mark = "Buruk";
                            $initial = "-";
                        @endphp
                        @foreach($category as $c)
                            @php $value = ((($totalPencapaian/$totalBobot)*1.1)*100);@endphp
                            @if($value>=$c->min_value)
                                @php $mark = $c->name;$initial = $c->initial;break; @endphp
                            @endif
                        @endforeach
                        {{$mark}} : {{$initial}}
                        </td><th>{{number_format($totalBobot,2,',')}}</th><th>{{number_format($totalPencapaian,2,',')}}</th>
                        <th>{{number_format(((($totalPencapaian/$totalBobot)*1.1)*100),0,',')}} %</th></tr></table>
                    @php $totalBobot = 0;$totalPencapaian = 0;@endphp
                @endif
                @php $lastId = $d->id_officer;@endphp
                <table><tr>
                        <th>#</th>
                        <th>Pegawai</th>
                        <th>Periode</th>
                        <th>Kriteria</th>
                        <th>Nilai Monitoring</th>
                        <th>Bobot Kriteria</th>
                        <th>Nilai Pencapaian</th>
                        <th>Nilai %</th>
                </tr>
            @endif
            <tr>
                <td style='width:50px'>{{$index}}</td>
                <td>{{$d->officerName}} : {{$d->officerPosition}} : {{$d->officerUnit}}</td>
                <td>{{$d->periodName}}</td>
                <td>{{$d->criteriaName}}</td>
                <td>{{number_format($d->mon_value,2,',')}}</td>
                <td>{{number_format($d->criteriaWeight,2,',')}}</td>
                <td>{{number_format($d->preference_value,2,',')}}</td>
                <td align='right'>{{number_format(((($d->preference_value/$d->criteriaWeight)*1.1)*100),0,',')}} %</td>
            </tr>
            @php $totalPencapaian += $d->preference_value;
            $totalBobot += $d->criteriaWeight;
            $counter+=1;
            @endphp
            @if($counter==$max)
                <tr><td colspan='5'>
                    @php 
                            $mark = "Buruk";
                            $initial = "-";
                        @endphp
                        @foreach($category as $c)
                            @php $value = ((($totalPencapaian/$totalBobot)*1.1)*100);@endphp
                            @if($value>=$c->min_value)
                                @php $mark = $c->name;$initial = $c->initial;break; @endphp
                            @endif
                        @endforeach
                        {{$mark}} : {{$initial}}
                    </td><th>{{number_format($totalBobot,2,',')}}</th><th>{{number_format($totalPencapaian,2,',')}}</th>
                    <th>{{number_format(((($totalPencapaian/$totalBobot)*1.1)*100),0,',')}} %</th></tr></table>
            @endif
            @php $index+=1;@endphp
        @endforeach
    </body>
</html>
