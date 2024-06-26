<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Performance Report</title>
    <style>
        #myTable{
            width:100%;
            border-collapse: collapse;
            border:1px solid black;
            padding: 10px;
        }
        #myTable td,#myTable tr,#myTable th{
            border-collapse: collapse;
            border: 1px solid black;
            padding: 10px;
        }
        div.breakNow{page-break-inside: avoid;page-break-after: always;}
        /* .inner_table{
            margin: 10px;
        } */
    </style>
</head>
<body>
    <table style="width:100%">
        <thead>
            <tr style="border-bottom:1px solid black"><td style="border-bottom:4px solid black;text-align: center;">
                <h2 >KEPOLISIAN NEGARA REPUBLIK INDONESIA</h2>
      
                <br>
                <h3 style="margin-top:-20px;">KEPOLISIAN RESOR MINAHASA</h3></td></tr>
           
            <tr><td style="text-align: center"><h4>FORMULIR HASIL PENILAIAN KINERJA {{$pageName}}</h4></td></tr>
        </thead>
        <tbody>
            <tr><td>
                <table style="width:100%" id="myTable">
                    <tr><th width="5%">No.</th><th>Nama Pegawai</th><th>Nilai</th><th>Predikat</th></tr>
                    
                    @php $index =0;@endphp
                        @foreach($data as $d)
                            <tr><td>{{$index+1}}</td><td>{{$d->officerName}} : {{$d->officerPosition}} : {{$d->officerUnit}}</td>
                            <td><b>{{number_format($d->rank_value/100,2,',')}}</b></td>
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
                            <td><b>{{$mark}} : {{$initial}}</b></td></tr>
                            <tr>
                                <td colspan='4' style="height: 100px;overflow:hidden;font-size:11px">
                                    <?php 
                                        $notes = [];
                                        foreach($d['detail'] as $detail){
                                            if($detail->notes!="" && $detail->notes!='-'){
                                                $tmp = [
                                                    "notes"=>$detail->notes,
                                                    "kriteria"=>$detail->criteriaName
                                                ];
                                                array_push($notes,$tmp);
                                            }
                                        }
                                    ?>
                                    @if(count($notes)>1)
                                    <b>Catatan untuk {{$d->officerName}}</b>
                                        <ul>
                                        @foreach($notes as $n)
                                            <li>Kriteria <b>{{$n["kriteria"]}}</b> : {{$n["notes"]}}</li>
                                        @endforeach
                                        </ul>
                                    @elseif(count($notes)==1)
                                        Kriteria : <b>{{$notes[0]["kriteria"]}}, Catatan : {{$notes[0]["notes"]}}</b>
                                    @else
                                        <b>Tidak ada Catatan</b>
                                    @endif
                                </td>
                            </tr>
                            @php $index+=1;@endphp
                            @if($index%3==0)
                        </table>
                        <div class='breakNow'></div>
                        <table style="width:100%" id="myTable">
                            @endif
                        @endforeach
                   
                </table>
            </td></tr>
            
        </tbody>
        <tfoot>
            
            <tr><td style="width:100%">
                <table style="float:right;margin-top:25px">
                    <tr><td>Minahasa, {{date('d F Y',strtotime('now'))}}</td></tr>
                    <tr><td><b>Pejabat Penilai</b></td></tr>
                    <tr><td style="height: 100px"></td></tr>
                    <tr><td><u><b>Yindar T. Sapangallo S.Sos</b></u></td></tr>
                    <tr><td>Kompol NRP 73080786</td></tr>
                </table>
            </td></tr>
        </tfoot>
    </table>
</body>
</html>