@extends('layout')
@section('title', 'Employee Leave Report')
@section('subtitle', 'Employee Leave Report')
@section('content')

<div class="row">
    <div class="col-lg-8 dashboard" style="margin-top: 20px !important;">
        <div class="row">
            <!-- Total Leave Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="filter">
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Total Leave</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-card-list"></i>
                            </div>  
                            <div class="ps-3">
                            <h6>{{ $totalLeaveData }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Total Leave Card -->

            <!-- Leave Spent Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card revenue-card">
                    <div class="filter">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Leave Spent</h5>
                        <div class="d-flex align-items-center leavesMemberCont">
                            <div class="leavesMemeberInnerCont">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-card-list"></i>
                                </div>
                                <div class="ps-3">
                                <h6>{{ $totalLeaveSpentCount }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!---End Leave Spent Card--->

            <!-- Total Pending Leave Card -->
            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">
                    <div class="filter">
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Pending Leave</h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-card-list"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $pendingLeavesCount }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Total Pending Leave Card -->
    </div>
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="box-header with-border" id="filter-box">
                <br>
                <div class="box-body table-responsive" style="margin-bottom: 5%">
                    <table class="table table-borderless datatable" id="leavesss">
                        <thead>
                            <tr>
                                <th scope="col">From</th>
                                <th scope="col">To</th>
                                <th scope="col">Type</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <?php 
                               use App\Http\Controllers\DashboardController;
                            ?>
                        <tbody>
                            @forelse($userLeaves as $data)
                            <tr>
                                <td>{{date("d-M-Y", strtotime($data->from));}}</td>
                                <td>{{date("d-M-Y", strtotime($data->to));}}</td>
                                <?php 
                                    $get_type_name = DashboardController::get_type_name($data->type);
                                // Check if the collection is not empty

                                    if (!$get_type_name->isEmpty()) {
                                        $leaveType = $get_type_name->first();
                                        $leave_type = $leaveType->leave_type;
                                    } else {
                                        $leave_type = '';
                                    }
                                ?>
                                <td>{{ $leave_type }}</td>
                                <td> ----
                                </td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection

@section('js_scripts')
    <script>
        $(document).ready(function() {
            $('#users_table').DataTable({
            });
        });
    </script>
    @endsection