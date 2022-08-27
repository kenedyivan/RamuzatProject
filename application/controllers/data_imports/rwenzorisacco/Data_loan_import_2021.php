<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Data_loan_import
 *
 * @author Eric
 */
class Data_loan_import_2021 extends CI_Controller {

    public function __construct() {
        parent :: __construct();
        $this->load->library("session");
        $this->load->library("helpers");
        if (empty($this->session->userdata('id'))) {
            redirect('welcome');
        }
        ini_set('memory_limit', '200M');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('max_input_time', 3600);
        ini_set('max_execution_time', 3600);
        $this->load->model('client_loan_model');
        $this->load->model('loan_product_model');
        $this->load->model('loan_installment_payment_model');
        $this->load->model('savings_account_model');
        $this->load->model('client_loan_monthly_income_model');
        $this->load->model('client_loan_monthly_expense_model');
        $this->load->model('loan_attached_saving_accounts_model');
        $this->load->model('payment_details_model');
        $this->load->model('repayment_schedule_model');
        $this->load->model('loan_state_model');
        $this->load->model("applied_loan_fee_model");
        $this->load->model("accounts_model");
        $this->load->model("Data_import_model");
        $this->schedule_id=0;
        $this->client_loan_no='LN000001';

        $this->debit_or_credit1 =  $this->accounts_model->get_normal_side(1,true);//loan_receivable_account_id
        $this->debit_or_credit3= $this->accounts_model->get_normal_side(27);//linked_account_id
        $this->debit_or_credit4 = $this->accounts_model->get_normal_side(7,true);//interest_receivable_account_id

    }

    public function index() {
        $this->db->trans_start();
        $folder = "data_extract".DIRECTORY_SEPARATOR."rwenzorisacco".DIRECTORY_SEPARATOR;
        $file_name = "rwenzori_loans_data_2021.csv";
        $file_path = FCPATH . $folder . $file_name;
        $feedback = $this->run_updates($file_path);
        $this->db->trans_complete();
        
        echo json_encode($feedback);
    }

    private function run_updates($file_path) {
        $handle = fopen($file_path, "r");
        $total_loans = $count = 0;
        $field_names = $data_array = [];
        $client_loans= [];
        $feedback = ["success" => false, "message" => "File Could not be opened"];
        if ($handle) {
            while (($data = fgetcsv($handle, 30048576, ",")) !== FALSE) {
                $data1 = $this->security->xss_clean($data);
                if ($count == 0) {//the row with the field_names
                    $field_names = $data1;
                    if ($field_names[2] != "Member ID") {
                        $feedback['message'] = "Please ensure that the first cell (A1) contains the key Aocount No ID";
                        fclose($handle);
                        return $feedback;
                    }
                } else {
                    $total_loans = $total_loans + $this->insert_loan_data($data1);
                    $this->client_loan_no = ++$this->client_loan_no;
                }
                $count++;
            }
            fclose($handle);

            if (is_numeric($total_loans)) {
                $feedback["success"] = true;
                $feedback["message"] = "Update done\n $total_loans records updated";
            }
        }
        return $feedback;
    }

