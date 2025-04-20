@extends('admin.layouts.app')

@section('style')
@endsection

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Posts List</h1>
                </div>
                <div class="col-sm-6" style="text-align:right">
                    <a href="{{ url('admin/post/add') }}" class="btn btn-primary"> <i class="fas fa-plus-circle"></i> Add New Post</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">


                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Posts List</h3>
                        </div>

                        @include('admin.layouts._message')
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="btn-primary">
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Description</th>
                                        <th>Location</th>
                                        <th>Liking</th>
                                        <th>Commenting</th>
                                        <th>Lost Item</th>
                                        <th>Found Item</th>
                                        <th>Type</th>
                                        <th>Created By</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getRecord as $value)
                                    <tr>
                                        <td>{{$value->id}}</td>
                                        <td>
                                            @if(!empty($value->getImage()))
                                            <img src="{{ $value->getImage() }}" style="height: 80px; width: 80px; border-radius: 20%">
                                            @endif
                                        </td>
                                        <td>{{$value->description}}</td>
                                        <td>{{$value->location}}</td>
                                        <td>{{ ($value->hide_like_view == 1) ? 'yes' : 'no' }}</td>
                                        <td>{{ ($value->allow_commenting == 1) ? 'yes' : 'no' }}</td>
                                        
                                        <td>{{ ($value->lost == 1) ? 'yes' : 'no' }}</td>
                                        <td>{{ ($value->found == 1) ? 'yes' : 'no' }}</td>
                                        <td>{{ ucfirst($value->type) }}</td>
                                        <td>{{ $value->created_by_name }}</td>
                                        <td>{{ ($value->status == 0) ? "active" : "Inactive"}}</td>
                                        <td>{{ date('d-m-y', strtotime($value->created_at))}}</td>
                                        <td>
                                            <a href="{{ url('admin/post/edit/'.$value->id) }}" class="btn btn-success"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="{{ url('admin/post/delete/'.$value->id) }}" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>

            </div>

        </div>
    </section>

</div>
@endsection

@section('script')
@endsection