@extends('layout')

@section('title', 'Leave Type')

@section('subtitle', 'Leave Type')

@section('content')



<div class="col-lg-12">

    <div class="card">

        <div class="card-body">

            <a href="{{ url('leave_type') }}" class="btn btn-primary mt-3 mb-4" href="javascript:void(0)">Add Leave Type</a>

            <!-- filter -->

            <div class="box-header with-border" id="filter-box">

                <div class="box-body table-responsive" style="margin-bottom: 5%">

                    <table class="table table-borderless dashboard" id="module_table">

                        <thead>

                            <tr>

                                <th>Leave Type</th>
                                <th>Total Holidays</th>

                                <th>Action</th>



                            </tr>

                        </thead>



                        <tbody>

                          @foreach($leaves as $val)

                            <tr>

                                <td>{{ $val->leave_type}}</td>
                                <td>{{ $val->leave_count}}</td>

                                <td>

                                    <i style="color:#4154f1;" onClick="editModule('{{ $val->id }}')" href="javascript:void(0)" class="fa fa-edit fa-fw pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Leave Type"></i>

                                    <i style="color:#4154f1;" onClick="deleteModule('{{ $val->id }}')" href="javascript:void(0)" class="fa fa-trash fa-fw pointer" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Leave Type"></i>

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

<div class="modal fade" id="editModule" tabindex="-1" aria-labelledby="addModuleLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="addModuleLabel">Edit Leave Type</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <form method="post"  action="{{ url('update_leave_type')}}">

                @csrf

                <div class="modal-body">

                    <div class="alert alert-danger" style="display:none"></div>



                    <div class="row mb-3">

                        <label for="module_name" class="col-sm-3 col-form-label required">Leave Type</label>

                        <div class="col-sm-9">

                          <input type="hidden" class="form-control" name="leave_type_id" id="leave_type_id">

                            <input type="text" class="form-control" name="leave_type" id="leave_type">

                        </div>

                    </div>

                    <div class="row mb-3">

                        <label for="module_name" class="col-sm-3 col-form-label required">Total Holidays</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="total_holidays" id="total_holidays">

                        </div>

                    </div>


                  



                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="button" class="btn btn-primary" onClick="updateModule()" href="javascript:void(0)">Save</button>

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



        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            }

        });

    });



    function openModuleModel() {

        $('#page_name').val('');

        $('#addModule').modal('show');

    }



    function updateModule() {

        var leave_type_id = $('#leave_type_id').val();
        var leave_type = $('#leave_type').val();
        var total_holidays = $('#total_holidays').val();

        $.ajax({

            type: 'POST',
            url: "{{ url('/update_leave_type')}}",
            data: {
                leave_type_id: leave_type_id,
                leave_type:leave_type,
                total_holidays: total_holidays
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
                    $('.alert-danger').hide();
                    $("#addModule").modal('hide');
                    location.reload();
                }
            },
                error: function(data) {
                    console.log(data);
                }
        });
    }



    function editModule(id) {

        $('#leave_type_id').val(id);

        $.ajax({

            type: "POST",

            url: "{{ url('/edit/leave_type') }}",

            data: { id: id },

            dataType: 'json',

            success: function(res) {
            if (res) {
                var leave_type = res.leave_type;
                var total_holidays = res.leave_count;
                
                $('#leave_type').val(leave_type);
                $('#total_holidays').val(total_holidays); 
                
                $('#editModule').modal('show');
            } else {
                console.log('Error: Leave type not found');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching leave type:', error);
        }

        });

    }



    // function updateModule() {

    //     var id = $('#hidde_module_id').val();



    //     $.ajax({

    //         type: "POST",

    //         url: "{{ url('/update/module') }}",

    //         data: {

    //             id: id

              

    //         },

    //         dataType: 'json',

    //         success: function(res) {

    //             if (res.errors) {

    //                 $('.alert-danger').html('');



    //                 $.each(res.errors, function(key, value) {

    //                     $('.alert-danger').show();

    //                     $('.alert-danger').append('<li>' + value + '</li>');

    //                 })

    //             } else {

    //                 $('.alert-danger').html('');

    //                 $("#editModule").modal('hide');

    //                 location.reload();

    //             }

    //         }

    //     });

    // }



    function deleteModule(id) {

        if (confirm("Are You Sure You Want To delete Leave Type ?") == true) {

            $.ajax({

                type: "post",

                url: "{{ url('delete_leave_type') }}",

                data: {id: id},

                dataType: 'json',

                success: function(res) {

                    location.reload();

                }

            });

        }

    }

</script>

@endsection