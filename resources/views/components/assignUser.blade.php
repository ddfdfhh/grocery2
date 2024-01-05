@props(['users'])
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
    Assign User
</button>

<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Assign To User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <select class="form-control" id="assignUserSelectid" multiple>
                    <option value="">Select User</option>
                    @foreach ($users as $user)
                        <option value="user_id__{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onClick="assignRowsToSomeUser('assignUserSelectid','products','user_id')">Submit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
