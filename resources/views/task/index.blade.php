<x-app-layout>
    <style>
        .tablecontainer {
            width: 85% !important;
            margin: 0 auto;
            margin-top: 4%;
        }

        .container {
            margin-top: 4%;
        }

        .col-lg-1>a {
            margin-top: 29%;
        }

        .row {
            margin-bottom: 1%;
        }
    </style>
    <x-slot name="header">
        {!! Form::open(['route' => 'exportTask', 'method' => 'POST', 'id' => 'dataForm']) !!}
        <div class="row">
            <div class="col-lg-2">
                <b>Project</b>
                {{-- @dd($projectName); --}}
                {!! Form::select('projectName', $projectName ?? [], null, [
                    'placeholder' => '-- Project --',
                    'id' => 'projectName',
                    'class' => 'form-control show-tick',
                    'data-live-search' => 'true',
                ]) !!}
            </div>
            <div class="col-lg-2">
                <b>Start Date</b>
                <input type="date" name="startDate" id="startDate" class="form-control">
            </div>
            <div class="col-lg-2">
                <b>End Date</b>
                <input type="date" name="endDate" id="endDate" class="form-control">
            </div>
            <div class="col-lg-1">

                <a href="" class="btn btn-dark" id="searchBtn">Search</a>
            </div>
            <div class="col-lg-1">
                <a href="" class="btn btn-dark">Reset</a>
            </div>
        </div>
        {!! Form::hidden('modifiedProjectName', null, ['id' => 'modifiedProjectName']) !!}

        <x-jet-button class="ml-4">
            <a href="{{ route('task.create') }}">Add Task</a>
        </x-jet-button>
        <x-jet-button class="ml-4">
            <input type="submit" value="EXPORT">
        </x-jet-button>
        {!! Form::close() !!}
    </x-slot>


    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            {{ Session::forget('success') }}
        </div>
    @endif



    <div class="row tablecontainer">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table taskTable" style="">
                    <thead>
                        <tr>
                            <th scope="col">Task Name</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        $('#dataForm').on('submit', function() {
            var index = $('#projectName').val();
            var desiredOption = '';
            if (index != '') {
                desiredOption = $('#projectName').find('option[value="' + index + '"]').html();
                $('#modifiedProjectName').val(desiredOption);
            }

            return true;
        });


        $('#searchBtn').on('click', function() {
            event.preventDefault();
            var index = $('#projectName').val();
            var desiredOption = '';
            if (index != '') {
                var desiredOption = $('#projectName').find('option[value="' + index + '"]').html();
            }
            $('.taskTable').DataTable().destroy();
            $('.taskTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                lengthChange: true,
                ajax: {
                    url: '{{ route('task.index') }}',
                    data: function(d) {
                        d.projectName = desiredOption;
                    },
                },
                columns: [{
                        data: 'taskName',
                        name: 'taskName'
                    },
                    {
                        data: 'projectName',
                        name: 'projectName'
                    },
                    {
                        data: 'startDate',
                        name: 'startDate'
                    },
                    {
                        data: 'endDate',
                        name: 'endDate'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });


        });


        $(document).ready(function() {

            $('.taskTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: '{{ route('task.index') }}',
                columns: [{
                        data: 'taskName',
                        name: 'taskName'
                    },
                    {
                        data: 'projectName',
                        name: 'projectName'
                    },
                    {
                        data: 'startDate',
                        name: 'startDate'
                    },
                    {
                        data: 'endDate',
                        name: 'endDate'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],

            });
        })

        $('body').on('click', '.deleteTask', function() {
            var taskId = $(this).data("id");
            var isConfirmed = confirm("Are you sure you want to delete this task?");
            if (isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('task.destroy', '') }}/" + taskId,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    </script>
</x-app-layout>
