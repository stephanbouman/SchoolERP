@extends('master')
@section('title', auth()->user()->role_name . ' ' . auth()->user()->last_name . ' | Role Create')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>
        <!-- Main content -->
        <section class="content attendance">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Permission Assign</h3>
                                <div class="card-title float-right">
                                    <a href="{{ route('user_permission.index') }}">
                                        <input type="button" value="User Permissions" class="btn btn-sm btn-warning">
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div>
                                    <form action="{{ route('user_permission.store') }}" method="post">
                                        @csrf
                                        @method('post')
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                                        <div class="form-group">
                                                            <label>Minimal</label>
                                                            <select
                                                                class="form-control select2 @error('id') border border-danger @enderror"
                                                                id="input_data" style="width: 100%;" name="id"
                                                                placeholder="User">
                                                                <option value=""> User </option>
                                                                <option value=""> Data </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div id="input_dataD" class="col p-2 p2 bg-gray"></div>
                                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                                        <div class="form-group">
                                                            <label>Permissions</label>
                                                            <select
                                                                class="select2 @error('permissions') border border-danger @enderror"
                                                                multiple="multiple" data-placeholder="Select Permissions"
                                                                style="width: 100%;" name="permissions[]">
                                                                @foreach ($permissions as $permission)
                                                                    <option>{{ $permission }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer">
                                                <button type="save" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </form>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection()

@push('scripts')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>
@endpush

@push('scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // $("#input_data").on('input', function(e) {
        $("#input_data").select2({
            placeholder: 'Select movie',

            console.log("On change working.");

            $("#input_data").empty();
            e.preventDefault();
            $("#input_data").empty();
            var input_data = $("#input_data").val();

            $.ajax({
                type: 'get',
                url: "{{ route('user_permission.create') }}",
                data: {
                    data: input_data
                },
                success: function(response) {
                    console.log("Response working.");
                    var data = $.parseJSON(response);
                    $.each(data, function(key, value) {
                        var sn_id = key += 1;
                        $("#input_data").last().append(
                            "<option value=" + value.id + " >" + value.id +
                            "|" + value
                            .first_name + " " + value.middle_name + " " + value
                            .last_name +
                            "|" + "</option>");
                        // $("#input_dataD").last().append(
                        //     "<div value=" + value.id + " >" + value.id + "|" + value
                        //     .first_name + " " + value.middle_name + " " + value.last_name +
                        //     "</div>");

                    });
                }
            });
        });
    </script>
@endpush
