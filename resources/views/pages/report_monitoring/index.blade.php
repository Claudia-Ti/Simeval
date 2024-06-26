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
              <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>{{$pageName}}</h4>
                    <div class="card-header-action">
                      <a href="{{url('monitoring_report')}}" class="btn btn-info">Back</a>
                      
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
                            <select name="id_period" id="id_period" class="form-control" >
                                @foreach($period as $p)
                                    <option value="{{$p->id}}">{{$p->p_name}}</option>
                                @endforeach
                            </select>
  
                        </div>
                      </div>
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-md data-table">
                        <tr>
                          <th>#</th>
                          <th>Action</th>
                          <th>Name</th>
                          <th>Jenis Kelamin</th>
                          <th>Tanggal Lahir</th>
                          <th>Posisi</th>
                          <th>Unit Kerja</th>
                          
                          
                        </tr>
                        <?php $index=(($data->currentPage()-1)*$data->perPage())+1;?>
                       
                        
                       @foreach($data as $d)
                            <tr>
                                <td style="width:50px">{{$index}}</td>
                                <td style="width:10%;">
                                  <input type='hidden' value="{{$d->id}}" name="officerId" id="officerId{{$index}}"/>
                                    <input type="hidden" id="officerName{{$index}}" value="{{$d->o_name}}"/>
                                    <a href="#"  class="btn btn-success" onclick="javascript:reportForm({{$index}})" data="{{$d->p_name}}"><i class="fa fa-check"></i> Input</a>
                                  
                                </td>
                                
                                <td>{{$d->o_name}}</td>
                                <td>{{$d->gender=='P'?'Perempuan':'Laki-Laki'}}</td>
                                <td>{{date('d M Y',strtotime($d->dob))}}</td>
                                <td>{{$d->position}}</td>
                                <td>{{$d->unit}}</td>
                               
                            </tr>
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
              
            
           
          </div>
        </section>
      </div>
      <script>
          document.onreadystatechange = ()=>{
              if(document.readyState==='complete'){
                  $('#alert').fadeOut(15000);
              }
          }
          
    </script>
    
    <script type="text/javascript">
 
 $('.show_confirm').click(function(event) {
      // var form =  $(this).closest("form");
      
  });
  function reportForm(counter){
    var periodV = document.getElementById("id_period");
      var officerId = document.getElementById('officerId'+counter);
      var sPeriod = document.createElement("input");
      sPeriod.setAttribute('type','hidden');
      sPeriod.setAttribute('name','periodId');
      sPeriod.setAttribute('id','period-'+officerId.value);
      sPeriod.value = periodV.value;
      // form.append(sPeriod);
      var officer = document.getElementById('officerName'+counter);
      
      event.preventDefault();
      new swal({
            title: `Are you sure you want to add monitoring report ?`,
            text: "Add Report For "+officer.value+" on "+periodV.options[periodV.selectedIndex].text+"?",
            icon: "warning",
            
            buttons: ["Cancel","Yes!"],
            dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          var periodV = document.getElementById("id_period");
           var officerId = document.getElementById('officerId'+counter);
          window.location = "{{url('monitoring_report_form')}}/"+officerId.value+"/"+periodV.value;
            // alert("Will Delete Value : "+JSONStringify(willDelete));
        }else{
          // var officerId = document.getElementById('officerId');
          // var sPeriod = document.getElementById("period-"+officerId.value);
          // if(sPeriod.parentNode){
          //   sPeriod.parentNode.removeChild(sPeriod);
          // }
        }
      });
  }
</script>
@endsection