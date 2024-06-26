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
                <div class="col-12">
                <x-alert></x-alert>
                </div>
              <div class="col-6 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Chart Performance Ranking</h4>
                   

                 </div>
                  <div class="card-body">
                     
                      
                      <div class="form-group">
                        <label>Pegawai</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <select name="officer" id="officer" class="form-control" >
                                @foreach($officer as $f)
                                    <option value="{{$f->id}}">{{$f->o_name}}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button onclick="getDataPerYear()"
                                    class="btn btn-info">Terapkan</button>
                                
                            </div>
                        </div>
                      </div>
                      <div class=" text-center ">
                        <div id="chart_place"><canvas id="chart_test"></canvas></div>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    
                  </div>
                </div>
              </div>
              <div class="col-6 col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Chart Monitoring Progres</h4>
                   

                 </div>
                  <div class="card-body">
                     
                      
                      <div class="form-group">
                        <label>Pegawai</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </div>
                            </div>
                            <select name="officer" id="officerMon" class="form-control" >
                                @foreach($officer as $f)
                                    <option value="{{$f->id}}">{{$f->o_name}}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button onclick="getDataMonitoring()"
                                    class="btn btn-info">Terapkan</button>
                                
                            </div>
                        </div>
                      </div>
                      <div class=" text-center ">
                        <div id="monitoring_place"><canvas id="chart_monitoring"></canvas></div>
                    </div>
                  </div>
                  <div class="card-footer text-right">
                    
                  </div>
                </div>
              </div>
            
           
          </div>
        </section>
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
      </script>
      <script>
          document.onreadystatechange = ()=>{
              if(document.readyState==='complete'){
                  $('#alert').fadeOut(15000);
                  getDataPerYear();
                  getDataMonitoring();
              }
          }
          
   
        function getDataPerYear() {
            const officer = document.getElementById('officer');
            
            $('#chart_test').remove();
            $('#chart_place').append('<canvas id="chart_test"></canvas>');
            $.get('{{url("/get_data_rank/")}}/' + officer.value, function (data) {
                // alert(JSON.stringify(data["dataset"]));
    
                var config = {
                    type: "bar",
    
                    data: {
                        labels: data["labels"],
                        datasets: data["dataset"]
    
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                };
                myChart = new Chart(
                    'chart_test',
                    config
                );
                
            });
        }
        function getDataMonitoring() {
            const officer = document.getElementById('officerMon');
            
            $('#chart_monitoring').remove();
            $('#monitoring_place').append('<canvas id="chart_monitoring"></canvas>');
            $.get('{{url("/get_data_monitoring/")}}/' + officer.value, function (data) {
                // alert(JSON.stringify(data["dataset"]));
    
                var config = {
                    type: "bar",
    
                    data: {
                        labels: data["labels"],
                        datasets: data["dataset"]
    
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                };
                myChart = new Chart(
                    'chart_monitoring',
                    config
                );
                
            });
        }
    
    </script>
@endsection