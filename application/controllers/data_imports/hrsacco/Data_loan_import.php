<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Data_loan_import
 *
 * @author Eric
 */
class Data_loan_import extends CI_Controller {

    public function __construct() {
        parent :: __construct();
        $this->load->library("session");
        if (empty($this->session->userdata('id'))) {
            redirect('/');
        }
        ini_set('memory_limit', '200M');
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '200M');
        ini_set('max_input_time', 3600);
        ini_set('max_execution_time', 3600);
        $this->load->model('client_loan_model');
        $this->load->model('loan_product_model');
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
        $this->client_loan_no='LN0067';

    }

    public function index() {
        $folder = "data_extract".DIRECTORY_SEPARATOR."hdrsacco".DIRECTORY_SEPARATOR;
        $file_name = "Member_loans.csv";
        $file_path = FCPATH . $folder . $file_name;
        $feedback = $this->run_updates($file_path);
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
                    if ($field_names[0] != "member_id") {
                        $feedback['message'] = "Please ensure that the first cell (A1) contains the key Member ID";
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
        $preferred_payment = ["CASH" => 1, "BANK" => 2, "MOBILE MONEY" => 3];
        $periods = ["DAYS" => 1, "WEEKS" => 2, "MONTHS" => 3];
        $topUp = ["N" => 0, "Y" => 1];
        $source_fund_id = [27, 40];

        $date_created = time();
        $action_date = date('Y-m-d',strtotime('2020-10-20'));
        $member_id = $loan_data[0];
        $amount=$loan_data[2];
        $loan_product_id =1;
        $interest_type =2;//flat rate
        $repayment_frequency = $loan_data[8];
        $repayment_made_every = 3;
        $installments=$loan_data[5];

        try {
            if ($loan_data[0] != "" && $loan_data[0] != 'NULL') {
                $single_row = [
                    "member_id" =>  $member_id,
                    "loan_no" => $this->client_loan_no,
                    "credit_officer_id" => 4, 
                    "loan_product_id" => $loan_product_id,
                    "topup_application" => 0,
                    "requested_amount" => $amount,
                    "application_date" => $action_date,
                    "source_fund_account_id" => 28, //$source_fund_id[$loan_product_id-1],
                    "disbursement_date" => $action_date,
                    "suggested_disbursement_date" => $action_date,
                    "interest_rate" => ($loan_data[4]*(12/$loan_data[8])),
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
                    'date_created' => $date_created,
                    "created_by" => 1
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
                        "created_by" => 1
                    ];
                    $this->loan_state_model->set2($loan_state_details);
                    //Schedule genration
                    $repayment_made_every=12;
                    
                    $r=$interest_rate_per_annum=$interest_rate_per_installment=($loan_data[4]*(12/$loan_data[8]))/100; 
                    $l=$length_of_a_period=($repayment_frequency/$repayment_made_every);
                    $i=$interest_rate_per_period=($r*$l);
                    $n=$installments; 
                    $p=$amount;
                    $repayment_date=$this->helpers->extract_date_time($loan_data[7],"Y-m-d");

                    $index_key=2;
                    $number_of_years=$n*$l;
                    $interest_amount= (($p*$number_of_years*$r)/$n);
                    $principal_amount= ($p/$n);
                    $repayment_schedule_array =[];
                    $interest_data =[];
                    $debit_or_credit3 = $this->accounts_model->get_normal_side(78);
                    $debit_or_credit4 = $this->accounts_model->get_normal_side(98);

                    for ($y=1; $y <= $installments; $y++) { 
                        $index_key+=2;
                        $this->schedule_id = ++$this->schedule_id;
                        $repayment_schedule_array[] = [
                            "repayment_date" => $repayment_date,
                            "interest_amount" => $interest_amount,
                            "principal_amount" => $principal_amount,
                            "client_loan_id" => $inserted_loan_id,
                            "grace_period_on" => 1,
                            "grace_period_after" => 3,
                            "installment_number" => $y,
                            "interest_rate" => ($loan_data[4]*(12/$loan_data[8])),
                            "repayment_frequency" => $repayment_frequency,
                            "repayment_made_every" => 3,
                            "comment" => 'Loan schedule imported',
                            "payment_status" => 4,
                            "status_id" => 1,
                            "date_created" => $date_created,
                            "created_by" => 1
                        ];
                        $interest_data[$index_key-1]=[
                            'reference_no' => $this->client_loan_no,
                            'reference_id' => $this->schedule_id,
                            'transaction_date' => $repayment_date,
                            $debit_or_credit3=> $interest_amount,
                            'narrative'=> strtoupper("Interest on Loan Disbursed on ".$action_date),
                            'account_id'=> 78,
                            'status_id'=> 1
                        ];

                        $interest_data[$index_key] =  [
                            'reference_no' => $this->client_loan_no,
                            'reference_id' => $this->schedule_id,
                            'transaction_date' => $repayment_date,
                            $debit_or_credit4=> $interest_amount,
                            'narrative'=> strtoupper("Interest on Loan Disbursed on ".$action_date),
                            'account_id'=> 98,
                            'status_id'=> 1
                        ];
                        $repayment_date = date('Y-m-d',strtotime('+'.$repayment_frequency.' month', strtotime($repayment_date)));
                        
                    }
                    $this->repayment_schedule_model->set2($repayment_schedule_array);
                    $sent_data['loan']=[
                        'journal_type_id'=> 4,
                        'ref_no' => $this->client_loan_no,
                        'ref_id' => $inserted_loan_id,
                        'description' => 'Loan imported from Excel',
                        'action_date' => $action_date
                    ];
                    $this->do_journal($sent_data['loan'],true,$interest_data);
                }
                return 1;
            }
        } catch (Exception $e) {
            return false;
        }
       
        return 0;
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
    if(!empty($insert_id)){
        
        return $this->Data_import_model->add_journal_tr_line($insert_id, $interest_data);
    }

}

}