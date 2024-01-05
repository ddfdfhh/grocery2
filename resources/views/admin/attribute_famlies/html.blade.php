@foreach($attributes as $at)
<div class="row mb-4" id="row-0">
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Attribute</label>
            <select class="form-control no-select2" name="attibute[]" multiple>
              
                    <option value="{{ $at->id }}" selected>{{ $at->name }}</option>
              
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="form-label">Value</label>
            <div>
                <input  class="form-control attribute_values" name="value-{{$at->id}}" data-role="tagsinput" />
            </div>
        </div>

    </div>

</div>
@endforeach