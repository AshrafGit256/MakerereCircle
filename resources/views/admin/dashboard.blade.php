@extends('admin.layouts.app')
@section('style')
@endsection

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Makerere<b> Circle</b></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Makerere<b> Circle</b></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Info boxes -->
      <div class="row">
        <!-- Total Users -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-primary elevation-1">
              <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Total Users</span>
              <span class="info-box-number">{{ $TotalUsers }}</span>
            </div>
          </div>
        </div>

        <!-- Today’s Users -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1">
              <i class="fas fa-user-clock"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Today’s Users</span>
              <span class="info-box-number">{{ $TotalTodayUsers }}</span>
            </div>
          </div>
        </div>

        <!-- Total Posts -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-secondary elevation-1">
              <i class="fas fa-list-alt"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Total Posts</span>
              <span class="info-box-number">{{ $TotalPosts }}</span>
            </div>
          </div>
        </div>

        <!-- Today’s Posts -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1">
              <i class="fas fa-calendar-alt"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Today’s Posts</span>
              <span class="info-box-number">{{ $TotalTodayPosts }}</span>
            </div>
          </div>
        </div>

        <!-- Total Found Items -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-teal elevation-1">
              <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Total Found Items</span>
              <span class="info-box-number">{{ $TotalFound }}</span>
            </div>
          </div>
        </div>

        <!-- Today’s Found Items -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-lime elevation-1">
              <i class="fas fa-calendar-check"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Today’s Found Items</span>
              <span class="info-box-number">{{ $TotalTodayFound }}</span>
            </div>
          </div>
        </div>

        <!-- Total Lost Items -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1">
              <i class="fas fa-question-circle"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Total Lost Items</span>
              <span class="info-box-number">{{ $TotalLost }}</span>
            </div>
          </div>
        </div>

        <!-- Today’s Lost Items -->
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-maroon elevation-1">
              <i class="fas fa-calendar-times"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Today’s Lost Items</span>
              <span class="info-box-number">{{ $TotalTodayLost }}</span>
            </div>
          </div>
        </div>
      </div>
      

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Lost & Found Analysis Graph</h3>
                <select class="form-control ChangeYear" style="width: 100px;">
                  @for($i=2024; $i<=date('Y'); $i++)
                  <option {{ ($year == $i) ? 'selected' : ''}} value="{{ $i }}" >{{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex">
                <p class="d-flex flex-column">

                  <span>Platform Activity</span>
                </p>

              </div>
              <!-- /.d-flex -->

              <div class="position-relative mb-4">
                <canvas id="users-progress-chart" height="200"></canvas>
              </div>

              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  <i class="fas fa-square text-primary"></i> Users
                </span>

                <span class="mr-2">
                  <i class="fas fa-square text-success"></i> Posts
                </span>

                <span class="mr-2">
                  <i class="fas fa-square text-danger"></i> Lost Items
                </span>

                <span class="mr-2">
                  <i class="fas fa-square text-warning"></i> Found Items
                </span>

              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-12">



          <!-- TABLE: LATEST ORDERS -->
          <div class="card">
            <div class="card-header border-transparent">
              <h3 class="card-title">Latest Posts</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
              <table class="table m-0">
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
                      <!-- <th>Status</th> -->
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($getLatestPosts as $value)
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

                      <td>{{ ($value->Lost == 1) ? 'yes' : 'no' }}</td>
                      <td>{{ ($value->found == 1) ? 'yes' : 'no' }}</td>
                      <td>{{ ucfirst($value->type) }}</td>
                      <td>{{ $value->created_by_name }}</td>
                      <!-- <td>{{ ($value->status == 0) ? "active" : "Inactive"}}</td> -->
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
              <!-- /.table-responsive -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Posts</a>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->

        <div class="col-md-4">

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!--/. container-fluid -->
  </section>
  <!-- /.content -->
</div>
@endsection

@section('script')
<script src="{{ url('assets/dist/js/pages/dashboard3.js') }}"></script>
<script>
  $('.ChangeYear').change(function(){
    var year = $(this).val();
    window.location.href = "{{ url('admin/dashboard?year=') }}"+year;
  })
  var ticksStyle = {
    fontColor: '#000',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  var $usersChart = $('#users-progress-chart')
  // eslint-disable-next-line no-unused-vars
  var usersChart = new Chart($usersChart, {
    type: 'bar',
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [{
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: [{{ $getTotalUserMonth }}]
        },
        {
          backgroundColor: '#28a745',
          borderColor: '#28a745',
          data: [{{ $getTotalPostMonth }}]
        },
        {
          backgroundColor: '#dc3545',
          borderColor: '#dc3545',
          data: [{{ $getTotalLostMonth }}]
        },
        {
          backgroundColor: '#eeb40f',
          borderColor: '#eeb40f',
          data: [{{ $getTotalFoundMonth }}]
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function(value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return '' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
</script>
@endsection