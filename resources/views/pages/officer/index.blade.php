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
                    <div class="card">
                        <div class="card-header">
                            @if($user->level=='Admin')
                            <a href="{{url('/officer/create')}}" class="btn btn-primary"><i class="fa fa-plus"></i>
                                Tambah</a>&nbsp;
                            @endif
                            <h4>{{$pageName}}</h4>
                            <div class="card-header-action">

                                <form action="{{url('officer_search')}}" method="POST">
                                    @csrf
                                    @php $filter = session('filter-officer');@endphp
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="name"
                                            value="{{$filter??''}}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>

                                        </div>
                                        @if($filter)
                                        <div class="input-group-append">
                                            <a href="{{url('officer_clear')}}" class="btn btn-danger"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                        @endif
                                    </div>

                                </form>

                            </div>
                        </div>
                        <div class="card-body">
                            <x-alert></x-alert>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-md data-table">
                                    <tr>
                                        <th>#</th>
                                        @if($user->level=='Admin')
                                        <th>Action</th>
                                        @endif
                                        <th>Name</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Posisi</th>
                                        <th>Unit Kerja</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>

                                    </tr>
                                    <?php $index=(($data->currentPage()-1)*$data->perPage())+1;?>


                                    @foreach($data as $d)
                                    <tr>
                                        <td style="width:50px">{{$index}}</td>
                                        @if($user->level=="Admin")
                                        <td style="width:10%;">
                                            <form method="post" action="{{url('officer',$d->id)}}">
                                                <a href='{{url("/officer/".$d->id."/edit")}}' class="btn btn-warning"><i
                                                        class="fa fa-edit"></i></a>

                                                @csrf
                                                @method('delete')

                                                <button type="submit" class="btn btn-danger show_confirm"
                                                    data="{{$d->p_name}}"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                        @endif
                                        <td>{{$d->o_name}}</td>
                                        <td>{{$d->gender=='P'?'Perempuan':'Laki-Laki'}}</td>
                                        <td>{{date('d M Y',strtotime($d->dob))}}</td>
                                        <td>{{$d->position}}</td>
                                        <td>{{$d->unit}}</td>
                                        <td>{{date('l, d F Y h:i:s a',strtotime($d->created_at))}}</td>
                                        <td>{{date('l, d F Y h:i:s a',strtotime($d->updated_at))}}</td>
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
                
                }
            });
    });

</script>
@endsection
