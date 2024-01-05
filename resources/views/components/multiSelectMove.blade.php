@props(['leftValues','rightKey','leftIdKeyName'])
<h6>Assign {{ucwords($rightKey)}}</h6>
  <div class="row">
        <div class="col-md-6">
            <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
            @foreach($leftValues as $item)
                <option value="{{$item->{$leftIdKeyName} }}">{{$item->name}}</option>
               @endforeach
            </select>
        </div>

        <div class="col-md-2 d-flex flex-column align-content-between">
            <button type="button" id="multiselect_rightAll" class="btn btn-info"><i class="fa fa-forward"></i></button>
            <button type="button" id="multiselect_rightSelected" class="btn btn-primary"><i
                    class="fa fa-chevron-right"></i></button>
            <button type="button" id="multiselect_leftSelected" class="btn btn-success"><i
                    class="fa fa-chevron-left"></i></button>
            <button type="button" id="multiselect_leftAll" class="btn btn-warning"><i
                    class="fa fa-backward"></i></button>
        </div>

        <div class="col-md-4">
            <select name="{$rightKey}[]" id="multiselect_to" class="form-control" size="8"
                multiple="multiple"></select>
        </div>
    </div>