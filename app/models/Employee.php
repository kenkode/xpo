<?php

class Employee extends Eloquent {

	

	public $table = "employee";
/*

	use \Traits\Encryptable;

	protected $encryptable = [

		
		'basic_pay',
		
		
	];

*/


	// Add your validation rules here
	public static $rules = [
		 'fname' => 'required',
		 'lname' => 'required',
		 'personal_file_number' => 'required|unique:employee',
		 'identity_number' => 'required|unique:employee',
		 'pay' => 'regex:/^[0-9]{1,3}(,[0-9]{3})*\.[0-9]+$/',
		 'email_office' => 'required|email|unique:employee',
		 'email_personal' => 'email|unique:employee',
		 'passport_number' => 'unique:employee',
		 'work_permit_number' => 'unique:employee',
		 'pin' => 'unique:employee',
		 'social_security_number' => 'unique:employee',
		 'hospital_insurance_number' => 'unique:employee',
		 'telephone_mobile' => 'unique:employee',
		 'swift_code' => 'unique:employee',
		 'bank_account_number' => 'unique:employee',
		 'bank_eft_code' => 'unique:employee',
         'branch_id' => 'required',
		 'department_id' => 'required',
		 'jgroup_id' => 'required',
		 'djoined' => 'required',
		 'kin_idno' => 'unique:employee',
		 'kin_phone' => 'unique:employee',
		 'kin_email' => 'email|unique:employee',
	];

    public static function rolesUpdate($id)
    {
        return array(
         'fname' => 'required',
		 'lname' => 'required',
		 'personal_file_number' => 'required|unique:employee,personal_file_number,' . $id,
		 'identity_number' => 'required|unique:employee,identity_number,' . $id,
		 'pay' => 'regex:/^[0-9]{1,3}(,[0-9]{3})*\.[0-9]+$/',
		 'email_office' => 'required|email|unique:employee,email_office,' . $id,
		 'email_personal' => 'email|unique:employee,email_personal,' . $id,
		 'passport_number' => 'unique:employee,passport_number,' . $id,
		 'work_permit_number' => 'unique:employee,work_permit_number,' . $id,
		 'pin' => 'unique:employee,pin,' . $id,
		 'social_security_number' => 'unique:employee,social_security_number,' . $id,
		 'hospital_insurance_number' => 'unique:employee,hospital_insurance_number,' . $id,
		 'telephone_mobile' => 'unique:employee,telephone_mobile,' . $id,
		 'swift_code' => 'unique:employee,swift_code,' . $id,
		 'bank_account_number' => 'unique:employee,bank_account_number,' . $id,
		 'bank_eft_code' => 'unique:employee,bank_eft_code,' . $id,
		 'branch_id' => 'required',
		 'department_id' => 'required',
		 'jgroup_id' => 'required',
		 'djoined' => 'required',
		 'kin_idno' => 'unique:employee,kin_idno,' . $id,
		 'kin_phone' => 'unique:employee,kin_phone,' . $id,
		 'kin_email' => 'email|unique:employee,kin_email,' . $id,
        );
    }

    public static $messages = array(
        'personal_file_number.required'=>'Please insert employee`s personal file number!',
        'personal_file_number.unique'=>'That personal file number already exists!',
        'fname.required'=>'Please insert employee`s first name!',
        'lname.required'=>'Please insert employee`s last name!',
        'identity_number.required'=>'Please insert employee`s identity number!',
        'identity_number.unique'=>'That identity number already exists!',
        'pay.regex'=>'Please insert a valid salary!',
        'email_office.required'=>'Please insert employee`s office email!',
        'email_office.email'=>'Please insert a valid employee`s office email!',
        'email_office.unique'=>'That employee`s office email already exists!',
        'email_personal.email'=>'Please insert a valid employee`s personal email!',
        'email_personal.unique'=>'That employee personal email already exists!',
        'passport_number.unique'=>'That passport number already exists!',
        'work_permit_number.unique'=>'That work permit number already exists!',
        'pin.unique'=>'That kra pin already exists!',
        'social_security_number.unique'=>'That nssf number already exists!',
        'hospital_insurance_number.unique'=>'That nhif number already exists!',
        'telephone_mobile.unique'=>'That mobile number already exists!',
        'swift_code.unique'=>'That swift code already exists!',
        'bank_account_number.unique'=>'That bank account number already exists!',
        'bank_eft_code.unique'=>'That bank eft code already exists!',
        'branch_id.required'=>'Please insert employee`s branch!',
        'department_id.required'=>'Please insert employee`s department!',
        'jgroup_id.required'=>'Please insert employee`s job group!',
        'djoined.required'=>'Please insert date employee joined the organization!',
        'kin_idno.unique'=>'That employee`s kin identity number already exists!',
        'kin_phone.unique'=>'That employee`s kin phone number exists!',
        'kin_email.email'=>'Please insert a valid employee`s kin email!',
        'kin_email.unique'=>'That employee`s kin email address already exists!',
    );

	// Don't forget to fill this array
	protected $fillable = [];


	public function branch(){

		return $this->belongsTo('Branch');
	}

	public function department(){

		return $this->belongsTo('Department');
	}

    public function jobgroup(){

		return $this->belongsTo('Jobgroup');
	}

	public function supervisor(){

		return $this->hasMany('Supervisor');
	}


	public function allowances(){
		return $this->belongsTo('EAllowances');
	}
	public function reliefs(){
		return $this->belongsTo('ERelief');
	}
	public function benefits(){
		return $this->belongsTo('Earnings');
	}

	public function Leaveapplications(){

		return $this->hasMany('Leaveapplication');
	}

    public function occurences(){

		return $this->hasMany('Occurence');
	}

	public function emergencycontact(){

		return $this->hasMany('Emergencycontact');
	}

    public function education(){

		return $this->hasMany('Education');
	}


	public static function getEmployeeName($id){

		$employee = Employee::findOrFail($id);
		$name = $employee->personal_file_number.'-'.$employee->first_name.' '.$employee->last_name;

		return $name;
	}

	public static function getName($id){

		$employee = Employee::findOrFail($id);
		$name = $employee->first_name.' '.$employee->last_name;

		return $name;
	}

	public static function getDept($id){

		$employee = Employee::findOrFail($id);
		$department = Department::findOrFail($employee->department_id);

		return $department->department_name;
	}


    public static function getActiveEmployee(){

		$employee = DB::table('employee')->where('in_employment', '=', 'Y')->get();

		return $employee;
	}

	public static function getDeactiveEmployee(){

		$employee = DB::table('employee')->where('in_employment', '=', 'N')->get();

		return $employee;
	}
	
}
