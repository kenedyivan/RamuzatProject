<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Data_loan_import
 *
 * @author Reagan
 */
class Data_share_import extends CI_Controller {

    public function __construct() {
        parent :: __construct();
        $this->load->library("session");
        if (empty($this->session->userdata('id'))) {
            redirect('/');
        }
        $this->load->model("Data_import_model");
        $this->load->model("journal_transaction_line_model");
        
        $this->load->library("helpers");
        
         ini_set('memory_limit', '200M');
            ini_set('upload_max_filesize', '200M');
            ini_set('post_max_size', '200M');
            ini_set('max_input_time', 3600);
            ini_set('max_execution_time', 3600);
    }

    public function index() {
        $folder = "data_extract".DIRECTORY_SEPARATOR."brookside".DIRECTORY_SEPARATOR;
        $file_name = "SHARES_DATA.csv";
        $file_path = FCPATH . $folder . $file_name;
        $feedback = $this->run_updates($file_path);
        echo json_encode($feedback);
    }

    private function run_updates($file_path) {
        $handle = fopen($file_path, "r");
        $total_counts = $count = 0;
        $field_names = $batch_data = [];
        $feedback = ["success" => false, "message" => "File Could not be opened"];
        if ($handle) {
            while (($data = fgetcsv($handle, 10240, ",")) !== FALSE) {
                $data1 = $this->security->xss_clean($data);
                if ($count == 0) {//the row with the field_names
                    $field_names = $data1;
                    //echo $field_names[0];die();
                    if ($field_names[0] != "Account ID") {
                        $feedback['message'] = "Please ensure that the first cell (Account ID) contains the key Account No";
                        fclose($handle);
                        return $feedback;
                    }

                } else {
                    
                   $total_counts = $count;
                   $this->insert_transaction_data($data1);
                }
                            
                $count++;

            }

                //$batch_data = [];
            fclose($handle);

            if (is_numeric($total_counts)) {
                $feedback["success"] = true;
                $feedback["message"] = "Update done\n $total_counts records updated";
            }
        }
        return $feedback;
    }
    
    
    private function insert_transaction_data($transaction) {  
        $transaction_date = $this->helpers->extract_date_time($transaction[8],"Y-m-d");
        // echo $transaction_date; die();
            if($transaction[4]=="CREDIT"){
                if ($transaction[9]!="5") {
                $amount=$transaction[6];
            $journal_type_id=22;
            $debit_amount =NULL;
            $credit_amount =$transaction[6];
            $credit_account_id=32;
            $debit_account_id=40;
            }else{
            $debit_amount =NULL;
            $journal_type_id=22;
            $credit_amount =$transaction[6];
            $amount=$transaction[6];
            $credit_account_id=32;
            $debit_account_id=8;
            }
            } else {
            $amount=$transaction[5];
            $journal_type_id=24;
            $debit_amount =$transaction[5];
            $credit_amount =NULL;
            $credit_account_id=40;
            $debit_account_id=32;
            }
       
            $trans_row = [
                "transaction_no" =>  date('yws').mt_rand(1000000,9999999),
                "share_account_id" => $transaction[0],
                "share_issuance_id"=>1,
                "debit" => $debit_amount,
                "credit" => $credit_amount,
                "transaction_type_id" => $transaction[7],
                "payment_id" =>$transaction[9],
                "transaction_date" => $transaction_date,
                "narrative" => $transaction[3]." [ ".$transaction[2]." ]",
                "status_id" => 1,
                "date_created" => time(),
                "created_by" => 1
            ];
            //for transfers to shares
            $transaction_data=$this->Data_import_model->add_transaction_shares($trans_row);
             if($transaction[4]=="CREDIT"){
              if ($transaction[9]=="5") {
                $this->insert_savings_transaction_data($transaction);
              }}
             if(!empty($transaction_data)){
            
             $single_row = [
                "journal_type_id" =>$journal_type_id,
                "ref_id" => $transaction_data['transaction_id'],
                "ref_no" => $transaction_data['transaction_no'],
                "description" =>$transaction[3]." [ ".$transaction[2]." ]",
                "transaction_date" =>  $transaction_date,
                "status_id" => 1,
                "date_created" => time(),
                "created_by" => 1,
                "modified_by" =>1
            ];
            $insert_id=$this->Data_import_model->add_journal_tr($single_row);
             if(!empty($insert_id)){
                 
                $data[0] = [
                    'debit_amount' => $amount,
                    'transaction_date' =>  $transaction_date,
                    'reference_id' => $transaction_data['transaction_id'],
                    'reference_no' => $transaction_data['transaction_no'],
                    'credit_amount' =>NULL,
                    'narrative' => $transaction[3]." [ ".$transaction[2]." ] made on " . $transaction_date,
                    'account_id' =>$debit_account_id,
                    'status_id' => 1
                ];
                $data[1] = [
                    'credit_amount' =>$amount,
                    'transaction_date' =>$transaction_date,
                    'reference_id' => $transaction_data['transaction_id'],
                    'reference_no' => $transaction_data['transaction_no'],
                    'debit_amount' => NULL,
                    'narrative' => $transaction[3]." [ ".$transaction[2]." ] made on " . $transaction_date,
                    'account_id' => $credit_account_id,
                    'status_id' => 1
                ];
             return $this->Data_import_model->add_journal_tr_line($insert_id, $data);
             }else{
                  echo "journal failed";die();
             }
        }else{
            echo "trasaction failed";die();
        }
            
        
    }
    
