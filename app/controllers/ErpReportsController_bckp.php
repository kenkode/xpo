<?php

class ErpReportsController extends \BaseController {


	public function clients(){

		$clients = Client::all();

		$organization = Organization::find(1);

		$pdf = PDF::loadView('erpreports.clientsReport', compact('clients', 'organization'))->setPaper('a4')->setOrientation('potrait');
 	
		return $pdf->stream('Client List.pdf');
		
	}

    public function items(){

        $items = Item::all();

        $organization = Organization::find(1);

        $pdf = PDF::loadView('erpreports.itemsReport', compact('items', 'organization'))->setPaper('a4')->setOrientation('potrait');
    
        return $pdf->stream('Item List.pdf');
        
    }

    public function expenses(){

        $expenses = Expense::all();

        $organization = Organization::find(1);

        $pdf = PDF::loadView('erpreports.expensesReport', compact('expenses', 'organization'))->setPaper('a4')->setOrientation('potrait');
    
        return $pdf->stream('Expense List.pdf');
        
    }

    public function paymentmethods(){

        $paymentmethods = Paymentmethod::all();

        $organization = Organization::find(1);

        $pdf = PDF::loadView('erpreports.paymentmethodsReport', compact('paymentmethods', 'organization'))->setPaper('a4')->setOrientation('potrait');
    
        return $pdf->stream('Payment Method List.pdf');
        
    }

    public function payments(){

        $payments = Payment::all();

        $erporders = Erporder::all();

        $erporderitems = Erporderitem::all();

        $organization = Organization::find(1);

        $pdf = PDF::loadView('erpreports.paymentsReport', compact('payments','erporders', 'erporderitems', 'organization'))->setPaper('a4')->setOrientation('potrait');
    
        return $pdf->stream('Payment List.pdf');
        
    }




    public function locations(){

        $locations = Location::all();


        $organization = Organization::find(1);

        $pdf = PDF::loadView('erpreports.locationsReport', compact('locations', 'organization'))->setPaper('a4')->setOrientation('potrait');
    
        return $pdf->stream('Stores List.pdf');
        
    }



    public function stock(){

        $items = Item::all();


        $organization = Organization::find(1);

        $pdf = PDF::loadView('erpreports.stockReport', compact('items', 'organization'))->setPaper('a4')->setOrientation('potrait');
    
        return $pdf->stream('Stock Report.pdf');
        
    }

    /**
     * GENERATE BANK RECONCILIATION REPORT
     */
    public function displayRecOptions(){
        $bankAccounts = DB::table('bank_accounts')
                        ->get();

        $bookAccounts = DB::table('accounts')
                        ->where('category', 'ASSET')
                        ->get();

        return View::make('erpreports.recOptions', compact('bankAccounts','bookAccounts'));
    }

    public function showRecReport(){
        $bankAcID = Input::get('bank_account');
        $bookAcID = Input::get('book_account');
        $recMonth = Input::get('rec_month'); 

        //get statement id
        $bnkStmtID = DB::table('bank_statements')
                    ->where('stmt_month', $recMonth)
                    ->pluck('id');

        $bnkStmtBal = DB::table('bank_statements')
                            ->where('bank_account_id', $bankAcID)
                            ->where('stmt_month', $recMonth)
                            ->select('bal_bd')
                            ->first();

        $acTransaction = DB::table('account_transactions')
                            ->where('status', '=', 'RECONCILED')
                            ->where('bank_statement_id', $bnkStmtID)
                            ->whereMonth('transaction_date', '=', substr($recMonth, 0, 2))
                            ->whereYear('transaction_date', '=', substr($recMonth, 3, 6))
                            ->select('id','account_credited','account_debited','transaction_amount')
                            ->get();

        $bkTotal = 0;
        foreach($acTransaction as $acnt){
            if($acnt->account_debited == $bookAcID){
                $bkTotal += $acnt->transaction_amount;
            } else if($acnt->account_credited == $bookAcID){
                $bkTotal -= $acnt->transaction_amount;
            }
        }

        $additions = DB::table('account_transactions')
                            ->where('status', '=', 'RECONCILED')
                            ->where('bank_statement_id', $bnkStmtID)
                            ->whereMonth('transaction_date', '<>', substr($recMonth, 0, 2))
                            ->whereYear('transaction_date', '=', substr($recMonth, 3, 6))
                            ->select('id','description','account_credited','account_debited','transaction_amount')
                            ->get();

        $add = [];
        $less = [];
        foreach($additions as $additions){
            if($additions->account_debited == $bookAcID){
                array_push($add, $additions);
            } else if($additions->account_credited == $bookAcID){
                array_push($less, $additions);
            }
        }

        $organization = Organization::find(1);

        $pdf = PDF::loadView('erpreports.bankReconciliationReport', compact('recMonth','organization','bnkStmtBal','bkTotal','add','less'))->setPaper('a4')->setOrientation('potrait');
        return $pdf->stream('Reconciliation Reports');
        /*if(count($bnkStmtBal) == 0 || $bkTotal == 0 || count($additions) == 0 ){
            return "Error";
            //return View::make('erpreports.bankReconciliationReport')->with('error','Cannot generate report for this Reconciliation! Please check paremeters!');
        } else{
            return "Success";*/
            return View::make('erpreports.bankReconciliationReport', compact('recMonth','organization','bnkStmtBal','bkTotal','add','less'));
        //}
    }

}
