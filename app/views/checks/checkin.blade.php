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

		 <form method="POST" action="{{{ URL::to('checks/checkin/'.$check->id) }}}" accept-charset="UTF-8">
   
    <fieldset>
        

         

        

        <div class="form-group">
            <label for="username">Date In <span style="color:red">*</span> :</label>
            <div class="right-inner-addon ">
            <i class="glyphicon glyphicon-calendar"></i>
            <input class="form-control datepicker21" readonly="readonly" placeholder="" type="text" name="date_in" id="date_in" value="" required>
        </div>


         <div class="form-group">
            <label for="username">Condition <span style="color:red">*</span> :</label>
            <select name="condition_back" class="form-control">
              <option value="good">Good</option>
              <option value="faulty">Faulty</option>
            </select>
        </div>


        
         <div class="form-group">
          <label for="username">Remarks:</label>
          <textarea name="remarks_in" class="form-control"></textarea>
        </div>


        

       
        <div class="form-actions form-group">
        
          <button type="submit" class="btn btn-primary btn-sm"> Checkin</button>
        </div>

    </fieldset>
</form>
		

  </div>

</div>

@stop