     private function insert_share_transaction_data($transaction) {  
        $transaction_date = $this->helpers->extract_date_time($transaction[8],"Y-m-d");
        // echo $transaction_date; die();

            $trans_row = [
                "transaction_no" =>  date('yws').mt_rand(1000000,9999999),
                "share_account_id" => $transaction[0],
                "share_issuance_id"=>1,
                "debit" => NULL,
                "credit" => $transaction[5],
                "transaction_type_id" => 9,
                "payment_id" =>2,
                "transaction_date" => $transaction_date,
                "narrative" => $transaction[4],
                "status_id" => 1,
                "date_created" => time(),
                "created_by" => 1
            ];
            return $this->Data_import_model->add_transaction_shares($trans_row);
     
    }


     private function insert_savings_transaction_data($transaction) {  
        $transaction_date = $this->helpers->extract_date_time($transaction[8],"Y-m-d");
            $trans_row = [
                "transaction_no" =>  date('yws').mt_rand(1000000,9999999),
                "account_no_id" => $transaction[0],
                "debit" => $transaction[6],
                "credit" => NULL,
                "transaction_type_id" => 4,
                "payment_id" =>$transaction[9],
                "transaction_date" => $transaction_date,
                "narrative" => "Converted into shares [ ".$transaction[2]." ]",
                "status_id" => 1,
                "date_created" => time(),
                "created_by" => 1
            ];
            return $this->Data_import_model->add_transaction($trans_row);
     
    }

    public function do_insert_accounts($account_data) {
        if ($account_data[2] != "" && $account_data[2] != NULL && substr( $account_data[2], 0, 2 )==5) {
        $state =5;
        if($account_data[4]=="Y"){
            $state =7;
        }else{
        $state =17;
        }
        //echo $this->helpers->extract_date_time($account_data[6]); die();
        $data = [
            "member_id" => $account_data[1],
            "account_no" => $account_data[3],
            "deposit_product_id" => 2,
            "client_type" => 1,
            "status_id" => 1,
            "date_created" =>$this->helpers->extract_date_time($account_data[6]),
            "created_by" => $account_data[5]
        ];
        $this->Data_import_model->add_savings_account($data,$state,$this->helpers->extract_date_time($account_data[6]));
    }
}

}