    private function insert_loan_data($loan_data) {
       //if($loan_data[1]==1 && ($loan_data[0] !="" && $loan_data[0]!=NULL)){
        //$preferred_payment = ["CASH" => 1, "BANK" => 2, "MOBILE MONEY" => 3];
       // $periods = ["DAYS" => 1, "WEEKS" => 2, "MONTHS" => 3];
        $topUp = 0;
        $date_created = time();
       // $action_date = $this->helpers->extract_date_time($loan_data[9],"Y-m-d");
        $amount= $loan_data[6];
        $member_id = $loan_data[2];
        $loan_product_id =1;
        $repayment_frequency =$loan_data[8];
       // repayment date from m/d/y to yyyy-mm-dd.
        $repaymentDateCsv = $loan_data[5];
        $repaymentDateCsvExploded = explode("/",$repaymentDateCsv);
        $repaymentDate =$repaymentDateCsvExploded[2]."-".$repaymentDateCsvExploded[1]."-".$repaymentDateCsvExploded[0];
        $action_date   =  $repaymentDate;
        $repaymentDate2 =$action_date;
 
        $interest_type =2;
        $interest_rate = $loan_data[9];
        $repayment_made_every=1;
        $installments= 1;

        try {
            if ($loan_data[6] != "" && $loan_data[6] != 'NULL' && $loan_data[4]==1) {
                $single_row = [
                    "member_id" =>  $member_id,
                    "loan_no" => $loan_data[3],
                    "credit_officer_id" => 2, 
                    "loan_product_id" => $loan_product_id,
                    "topup_application" => 0,
                    "requested_amount" => $amount,
                    'disbursed_amount' => $amount,
                    "application_date" => $action_date,
                    "source_fund_account_id" => 40, 
                    "disbursement_date" => $action_date,
                    "suggested_disbursement_date" => $action_date,
                    "interest_rate" => $interest_rate,
                    "offset_period" => 0,
                    "offset_made_every" => 1,
                    "repayment_frequency" => $repayment_frequency,
                    "repayment_made_every" => $repayment_made_every,
                    "installments" => $installments,
                    "link_to_deposit_account" => 1,
                    "comment" => 'Loan imported from the old system/Excel',
                    "amount_approved" => $amount,
                    "approval_date" => $action_date,
                    "approved_installments" => $installments,
                    "approved_repayment_frequency" => $repayment_frequency,
                    "approved_repayment_made_every" => $repayment_made_every,
                    "approved_by" => 1,
                    "approval_note" => 'Data imported from excel',
                    "loan_purpose" => 'N/L',
                    "preferred_payment_id" => 1,
                    "branch_id"=>1,
                    'date_created' => $date_created,
                    "created_by" =>2
                ];
                //insert into the client table
                $inserted_loan_id=$this->client_loan_model->set2($single_row);

                if ($inserted_loan_id) {
                    //Record the state of the loan
                    $loan_state_details = [
                        "client_loan_id" => $inserted_loan_id,
                        "state_id" => 7,
                        "comment" => 'Loan Disbursed - Data imported',
                        "action_date" => $action_date,
                        "date_created" => $date_created,
                        "created_by" => 2
                    ];
                    
                    $this->loan_state_model->set2($loan_state_details);
                    //Schedule generation
                    $repayment_made_every=3;
                    $next_pay_date = date('Y-m-d',strtotime('+'.$repayment_frequency.' month', strtotime($repaymentDate2)));
      
                    /*
                    $r=$interest_rate_per_annum=$interest_rate_per_installment=($interest_rate)/100; 
                    $l=$length_of_a_period=($repayment_frequency/12);
                    $i=$interest_rate_per_period=($r*$l);
                    $n=$installments; 
                    $p=$amount;
                 
                    $interest_sum=0; $principal_amount=0;
                    $reducing_principle = $p;
                    */
                    $repayment_date= $next_pay_date ;
                    $index_key=2;$loan_payment_id=0;
                    // based on reducing 
                   
                    $repayment_schedule_array =[]; $loan_payment_data =[]; $loan_payment_journal_data =[];
                    $interest_data =[];
                    $debit_or_credit3 = $this->accounts_model->get_normal_side(6);
                    $debit_or_credit4 = $this->accounts_model->get_normal_side(7);

                    for ($y=1; $y <= $installments; $y++) { 
                        $index_key+=2;
                        $this->schedule_id = ++$this->schedule_id;
                       /* if ($interest_type ==2) {
                            $interest_amount=($i*$p);
                            $EMI =($i*$p)/ (1- pow((1+$i),-$n));
                            $principal_amount= ($EMI-$interest_amount);
    
                            if ($loan_data[6] !='NULL' && $loan_data[6] !=0) {
                                $interest_amount +=$loan_data[6];
                            }
                        } */
                          $principal_amount = $loan_data[6];
                          $interest_amount = $loan_data[10];

                        $repayment_schedule_array[] = [
                            "repayment_date" => $repayment_date,
                            "interest_amount" => $interest_amount,
                            "principal_amount" => $principal_amount,
                            "client_loan_id" => $inserted_loan_id,
                            "grace_period_on" => 1,
                            "grace_period_after" => 3,
                            "installment_number" => $y,
                            "interest_rate" => $interest_rate,
                            "repayment_frequency" => 1,
                            "repayment_made_every" => 3,
                            "comment" => 'Loan schedule imported',
                            "payment_status" => 4,
                            'actual_payment_date' => '0000-00-00',
                            "status_id" => 1,
                            "date_created" => $date_created,
                            "created_by" => 1
                        ];
                        $interest_data[$index_key-1]=[
                            'reference_no' => $this->client_loan_no,
                            'reference_id' => $this->schedule_id,
                            'transaction_date' => $action_date,
                            $debit_or_credit3=> $interest_amount,
                            'narrative'=> strtoupper("Interest on Loan Disbursed on ".$action_date),
                            'account_id'=> 6,
                            'status_id'=> 1
                        ];

                        $interest_data[$index_key] =  [
                            'reference_no' => $this->client_loan_no,
                            'reference_id' => $this->schedule_id,
                            'transaction_date' => $action_date,
                            $debit_or_credit4=> $interest_amount,
                            'narrative'=> strtoupper("Interest on Loan Disbursed on ".$action_date),
                            'account_id'=> 7,
                            'status_id'=> 1
                        ];
 
                    }//end of the loop

                    $this->repayment_schedule_model->set2($repayment_schedule_array);
                    $sent_data['loan']=[
                        'journal_type_id'=> 4,
                        'ref_no' => $this->client_loan_no,
                        'ref_id' => $inserted_loan_id,
                        'description' => 'Loan imported from Excel',
                        'action_date' => $action_date,
                        'principal_amount'=>$amount,
                        'interest_amount' => $interest_amount,
                        'source_fund_account_id'=>40,
                        'loan_receivable_account_id'=>1,
                        'interest_income_account_id' =>6,
                        'interest_receivable_account_id' =>7
                    ];
                    $this->do_journal($sent_data['loan'],true,$interest_data);  
                    
                    // Record payments
                   // $this->insert_loan_payment_transaction($loan_data, $inserted_loan_id);

                }
                return 1;
            }
        } catch (Exception $e) {
            return false;
         
        return 0;
    }
    }

