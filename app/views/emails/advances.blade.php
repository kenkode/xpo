<p>
Hello, 
</p>

<p>Salary Advance Application for {{$name}}: </p>
<br>

<table>

<thead style="background-color:gray; color:white;">
	<th colspan="2">Application Details</th>
</thead>
<tbody>
	<tr>
		<td>Date cheque/Cash is needed:</td><td>{{$advance->date}}</td>
	</tr>
	<tr>
		<td>Name:</td><td>{{$name}}</td>
	</tr>
	<tr>
		<td>Department:</td><td>{{Department::getName($employee->department_id)}}</td>
	</tr>

	<tr>
		<td>Type</td><td>{{$advance->type}}</td>
	</tr>


	<tr>
		<td>Amount</td><td>Ksh. {{number_format($advance->amount,2)}}</td>
	</tr>

</tbody>
	
</table>




<br><br>
<p>Regards,</p>
<p>Xpose Limited.</p>
