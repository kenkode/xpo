@extends('layouts.payroll')
@section('content')

@if (Session::get('notice'))
  <div class="alert alert-info">{{ Session::get('notice') }}</div>
@endif
    <br><br>
  <div class="row">
    
      <div class="col-lg-12">
        <h2>Salary Advance Application</h2>
      </div>
    
  </div>

  <div class="row">
    
    <div class="col-lg-12">
      <hr>

    </div>
  </div>


<div class="row">
  
  

  <div class="col-lg-6">



      <table class="table table-condensed table-bordered">

          <tr>
            <td>Name</td>
            <td>{{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}}</td>
          </tr>

           <tr>
            <td>Department</td>
            <td>{{Department::getName($employee->department_id)}}</td>
          </tr>

           <tr>
            <td>Date Cheque/Cash is needed</td>
            <td>{{$advance->date}}</td>
          </tr>

           <tr>
            <td>Type</td>
            <td>{{$advance->type}}</td>
          </tr>

          <tr>
            <td>Amount</td>
            <td>Ksh. {{number_format($advance->amount,2)}}</td>
          </tr>

        
      </table>

  <div align="right" class="col-lg-12">
    <a href="{{URL::to('memberadvances/memrej/'.$advance->id)}}" class="btn btn-danger">Reject</a>
    <br><br>
  </div>
  

  </div>  

</div>


@stop