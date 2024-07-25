@extends('layout')
@section('title', 'Employee Leave Report - ' . $employeeName)
@section('subtitle', 'Employee Leave Report')
@section('content')

<div class="form_fill">
    <form id="year_filter_form" action="{{ route('leave.report', ['id' => $id]) }}" method="GET" class="form-inline">
    <select class="form-select" id="year_filter" name="year">
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ $year == $CurrentYear ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        <div class="form-group">
            <button type="submit" class="btn btn-primary ml-2">Apply Filter</button>    
        </div>
    </form>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card recent-sales ">
            <div class="card-body">
                <h5 class="card-title">Leave Type Record</h5>
                    <table class="table table-borderless" id="leave_data">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Total Leaves</th>
                                <th scope="col">Leaves Occupied</th>
                                <th scope="col">Pending Leaves</th>
                                <th scope="col">Carry Forward</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($segregatedArrays as $item)
                        <tr>
                            <td>{{ $item['leave_type'] }}</td>
                            <td>{{ $item['leave_count'] }}</td>
                            <td>{{ $item['leave_day_count'] }}</td>
                            <td>{{ $item['pending_leaves'] }}</td>
                            <td>-----</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row">
    <div class="col-lg-8 dashboard" style="margin-top: 20px !important;">
        <div class="row">
            Total Leave Card
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
            End Total Leave Card

            Leave Spent Card
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
            -End Leave Spent Card-

            Total Pending Leave Card
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
            End Total Pending Leave Card
    </div>
</div> -->

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
        <h5 class="card-title">Leave Record</h5>
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
                                <td>
                                    
                                    @php

                                        $leaveStatusData = $leaveStatus->where('leave_id', $data->id)->first();

                                        @endphp

                                        @if($data->leave_status == 'approved')

                                        <span class="badge rounded-pill approved">Approved</span>

                                        @elseif($data->leave_status == 'declined')

                                        <span class="badge rounded-pill denied">Declined</span>

                                        @else

                                        <span class="badge rounded-pill requested">Requested</span>

                                        @endif

                                        @if (!empty($leaveStatusData))

                                        <p class="small mt-1" style="font-size: 11px;font-weight:600; margin-left:6px;"> By:

                                            {{ $leaveStatusData->first_name ?? '' }}

                                        </p>

                                    @endif
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
            $('#leavesss').DataTable({
                "language": {
                "emptyTable": "No records for this year"
            }
            });
        });
        
    </script>
    @endsection