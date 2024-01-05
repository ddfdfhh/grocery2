@props(['searchableFields'])
@php 
$fields=$searchableFields;
@endphp

   <div class="input-group" style="max-width:313px;float:right;padding-top:5px;padding-bottom:5px;">
              <button type="button" class="rounded-0 btn btn-primary dropdown-toggle dropdown-toggle-split"  data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bx bx-search-alt" style="margin-top:-3px"></i>  <span class="">Search By&nbsp;&nbsp;&nbsp;   </span>
              </button>
              <ul class="dropdown-menu">
                @foreach($fields as $r)
                        <li>
                            <div class="radio dropdown-item">
                                    <label>
                                        <input id="search_by" onchange="setSearchBy(this.value)" type="radio" name="search_by" @if($loop->first) checked @endif value="{{$r['name']}}">
                                    &nbsp;&nbsp;{{$r['label']}}
                                </label>
                            </div>
                        </li>
                   @endforeach
              </ul>
              <input type="text" id="search" class="rounded-0 form-control" placeholder="Type to search" aria-label="Text input with segmented dropdown button">
            </div>