    private function do_journal($sent_data,$loan=True,$interest_data=false){
        
        $single_row = [
            "journal_type_id" => $sent_data['journal_type_id'],
            "ref_id" => $sent_data['ref_id'],
            "ref_no" => $sent_data['ref_no'],
            "description" => $sent_data['description'],
            "transaction_date" =>  $sent_data['action_date'],
            "status_id" => 1,
            "date_created" => time(),
            "created_by" => 1,
        ];

        $insert_id=$this->Data_import_model->add_journal_tr($single_row);

        if(!empty($insert_id) && $loan){
            $data=[];
            $debit_or_credit1 = $this->accounts_model->get_normal_side($sent_data['loan_receivable_account_id']);
            $debit_or_credit2 = $this->accounts_model->get_normal_side(28, true);
            $data[0] = [
                'reference_no' => $sent_data['ref_no'],
                'reference_id' => $sent_data['ref_id'],
                'transaction_date' => $sent_data['action_date'],
                $debit_or_credit2=> $sent_data['principal_amount'],
                'narrative'=> strtoupper("Loan Disbursement on ".$sent_data['action_date']),
                'account_id'=> 40,
                'status_id'=> 1
            ];
            $data[1] = [
                'reference_no' => $sent_data['ref_no'],
                'reference_id' => $sent_data['ref_id'],
                'transaction_date' => $sent_data['action_date'],
                $debit_or_credit1=> $sent_data['principal_amount'],
                'narrative'=> strtoupper("Loan Disbursement on ".$sent_data['action_date']),
                'account_id'=>$sent_data['loan_receivable_account_id'],
                'status_id'=> 1
            ];

            $data=array_merge($data,$interest_data);
            return $this->Data_import_model->add_journal_tr_line($insert_id, $data);
        }

}

private function insert_loan_payment_transaction($loan_data, $loan_id) {

    $date_created = time();
    $payment_date= "2019-12-31";

    $payment_status = ["FULL" => 1, "PARTIAL" => 2, "PAID OFF" => 3, "PAID UP" => 3];
    
    $schedule = $this->Data_import_model->get_reschedule_id($loan_id);
    $paid_principal = $loan_data[6];
    $paid_interest = $loan_data[10];

    try {
        if ($loan_data[0] != "" && $loan_data[0] != 'NULL') {
            $payment_data = [
                "client_loan_id" =>  $loan_id,
                "paid_principal" => $paid_principal,
                "paid_interest" => $paid_interest,
                "repayment_schedule_id" => $schedule['id'],
                "paid_penalty" => 0,
                "payment_date" => $payment_date,
                "transaction_channel_id" => 1,
                "comment" => 'Loan Payment imported',
                "status_id" => 1,
                'date_created' => $date_created,
                "created_by" => 1
            ];
            $inserted_id=$this->loan_installment_payment_model->set3($payment_data);

            $data = array(
                        'payment_status' => 2,//All payments will be partial
                        'actual_payment_date' => $payment_date,
                        'modified_by' => 1
                        );
            $this->repayment_schedule_model->update2($data,'repayment_schedule.id ='.$schedule['id']);//loan_id is the same and the repayment_schedule_id

            $sent_data=[
                    'journal_type_id'=> 6,
                    'ref_no' => $this->client_loan_no,
                    'ref_id' => $inserted_id,
                    'description' => 'Loan Payment imported from Excel',
                    'action_date' => $payment_date,
                    'principal_amount' => $paid_principal,
                    'interest_amount' => $paid_interest,
                    'linked_account_id' =>40,
                    'loan_receivable_account_id' =>1,
                    'interest_income_account_id' =>6,
                    'interest_receivable_account_id' =>7
                ];
              
            return 1;
        }
        return 0;
    } catch (Exception $e) {
        return false;
    }
}
}