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

    <form method="get">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Post Search</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>ID</label>
                            <input type="text" name="id" placeholder="ID" class="form-control" value="{{ Request::get('id') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" placeholder="Description" class="form-control" value="{{ Request::get('description') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" placeholder="Location" class="form-control" value="{{ Request::get('location') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" name="type" placeholder="Type" class="form-control" value="{{ Request::get('type') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Created By</label>
                            <input type="text" name="created_by_name" placeholder="Creator" class="form-control" value="{{ Request::get('created_by_name') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="0" {{ Request::get('status') === '0' ? 'selected' : '' }}>Active</option>
                                <option value="1" {{ Request::get('status') === '1' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ Request::get('from_date') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ Request::get('to_date') }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-2">
                        <button class="btn btn-primary">Search</button>
                        <a href="{{ url('admin/post/list') }}" class="btn btn-success">Reset</a>
                    </div>
                </div>

            </div>
        </div>
    </form>


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
                                        <!-- <th>Image</th> -->
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
                                        <!-- <td>
                                            @if(!empty($value->getImage()))
                                            <img src="{{ $value->getImage() }}" style="height: 80px; width: 80px; border-radius: 20%">
                                            @endif

                                        </td> -->
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