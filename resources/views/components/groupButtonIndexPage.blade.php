 @props(['filterableFields', 'pluralLowercase', 'bulkUpdate', 'moduleTableName'])
 @php
     $filterable_fields = $filterableFields;
     $plural_lowercase = $pluralLowercase;
     $bulk_update_columns = json_decode($bulkUpdate, true);

 @endphp
 <div class="d-flex flex-wrap justify-content-between " style="align-items: start;max-width:660px; ">
     @if (!empty($bulk_update_columns))
         <div class="" id="bulk_update">
             <button type="button" class="rounded-0 btn btn-outline-success me-1" data-bs-toggle="modal"
                 data-bs-target="#bulk_update_modal">
                 <i class="bx bx-edit"></i>&nbsp;&nbsp;Bullk Update
             </button>
             <div id="bulk_update_modal" class="modal fade" role="dialog">
                 <div class="modal-dialog">

                     <!-- Modal content-->
                     <form id="bulk_form">
                         <div class="modal-content">
                             <div class="modal-header">

                                 <b>Bulk Update </b>

                             </div>
                             <div class="modal-body">

                                 <div style="">
                                     @foreach ($bulk_update_columns as $k => $val)
                                         <b
                                             style="font-weight: 600;font-size: 13px;">{{ properSingularName($val['label']) }}</b>
                                         @if (!isset($val['input_type']))
                                             <div class="form-group">
                                                 <select class="form-control" name="{{ $k }}">
                                                     <option value="">Select {{ $k }}</option>
                                                     @foreach ($val['data'] as $p)
                                                         <option value="{{ $p['id'] }}">

                                                             {{ $p['name'] }}
                                                         </option>
                                                     @endforeach

                                                 </select>

                                             </div>
                                         @else
                                             @if ($val['input_type'] == 'select')
                                                 <div class="form-group">
                                                     <select class="form-control" name="{{ $k }}">
                                                         <option value="">Select {{ $k }}</option>
                                                         @foreach ($val['data'] as $p)
                                                             <option value="{{ $p['id'] }}">

                                                                 {{ $p['name'] }}
                                                             </option>
                                                         @endforeach

                                                     </select>

                                                 </div>
                                             @elseif ($val['input_type'] == 'textarea')
                                                 <div class="form-group">
                                                     <textarea class="form-control" name="{{ $k }}">{{ $val['data'] }}</textarea>

                                                 </div>
                                             @elseif ($val['input_type'] == 'text')
                                                 <div class="form-group">
                                                     <input class="form-control" name="{{ $k }}"
                                                         value="{{ $val['data'] }}" />

                                                 </div>
                                             @endif
                                         @endif
                                     @endforeach
                                 </div>
                                 <div class="form-group mt-2">

                                 </div>

                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-primary rounded-0"
                                     onClick="multiSelectCheckBoxAction('{!! $moduleTableName !!}')">Submit</button>
                                 <button type="button" class="btn btn-danger rounded-0"
                                     data-bs-dismiss="modal">Close</button>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     @endif
     <div id="bulk_delete" style="margin-right:5px;">
         <button type="button" class="rounded-0 btn btn-outline-danger"
             onclick="bulkDelete('{!! $moduleTableName !!}')">
             <i class="bx bx-trash"></i>&nbsp;&nbsp;Trash
         </button>
     </div>
     <x-filter :data="$filterable_fields" />
 </div>
