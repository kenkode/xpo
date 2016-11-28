@extends('layouts.main')
@section('content')

<style type="text/css" media="screen">
  .quicklink{
      text-align: center;
  }

  .quicklink div{
      font-weight: 400;
  }

  .quicklink a{
      width: 100%;
      padding: 15px 5px;
      color: #FFF;
      transition: all linear 0.25s;
  }

  .quicklink a:hover{
      color: #FFF;
      transform: translateY(-5px);
      box-shadow: 0px 1px 2px rgba(0,0,0,0.3);
      filter: brightness(90%);
  }

</style>

<br><br>

@if (Session::get('notice'))
      <div class="alert alert-info">{{ Session::get('notice') }}</div>
        @endif
    
        <div class="row">
          <div class="col-md-2 quicklink">
            <a class="btn btn-default btn-icon input-block-level" href="{{ URL::to('employees')}}" style="background: #3498DB">
              <i class="fa fa-users fa-2x"></i>
              <div>Manage Employees</div>
              
            </a>
          </div>

          <div class="col-md-2 quicklink">
            <a class="btn btn-default btn-icon input-block-level" href="{{ URL::to('payrollmgmt')}}" style="background: #2ECC71">
              <i class="fa fa-credit-card fa-2x"></i>
              <div>Manage Payroll</div>
              
            </a>
          </div>


          <div class="col-md-2 quicklink">
            <a class="btn btn-default btn-icon input-block-level" href="{{URL::to('leavemgmt')}}" style="background: #9B59B6">
              <i class="fa fa-tasks fa-2x"></i>
              <div>Manage Leave</div>
              
            </a>
          </div>

          <div class="col-md-2 quicklink">
            <a class="btn btn-default btn-icon input-block-level" href="{{ URL::to('accounts')}}" style="background: #F39C12">
              <i class="fa fa-list fa-2x"></i>
              <div>Manage Accounting</div>
              
            </a>
          </div>
          
          <div class="col-md-2 quicklink">
            <a class="btn btn-default btn-icon input-block-level" href="{{ URL::to('payrollReports')}}" style="background: #34495E">
              <i class="fa fa-file fa-2x"></i>
              <div>Manage Reports</div>
              
            </a>
          </div>

          
        </div>
                  



<div class="row">
  
  <div class="col-lg-12">
    <hr>

  </div>
</div>


<div class="row">
  


  <div class="col-lg-12">


   <div class="panel panel-default">
      <div class="panel-heading">
          <a class="btn btn-info btn-sm" href="{{ URL::to('employees/create')}}">new employee</a>
        </div>
        <div class="panel-body">

      <table id="users" class="table table-condensed table-bordered table-responsive table-hover">


      <thead>

        <th>#</th>
        <th>Personal File Number</th>
        <th>Employee Name</th>
        <th>Employee Branch</th>
        <th>Employee Department</th>

        <th>Action</th>

      </thead>
      <tbody>

        <?php $i = 1; ?>
        @foreach($employees as $employee)

        <tr>

          <td> {{ $i }}</td>
          <td>{{ $employee->personal_file_number }}</td>
          <td>{{ $employee->first_name.' '.$employee->last_name}}</td>
          <?php if( $employee->branch_id!='0'){ ?>
          <td>{{ Branch::getName($employee->branch_id) }}</td>
          <?php }else{?>
          <td></td>
          <?php } ?>
           <?php if( $employee->department_id!='0'){ ?>
          <td>{{ Department::getName($employee->department_id) }}</td>
          <?php }else{?>
          <td></td>
          <?php } ?>
                   <td>

                  <div class="btn-group">
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    Action <span class="caret"></span>
                  </button>
          
                  <ul class="dropdown-menu" role="menu">

                    <li><a href="{{URL::to('employees/view/'.$employee->id)}}">View</a></li>

                    <li><a href="{{URL::to('employees/edit/'.$employee->id)}}">Update</a></li>
                   
                    <li><a href="{{URL::to('employees/deactivate/'.$employee->id)}}" onclick="return (confirm('Are you sure you want to deactivate this employee?'))">Deactivate</a></li>
                    
                  </ul>
              </div>

                    </td>
        </tr>

        <?php $i++; ?>
        @endforeach


      </tbody>


    </table>
</div>
</div>

  </div>  


<div class="row">

  <div class="col-lg-12">
    <hr>
  </div>  

  

  
</div>
@stop
