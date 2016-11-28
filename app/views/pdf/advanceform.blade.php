<?php


function asMoney($value) {
  return number_format($value, 2);
}

?>
<html >



<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style type="text/css">

table {
  max-width: 100%;
  background-color: transparent;
}
th {
  text-align: left;
}
.table {
  width: 100%;
  margin-bottom: 2px;
}
hr {
  margin-top: 1px;
  margin-bottom: 2px;
  border: 0;
  border-top: 2px dotted #eee;
}

body {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 12px;
  line-height: 1.428571429;
  color: #333;
  background-color: #fff;
}



 @page { margin: 170px 30px; }
 .header { position: fixed; left: 0px; top: -150px; right: 0px; height: 150px;  text-align: center; }
 .content {margin-top: -100px; margin-bottom: -150px}
 .footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 50px;  }
 .footer .page:after { content: counter(page, upper-roman); }


   #underline
{
    margin-top:0;
    margin-left:0;
    width:100%;
    border-top: 1px dotted #000;
}

#s {    
    border-bottom: 1px solid #000;
    text-decoration: none;
    width:100%;
}


label {
  display: block;
  padding-left: 15px;
  margin-left: 15px;
  text-indent: -15px;
}
input {
  width: 13px;
  height: 13px;
  padding: 0;
  margin-left:20px;
  vertical-align: bottom;
  position: relative;
  top: -1px;
  *overflow: hidden;
}


</style>

</head>

<body>

  <div class="header">
     <table >

      <tr>


       
        <td style="width:150px">

            <img src="{{public_path().'/uploads/logos/'.$organization->logo}}" alt="logo" width="80%">

    
        </td>

        <td>
        <strong>
          {{ strtoupper($organization->name)}}<br>
          </strong>
          {{ $organization->phone}} |
          {{ $organization->email}} |
          {{ $organization->website}}<br>
          {{ $organization->address}}
       

        </td>
        

      </tr>


      <tr>

        <hr>
      </tr>



    </table>
   </div>

<br>

<div class="footer">
     <p class="page">Page <?php $PAGE_NUM ?></p>
   </div>
<?php $name = "";?>
@if($employee->middle_name=="" || $employee->middle_name==null)
<?php $name=$employee->first_name.' '.$employee->last_name;?>
@else
<?php $name=$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name;?>
@endif

	<div class="content" style='margin-top:0px;'>

<div align="center"><strong><u>SALARY ADVANCE FORM </u></strong></div><br>
<div style="border:1px solid #000;padding:1px 5px;">
    <ul >
        <li>Please submit to Human Resource Information services (HR Records) 5 working days prior to date needed to allow for processing</li>
        <li>Note: Only 2 advances are permitted per fiscal year.</li>
    </ul>
</div>
<br><br>

<p>Date cheque/Cash is needed:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>{{$advance->date}}</u></p>

<p>Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>{{$name}}</u></p>

<p>Department:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>{{Department::getName($employee->department_id)}}</u></p>

<p>This is to request issuance of an advance on my next Salary</p>

<div style="margin-left:-15px;"class="checkbox">
<label>
<span>The amount I am requesting is</span>
<input type="checkbox" value="{{{ $advance->type }}}"<?= ($advance->type=='Full paycheque')?'checked="checked"':''; ?>>Full paycheque<input type="checkbox" value="{{{ $advance->type }}}"<?= ($advance->type=='Fixed')?'checked="checked"':''; ?>>Fixed Ksh <u>{{number_format($advance->amount,2)}}</u>
</label>
</div>   

<p>Employee Signature &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
@if($employee->signature!=null || $employee->signature != '')
  <img src="{{ asset('public/uploads/employees/signature/'.$employee->signature)}}" width="80px" alt="">
@else
_______________________________
@endif
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:&nbsp;&nbsp;&nbsp;<u>{{date('d-m-Y')}}</u></p>
<br><br>

   
</div>


</body>

</html>



