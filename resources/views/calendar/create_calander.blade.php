@extends('layout')

@section('title', 'Holidays')

@section('subtitle', 'Holidays')

@section('content')



<div class="col-lg-12">

    <div class="card">

        <div class="card-body">

            <button class="btn btn-primary mt-3 mb-4" onClick="opencalanderModel()" href="javascript:void(0)">Add

                Calendar</button>



            <form id="filter-data" method="GET" action="{{ route('holidays.index') }}">

                <div class="row mt-3 mx-auto">

                    <div class="col-md-6 filtersContainer d-flex p-0">





                    </div>

                </div>

            </form>

            <!-- filter -->

            <div class="box-header with-border" id="filter-box">

                <div class="box-body table-responsive" style="margin-bottom: 5%">

                    <table class="table table-borderless dashboard" id="module_table">

                        <thead>

                            <tr>

                                <th>Calendar</th>

                                <th>Action</th>

                            </tr>

                        </thead>



                        <tbody>

                            @foreach($calendar as $val)

                            <tr>

                                <td>{{ $val->calendar_name }} </td>

                                <td>

                                    <i style="color:#4154f1;" onClick="editcalander('{{ $val->id }}')" href="javascript:void(0)" class="fa fa-edit fa-fw pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Calendar"></i>



                                    <i style="color:#4154f1;" onClick="deletecalendar('{{ $val->id }}')" href="javascript:void(0)" class="fa fa-trash fa-fw pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Calendar"></i>



                                    <i style="color:#4154f1;" onClick="openHolidayModel('{{ $val->id }}')" href="javascript:void(0)" class="fa fa-plus-square fa-fw pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Holiday"></i>



                                    <a href="{{ url('holidays/'.$val->id) }}" class="fa fa-eye" style="color:#4154f1;" data-bs-toggle="tooltip" data-bs-placement="top" title="View Holidays"></a>





                                </td>

                            </tr>

                            @endforeach



                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>



<!--start: Add Module Modal -->

<div class="modal fade" id="addcalander" tabindex="-1" aria-labelledby="addHolidayLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="addHolidayLabel">Add Calendar</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form method="post" id="addHolidayForm" action="">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-danger" style="display:none"></div>



                    <div class="row mb-3">

                        <label for="holiday_name" class="col-sm-3 col-form-label required">Calendar Name</label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="calendar_name" id="calendar_name">

                        </div>

                        @if ($errors->has('calendar_name'))

                        <span style="font-size: 12px;" class="text-danger">{{ $errors->first('calendar_name') }}</span>

                        @endif

                    </div>





                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="button" class="btn btn-primary" onClick="addcalander()" href="javascript:void(0)">Save</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!--end: Add Module Modal -->



<!--start: Edit Module Modal -->

<div class="modal fade" id="editcalander" tabindex="-1" aria-labelledby="editHolidayLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="editHolidayLabel">Edit Calendar</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form method="post" id="editHolidayForm" action="">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-danger" style="display:none"></div>



                    <div class="row mb-3">

                        <label for="edit_holiday_name" class="col-sm-3 col-form-label required">Calendar Name</label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="edit_cal_name" id="edit_cal_name">

                        </div>

                        @if ($errors->has('edit_cal_name'))

                        <span style="font-size: 12px;" class="text-danger">{{ $errors->first('edit_cal_name') }}</span>

                        @endif

                    </div>

                    <input type="hidden" class="form-control" id="calendar_id" value="">

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="button" class="btn btn-primary" onClick="updatecal()" href="javascript:void(0)">Update</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!--end: Edit Module Modal -->

<!--start: Add Module Modal -->

<div class="modal fade" id="addHoliday" tabindex="-1" aria-labelledby="addHolidayLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="addHolidayLabel">Add Holiday</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form method="post" id="addHolidayForm" action="">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-danger" style="display:none"></div>

                    <div class="row mb-3">
                        <label for="add_cal_name" class="col-sm-3 col-form-label">Calendar Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="add_cal_name" value="" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <label for="holiday_name" class="col-sm-3 col-form-label required">Holiday Name</label>

                        <div class="col-sm-9">

                            <input type="hidden" name="cal_id" id="cal_id">

                            <input type="text" class="form-control" name="holiday_name" id="holiday_name">

                        </div>

                        @if ($errors->has('holiday_name'))

                        <span style="font-size: 12px;" class="text-danger">{{ $errors->first('holiday_name') }}</span>

                        @endif

                    </div>



                    <div class="row mb-3">

                        <label for="user_name" class="col-sm-3 col-form-label required">From</label>

                        <div class="col-sm-9">

                            <input type="date" class="form-control" name="from" id="from" min="{{ date('Y-m-d') }}">

                        </div>

                        @if ($errors->has('from'))

                        <span style="font-size: 12px;" class="text-danger">{{ $errors->first('from') }}</span>

                        @endif

                    </div>

                    <div class="row mb-3">

                        <label for="last_name" class="col-sm-3 col-form-label required">To</label>

                        <div class="col-sm-9">

                            <input type="date" class="form-control" name="to" id="to">

                        </div>

                        @if ($errors->has('to'))

                        <span style="font-size: 12px;" class="text-danger">{{ $errors->first('to') }}</span>

                        @endif

                    </div>



                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="button" class="btn btn-primary" onClick="addHoliday()" href="javascript:void(0)">Save</button>

                </div>

            </form>

        </div>

    </div>

</div>

<!--end: Add Module Modal -->

@endsection

@section('js_scripts')

