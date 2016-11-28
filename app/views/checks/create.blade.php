@extends('layouts.erp')
@section('content')

<br><div class="row">
	<div class="col-lg-12">
  <h3>New Checkout</h3>

<hr>
</div>	
</div>


<div class="row">
	<div class="col-lg-5">

    
		
		 @if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
        @endif

		 <form method="POST" action="{{{ URL::to('checks') }}}" accept-charset="UTF-8">
   
    <fieldset>
        

         

         <div class="form-group">
            <label for="username">Item <span style="color:red">*</span> :</label>
            <select name="item_id" class="form-control" required>

                @foreach($items as $item)
                <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
            
        </div>

       

        <div class="form-group">
            <label for="username">Date Out <span style="color:red">*</span> :</label>
            <div class="right-inner-addon ">
            <i class="glyphicon glyphicon-calendar"></i>
            <input class="form-control datepicker21" placeholder="" readonly="readonly" type="text" name="date_out" id="date_out" value="{{{ Input::old('date_out') }}}" required>
        </div>


          <div class="form-group">
            <label for="username">Date Expected Back <span style="color:red">*</span> :</label>
             <div class="right-inner-addon ">
             <i class="glyphicon glyphicon-calendar"></i>
            <input class="form-control datepicker21" placeholder="" readonly="readonly" type="text" name="expected_date_back" id="expected_date_back" value="{{{ Input::old('expected_date_back') }}}" required>
        </div>


         <div class="form-group">
            <label for="username">Remarks:</label>
          <textarea name="remarks_out" class="form-control"></textarea>
        </div>


        

       
        <div class="form-actions form-group">
        
          <button type="submit" class="btn btn-primary btn-sm"> Checkout</button>
        </div>

    </fieldset>
</form>
		

  </div>

</div>

@stop