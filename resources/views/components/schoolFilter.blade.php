@props(['classes', 'sections'])
<form>
<div class="row">

    <div class="col-md-4 mb-2">

        <lable class="form-label">Select Class</label>
            <select class="form-select" name="class_id">
                <option value="">Select Class</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
    </div>
    <div class="col-md-4 mb-2">
        <lable class="form-label">Select Section</label>
            <select class="form-select" name="section_id">
                <option value="">Select Section</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
    </div>
    <div class="col-md-4 mt-3">
        <button class="btn btn-primary" type="submit">Search Student</button>
    </div>
</div>
</form>