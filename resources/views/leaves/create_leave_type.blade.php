@extends('layout')
@section('title', 'Leaves Type')
@section('subtitle', 'Leaves Type')
@section('content')
<div id="loader">
    <img class="loader-image" src="{{ asset('assets/img/loading.gif') }}" alt="Loading..">
</div>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
          
<form action="{{ url('store_leave_type') }}" method="POST">
    @csrf
    <div class="modal-body">

        <div class="row mb-3 mt-4">
            <label for="title" class="col-sm-3 col-form-label required">Type of Leave</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" name="leave_type" id="leave_type">
                @if ($errors->has('leave_type'))
                <span class="text-danger">{{ $errors->first('leave_type') }}</span>
                @endif
            </div>
        </div>
        <div class="row mb-3 mt-4">
            <label for="title" class="col-sm-3 col-form-label required">Total Holidays</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" name="total_holidays" id="total_holidays">
                @if ($errors->has('total_holidays'))
                <span class="text-danger">{{ $errors->first('total_holidays') }}</span>
                @endif
            </div>
        </div>
    </div>


    <div class="modal-footer back-btn">
    <button type="submit"  class="btn btn-primary">Save</button>
    </div>

</form>
        </div>
    </div>
</div>

@endsection
@section('js_scripts')
<script>
$(document).ready(function() {

});
</script>

@endsection