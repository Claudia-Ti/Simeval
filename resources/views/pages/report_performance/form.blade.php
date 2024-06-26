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
              <?php $user = Auth::user();?>
            <div class="row">
              <div class="col-12 col-md-12 col-lg-12">
                <form action="{{route('performanceStoreBulk')}}" method="POST">
                    @csrf
                <div class="card">
                  <div class="card-header">
                    <h4>{{$pageName}}</h4>
                    
                    <div class="card-header-action">
                        <a href="{{url('performance_report/create')}}" class="btn btn-info">Back</a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                    </div>
                 </div>
                  <div class="card-body">
                      <x-alert></x-alert>
                      
                        <input type="hidden" value="{{$officerId}}" name="officerId"/>
                        <input type="hidden" value="{{$periodId}}" name="periodId"/>
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-md data-table">
                        <tr>
                          <th>#</th>
                          <th>Criteria Name</th>
                          <th width="10%">Value</th>
                          <th>Notes</th>
                          
                        </tr>
                        <?php $index=1;?>
                       
                        
                       @foreach($data as $d)
                            <tr>
                                <td style="width:50px;padding-top:20px">{{$index}}</td>
                                
                                <td style="padding-top:20px"><label>{{$d->c_name}}</label></td>
                                <td>
                                    <input type="hidden" value="{{$d->id}}" name="criteriaId[]"/>
                                    @if($user->level=='Kasat')
                                    <input type="number" class="form-control" name="criteria[]" value="{{$d["value"]??''}}"/>
                                    @else
                                    <input type="number" class="form-control" name="criteria[]" value="{{$d["value"]??''}}" readonly/>
                                    @endif
                                    <input type="hidden" value="{{$d["id_performance"]??'-'}}" name="id_performance[]"/>
                                    
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="notes[]" value="{{$d["notes"]??''}}"/>
                                </td>
                            </tr>
                        <?php $index+=1;?>
                       @endforeach
                      </table>
                    </div>
                      
                  </div>
                  
                </div>
            </form>
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
      var form =  $(this).closest("form");
      var periodV = document.getElementById("id_period");
      var sPeriod = document.getElementById('period');
      var officer = document.getElementById('officerName');
      sPeriod.value = periodV.value;
      event.preventDefault();
      new swal({
            title: `Are you sure you want to add performance report ?`,
            text: "Add Report For "+officer.value+" on "+periodV.options[periodV.selectedIndex].text+"?",
            icon: "warning",
            
            buttons: ["Cancel","Yes!"],
            dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          form.submit();
            // alert("Will Delete Value : "+JSONStringify(willDelete));
        }else{
            
        }
      });
  });
  function reportForm(id){
    var period = document.getElementById('id_period');
    alert("ID : "+id+", PERIOD : "+period.value);
  }
</script>
@endsection