<script>

    $(document).ready(function() {

        setTimeout(function() {

            $('.message').fadeOut("slow");

        }, 2000);

        $('#module_table').DataTable({

            "order": []

            //"columnDefs": [ { "orderable": false, "targets": 7 }]

        });

        $('#from').change(function() {

            var fromDate = $(this).val();
            console.log('From Date:', fromDate);

            $('#to').attr('min', fromDate);
            var toDate = $('#to').val();
            console.log('To Date:', toDate);
            if (toDate < fromDate) {
                $('#to').val('');
            }

        });


        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });

    });



    function openHolidayModel(id) {

        $('#page_name').val('');

        $('#cal_id').val(id);

        $('#addHoliday').modal('show');

        $.ajax({

            type: "post",

            url: "{{ url('/calendar/name') }}",

            data: {
            id: id
            },
            success: function(response) {
                console.log(response);
                $('#add_cal_name').val(response.name); 
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }

        });



    }



    function opencalanderModel() {

        $('#page_name').val('');

        $('#addcalander').modal('show');

    }



    function addcalander() {

        var calendarName = $('#calendar_name').val();

        $.ajax({

            type: 'POST',

            url: "{{ url('/add/calendar')}}",

            data: {

                calendarName: calendarName

            },

            cache: false,

            success: (data) => {

                if (data.errors) {

                    $('.alert-danger').html('');



                    $.each(data.errors, function(key, value) {

                        $('.alert-danger').show();

                        $('.alert-danger').append('<li>' + value + '</li>');

                    })

                } else {

                    $('.alert-danger').html('');

                    $("#addcalander").modal('hide');

                    location.reload();

                }

            },

            error: function(data) {

                console.log(data);

            }

        });

    }



    function editcalander(id) {



        $('#calendar_id').val(id);



        $.ajax({

            type: "POST",

            url: "{{ url('/edit/calendar') }}",

            data: {

                id: id

            },

            dataType: 'json',

            success: function(res) {

                if (res.calendar != null) {

                    $('#edit_cal_name').val(res.calendar.calendar_name);

                    $('#editcalander').modal('show');



                }

            }

        });

    }



    function updatecal() {

        var id = $('#calendar_id').val();

        var edit_cal_name = $('#edit_cal_name').val();



        $.ajax({

            type: "POST",

            url: "{{ url('/update/calendar') }}",

            data: {

                id: id,

                calendar_name: edit_cal_name,

            },

            dataType: 'json',

            success: function(res) {

                if (res.errors) {

                    $('.alert-danger').html('');



                    $.each(res.errors, function(key, value) {

                        $('.alert-danger').show();

                        $('.alert-danger').append('<li>' + value + '</li>');

                    })

                } else {

                    $('.alert-danger').html('');

                    $("#editcalander").modal('hide');

                    location.reload();

                }

            }

        });

    }



    function deletecalendar(id) {

        if (confirm("Are you sure You Want To Delete Calendar From List?") == true) {

            $.ajax({

                type: "post",

                url: "{{ url('/delete/calendar') }}",

                data: {

                    id: id

                },

                dataType: 'json',

                success: function(res) {

                    location.reload();

                }

            });

        }

    }



    function addHoliday() {

        var cal_id = $('#cal_id').val();

        var holidayName = $('#holiday_name').val();

        var from = $('#from').val();

        var to = $('#to').val();


        $.ajax({

            type: 'POST',

            url: "{{ url('/add/holiday')}}",

            data: {

                holidayName: holidayName,

                from: from,

                to: to,

                cal_id: cal_id

            },

            cache: false,

            success: (data) => {

                if (data.errors) {

                    $('.alert-danger').html('');



                    $.each(data.errors, function(key, value) {

                        $('.alert-danger').show();

                        $('.alert-danger').append('<li>' + value + '</li>');

                    })

                } else {

                    $('.alert-danger').html('');

                    $("#addHoliday").modal('hide');

                    location.reload();

                }

            },

            error: function(data) {

                console.log(data);

            }

        });

    }



    function editHoliday(id) {

        $('#hidde_holiday_id').val(id);



        $.ajax({

            type: "POST",

            url: "{{ url('/edit/holiday') }}",

            data: {

                id: id

            },

            dataType: 'json',

            success: function(res) {

                if (res.holiday != null) {

                    $('#editHoliday').modal('show');

                    $('#edit_holiday_name').val(res.holiday.name);

                    $('#edit_from').val(res.holiday.from);

                    $('#edit_to').val(res.holiday.to);

                }

            }

        });

    }



    function updateHoliday() {

        var id = $('#hidde_holiday_id').val();

        var edit_holiday_name = $('#edit_holiday_name').val();

        var edit_from = $('#edit_from').val();

        var edit_to = $('#edit_to').val();



        $.ajax({

            type: "POST",

            url: "{{ url('/update/holiday') }}",

            data: {

                id: id,

                edit_holiday_name: edit_holiday_name,

                edit_from: edit_from,

                edit_to: edit_to,

            },

            dataType: 'json',

            success: function(res) {

                if (res.errors) {

                    $('.alert-danger').html('');



                    $.each(res.errors, function(key, value) {

                        $('.alert-danger').show();

                        $('.alert-danger').append('<li>' + value + '</li>');

                    })

                } else {

                    $('.alert-danger').html('');

                    $("#editHoliday").modal('hide');

                    location.reload();

                }

            }

        });

    }



    function deleteHoliday(id) {

        if (confirm("Are you sure You Want To Delete Holiday From List?") == true) {

            $.ajax({

                type: "DELETE",

                url: "{{ url('/delete/holiday') }}",

                data: {

                    id: id

                },

                dataType: 'json',

                success: function(res) {

                    location.reload();

                }

            });

        }

    }



    // Event listener for checkbox changes

    $("#filter-data input:checkbox").change(function() {

        // Submit the form

        $("#filter-data").submit();

    });

</script>

@endsection