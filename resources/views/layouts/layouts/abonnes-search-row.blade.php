<div class="row">
  @php
    $index = 0;
  @endphp
  @foreach ($items as $item)
    <div class="col-md-3">
      <div class="form-group">
          @php
            $stringFormat =  strtolower(str_replace(' ', '', $item));
          @endphp
          <label for="input<?=$stringFormat?>" class="col-sm-3 control-label">{{$item}}</label>
          <div class="col-sm-9">
            <input value="{{isset($oldVals) ? $oldVals[$index] : ''}}" type="text" class="form-control" name="<?=$stringFormat?>" id="input<?=$stringFormat?>" placeholder="{{$item}}">
          </div>
      </div>
    </div>
  @php
    $index++;
  @endphp
  @endforeach
  


<div class="col-md-3">
      <div class="form-group">  
          
          <label  class="col-sm-3 control-label">Statut KYC</label>
          <div class="col-sm-9">
            <select name="statut_kyc" id="statut_kyc">
              <option value="">Toute</option>
              <option value="0" class="btn btn-warning" >En attente</option>
              <option value="1" class="btn btn-success">Approuvé</option>
              <option value="2" class="btn btn-danger">Rejeté</option>
              <option value="3" class="btn" style="background: #000; color: #fff">à vérifier</option>
            </select>
            
          </div>
      </div>
    </div>
</div>
</div>