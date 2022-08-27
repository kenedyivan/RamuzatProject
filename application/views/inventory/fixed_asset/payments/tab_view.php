<div class="panel-body"><br>
    <div class="col-lg-12">
        <p>
            <strong>Payments</strong>
            <?php if (in_array('3', $accounts_privilege)) { ?>
            <div data-bind="with: fixed_asset_detail">
            <div data-bind="visible: parseInt(status_id) ==parseInt(1)">
             
                <a data-bind="visible: (parseFloat(purchase_cost)-parseFloat($parent.asset_paid_amount()?$parent.asset_paid_amount():0))>0" data-toggle="modal" href="#add_asset_payment-modal" class="btn btn-sm btn-primary pull-right" style="margin:1px;><i class="fa fa-edit"></i> Add Payment
                </a>
                  
                   <a data-bind="visible: (parseFloat(purchase_cost)-parseFloat($parent.asset_paid_amount()?$parent.asset_paid_amount():0))<=0" data-toggle="modal" href="#selling_asset_modal" class="btn btn-sm btn-primary pull-right" style="margin:1px;"><i class="fa fa-money"></i> Dispose Asset</a>

                </div>
            </div>

            <?php } ?>
        </p>

        <p data-bind="with: fixed_asset_detail"><strong>Purchase Cost (UGX):</strong> <span data-bind="text: curr_format(purchase_cost*1)"></span>.
            <strong>Total Payments (UGX):</strong> <span data-bind="text: curr_format($parent.asset_paid_amount()*1)"></span>
            <strong>Balance (UGX):</strong> <span data-bind="text: curr_format(parseFloat(purchase_cost)-parseFloat($parent.asset_paid_amount()?$parent.asset_paid_amount():0))"></span>
        </p>
        <div class="hr-line-dashed"></div>
        
        <div class="table-responsive">
            <table class="table  table-bordered table-hover" id="tblAsset_payment" width="100%" >
                <thead>
                    <tr>
                        <th>Transaction No</th>
                        <th>Transn. Date</th>
                        <th>Type </th>
                        <th>Amount </th>
                        <th>Payment Mode </th>
                        <th>Fund Source AC </th>
                        <th>Narrative</th>
                        <th>Status</th>
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Totals</th>
                        <th>&nbsp;</th> 
                        <th>&nbsp;</th> 
                        <th>0 </th>
                        <th>&nbsp;</th> 
                        <th>&nbsp;</th> 
                        <th>&nbsp;</th> 
                        <th >&nbsp;</th> 
                        <th >&nbsp;</th> 
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<?php
$this->load->view('inventory/fixed_asset/payments/add_modal');
$this->load->view('inventory/fixed_asset/payments/disposal_modal');
$this->load->view('inventory/fixed_asset/payments/edit_payment_transaction');
$this->load->view('inventory/fixed_asset/payments/reverse_model');
?>
