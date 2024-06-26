<html>
<head>
    <title>Rank Result</title>
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
    <table>
        <thead>
            <tr><th width="5%">#</th><th>Pegawai</th><th>Periode</th><th>Nilai</th><th>Predikat</th></tr>
        </thead>
        <tbody>
            <?php $index = 1;?>
            @foreach($data as $d)
                <tr><td width="5%">{{$index}}</td>
                    <td width="50%">{{$d->officerName}} : {{$d->officerPosition}} : {{$d->officerUnit}}</td>
                    <td>{{$d->periodName}}</td>
                    <td align="right"><b>{{number_format($d->rank_value/100,4,',')}}</b></td>
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
                                        <td><b>{{$mark}} : {{$initial}}</b></td>
                </tr>
                <tr><td colspan='5'>
                    @php $totalPreference =0; @endphp
                    <table class="inner_table">
                        <tr><th>Kriteria</th><th>Bobot</th><th>Tipe</th><th>Nilai</th><th>Normalisasi</th><th>Nilai Preference</th></tr>
                        @foreach($d['detail'] as $detail)
                            <tr>
                                <td>{{$detail->criteriaName}}</td>
                                <td align='right'>{{number_format($detail->criteriaWeight,4,',')}}</td>
                                <td>{{$detail->criteriaType}}</td>
                                <td align="right">{{number_format($detail->perf_value,4,',')}}</td>
                                <td align="right">{{number_format($detail->norm_value,4,',')}}</td>
                                <td align="right">{{number_format($detail->preference_value,4,',')}}</td>
                            </tr>
                            @php $totalPreference += $detail->preference_value; @endphp
                        @endforeach
                        <tr><td colspan='5'>Total Nilai Preference</td><td align="right">{{number_format($totalPreference,4,',')}}</td></tr>
                    </table>
                    </td></tr>
                    @php $index+=1;@endphp
                    @endforeach
        </tbody>
    </table>

</body>
</html>