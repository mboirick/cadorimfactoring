<div class="row">
  @php
    $index = 0;
  @endphp
  @foreach ($items as $item)
    <div class="col-md-2">
      <div class="form-group">
          @php
            $stringFormat =  strtolower(str_replace(' ', '', $item));
          @endphp
           <div class="col-md-12">
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" autocomplete="off"  value="{{isset($oldVals) ? $oldVals[$index] : ''}}" name="<?=$stringFormat?>" class="form-control pull-right" id="<?=$stringFormat?>" placeholder="{{$item}}" required>
            </div>
        </div>
      </div>
    </div>
  @php
    $index++;
  @endphp
  @endforeach

    <div class="col-md-3">
      <div class="form-group">
         
          <label  class="col-sm-6 control-label" align="right">Client</label>
          <div class="col-sm-6">
            <select name="id_client" id="id_client" required>
            <option value="">selectionne un Client</option>
             
            @foreach ($clients as $client)
              <option value="{{$client->id_client}}">{{$client->firstname}}</option>
             
              @endforeach
              <option value="Transfert">Transfert</option>
            </select>
            
          </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
          
          <label  class="col-sm-6 control-label"  align="right" >Operation</label>
          <div class="col-sm-6">
            <select name="type" id="type">
              <option value="e">Toute operations</option>
              <option value="depot">Dépot</option>
              <option value="retrait">Rétrait</option>
            </select>
            
          </div>
      </div>
      
    </div>

    <div class="col-md-2">     
<!--           
      <button type="submit"  class="btn btn-warning" name="search" value="recherche">
      <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
      Search
    </button>
   |
    <button type="submit" class="btn btn-primary" name="search" value="excel">
      <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
      To Excel
    </button> -->

   
    </div>


   
</div>