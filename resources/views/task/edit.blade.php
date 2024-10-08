<x-app-layout>
    <style>
        .error {
            color: red;
        }
      form{
        width: 400px;
    margin: 0 auto;
    margin-top: 1%;
    margin-bottom: 2%;
      }
    </style>
    <x-slot name="header">
        <x-jet-button class="ml-4">
            <a href="{{ route('task.index') }}">View All</a>
        </x-jet-button>
    </x-slot>
    <form class="addTask" id="addTask" action="{{ route('task.update',$data->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Task</label>
            <input type="text" class="form-control" id="taskName" value="{{ $data->taskName }}" name="taskName" placeholder="Enter Task name"
                aria-describedby="emailHelp">

        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Project</label>
            <input type="text" name="projectName" value="{{ $data->projectName }}" placeholder="Enter Project name or Learning" class="form-control"
                id="projectName">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Start Date</label>
            <input type="date" name="startDate" value="{{$data->startDate}}" class="form-control" id="startDate">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">End Date</label>
            <input type="date" name="endDate" value="{{$data->endDate}}" class="form-control" id="endDate">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            {{ Session::forget('success') }}
        </div>
    @endif

    <script>
        $(document).ready(function(e) {

            $('#addTask').validate({
                rules: {
                    taskName: {
                        required: true,
                    },
                    projectName: {
                        required: true,
                    },

                },
                messages: {

                    taskName: {
                        required: 'Please provide Task name',

                    },

                    projectName: {
                        required: 'Please provide Project name e.g SSPA or Enter "Learning"',
                    },
                },
                submitHandler: function(form) {
                    // Form is valid, you can submit it here
                    form.submit();
                }
            });
        });
    </script>

</x-app-layout>
