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
                    <h1>Edit Post</h1>
                </div>

            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">New Post's Info</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Image<span style="color: red;">*</span></label> <!-- Add * if it's a required field -->
                                    <input type="file" name="image_name" class="form-control">
                                    @if(!empty($getRecord->getImage()))
                                    <img src="{{ $getRecord->getImage() }}" style="height: 100px; width: 100px; border-radius: 20%;">
                                    @endif
                                </div>


                                <hr style="border: 1px dashed #ccc;">

                                <div class="form-group">
                                    <label>Description<span style="color: red;">*</span></label>
                                    <textarea type="text" name="description" value="{{ old('description', $getRecord->description) }}" class="form-control" required placeholder="Description"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Location<span style="color: red;">*</span></label>
                                    <input type="text" name="location" value="{{ old('location', $getRecord->location) }}" class="form-control" placeholder="Enter Location">
                                </div>

                                <div class="form-group">
                                    <label>Status<span style="color: red;">*</span></label>
                                    <select class="form-control" name="status" value="{{ old('status') }}">
                                        <option {{ (old('status', $getRecord->status) == 0) ? 'selected'  : '' }} value="0">Active</option>
                                        <option {{ (old('status', $getRecord->status) == 1) ? 'selected' : '' }} value="1">Inactive</option>
                                    </select>
                                </div>

                                
                                <hr style="border: 1px dashed #ccc; margin-top: 35px;">

                                <div class="form-group">
                                    <label style="display: block;">Allow Commenting<span style="color: red;"></span></label>
                                    <input type="checkbox" {{ !empty($getRecord->allow_commenting) ? 'checked' : ''}} name="allow_commenting">
                                </div>

                                <div class="form-group">
                                    <label style="display: block;">Allow View like Count<span style="color: red;"></span></label>
                                    <input type="checkbox" {{ !empty($getRecord->hide_like_view) ? 'checked' : ''}} name="hide_like_view">
                                </div>

                                <div class="form-group">
                                    <label style="display: block;">Lost Item<span style="color: red;"></span></label>
                                    <input type="checkbox" {{ !empty($getRecord->lost) ? 'checked' : ''}} name="lost">
                                </div>

                                <div class="form-group">
                                    <label style="display: block;">Found Item<span style="color: red;"></span></label>
                                    <input type="checkbox" {{ !empty($getRecord->found) ? 'checked' : ''}} name="found">
                                </div>

                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">

                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->
</div>
@endsection

@section('script')
@endsection