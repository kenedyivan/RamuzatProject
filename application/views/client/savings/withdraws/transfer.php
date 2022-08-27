<style type="text/css">
    .blueText{color: blue;font-size: 10px;}
</style>
<div class="modal inmodal fade" id="transfer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" class="formValidate" enctype="multipart/form-data" action="<?php echo base_url(); ?>Transaction/Create" id="formTransfer">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                         <h4 class="modal-title">
                         <?php
                        if (!isset($selected_account)) {?>
                           <!-- ko with: account_trans -->
                             <span data-bind="text:(client_type==1)?'Individual Transfer':'Group Transfer'"></span>
							 <input type="hidden" data-bind="value:(client_type==1)?1:2" name="client_type">
                           <!-- /ko -->
                        <?php } else { ?>
                            <span data-bind="text:($root.account_trans().client_type==1)?' New Individual Transfer':' New Group Transfer'"></span>
							<input type="hidden" data-bind="value:($root.account_trans().client_type==1)?1:2" name="client_type">
                         <?php    } 
                        ?>
                        </h4>
                    <small class="font-bold">Note: Required fields are marked with <span class="text-danger">*</span></small>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id">
                    <input type="hidden" name="transaction_type_id" id="transaction_type_id" value="3">
                    <input type="hidden" name="charge_id" id="charge_id" value="6">

                    <!--withdraw-->

                    <div class="form-group row">  
                        <fieldset class="col-lg-12">     
                            <legend>Account info</legend>
                            <?php if (!isset($selected_account)) { ?>
                                <div class="col-lg-12 row no-gutter" >
                                    <!-- ko with: account_trans -->
                                    <input name="account_no_id" data-bind="value: (id)?id :''" type="hidden"/>
                                    <input name="opening_balance" data-bind="value: (opening_balance)?opening_balance :''" type="hidden"/>
                                    <input name="state_id" data-bind="value: (state_id)?state_id :''" type="hidden"/>
                                    <!-- /ko -->
                                <?php } else { ?>
                                    <div class="col-lg-12 row no-gutter" data-bind="with: selected_account">
                                        <input name="account_no_id" data-bind="value: (id)?id :''" type="hidden"/>
                                        <input name="opening_balance" data-bind="value: (opening_balance)?opening_balance :0" type="hidden"/> 
                                        <input name="state_id" data-bind="value: (state_id)?state_id :''" type="text"/>
                                        <input name="account_details" value="3" type="hidden"/>
                                    <?php } ?>
                                    <div class="form-group col-lg-5">
                                        <h2> <center>From</center></h2><hr>
                                        <label class="col-lg-12"><span class="text-danger">*</span>Account No.</label>
                                        <div class="col-lg-12" >
                                         <?php if (!isset($selected_account)) { ?> 
                                          <!-- ko with: account_trans -->
                                         <?php } ?>
                                                <input placeholder="" disabled class="form-control" id="account_no" type="text" data-bind="value: (account_no)?account_no:'None'">
                                        <?php if (!isset($selected_account)) { ?> 
                                            <!--/ko-->
                                        <?php } ?>
                                        </div>
                                        <label class="col-lg-12 ">Account Holder<span class="text-danger">*</span></label>
                                        <div class="col-lg-12">
                                            <?php if (!isset($selected_account)) { ?>  
                                                <!-- ko with: account_trans -->
                                                <input type='text'  name="member_name" class="form-control" data-bind="value:(member_name)?member_name:'None'" disabled />
                                                <!--/ko-->
                                                <!-- ko ifnot: account_trans -->
                                                <input type='text' class="form-control" value="Select Account No. first..." disabled />
                                                <!--/ko-->
                                            <?php } else { ?>
                                                <input class="form-control" name="member_id" data-bind="value:(member_name)?member_name :'None'" disabled />
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 border-left">
                                        <h2> <center>To</center></h2><hr>
                                         <div class="form-group row">
                                        <label class="col-lg-12 col-form-label">Account Number<span class="text-danger">*</span></label>
                                        <div class="col-lg-12 form-group">
                                            <select class="form-control required" id="savings_account_id" name="savings_account_id" data-bind='options: savings_accounts, optionsText: function(data){return data.account_no + " | " + data.member_name;}, optionsCaption: "Select...", optionsAfterRender: setOptionValue("id"), value: savings_account_id' style="width: 100%" data-msg-required="A savings account is required">
                                            </select>
                                        </div>
                                    </div>
                                     <center> 
                                            <span class="text-muted">Amount available to Transfer</span>
                                            <?php if (!isset($selected_account)) { ?>
                                                <!-- ko with: account_trans -->
                                                <h2 data-bind="text: curr_format(parseFloat(cash_bal))"></h2>
                                                <!--/ko-->
                                            <?php } else { ?>
                                                <h2 data-bind="text: curr_format(parseFloat(cash_bal))">
                                                </h2>
                                            <?php } ?> 
                                        </center>
                                       
                                    </div>
                                </div>
                        </fieldset>
                    </div> 
                    <input required type="hidden" class="form-control" style="width: 100%" name="group_member_id" value=""> 
                    <!--ko with: account_trans-->  
                    <div class="form-group row" > 
                  
                        <label class="col-lg-4 col-form-label"> Transfer Amount</label>
                        <div class="col-lg-6">
                            <input class="form-control" id="amount" name="amount"  data-bind="textInput:$parent.transfer_amount,valueUpdate:'afterkeydown',attr: {'data-rule-min':((parseFloat($root.totaltransferCharges())>0)?$root.totaltransferCharges():null),'data-rule-max':(cash_bal<=parseInt(maxwithdrawalamount))?cash_bal-(parseFloat($root.totaltransferCharges())):((parseFloat(maxwithdrawalamount)>0 )?((maxwithdrawalamount)*1-parseFloat($root.totaltransferCharges())):null)}
                            " type="text" required >
                            <span class="blueText"><b data-bind="text:parseFloat($parent.totaltransferCharges())"></b>(min )&nbsp;&nbsp;&nbsp;<b data-bind="text:(cash_bal<=parseInt(maxwithdrawalamount))?cash_bal-(parseFloat($root.totaltransferCharges())):((parseFloat(maxwithdrawalamount)>0 )?((maxwithdrawalamount)*1-parseFloat($root.totaltransferCharges())):null)"></b> (max ).</span>
                        </div>
                    </div> 
                    <div class="form-group row">  
                        <div class="col-lg-6">
                            <fieldset >
                                <legend style="min-width:250px;">Transaction Charges</legend>
                                <table class='table table-hover'>
                                    <thead>
                                        <tr>
                                            <th class="border-right">#</th>
                                            <th>Name</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody data-bind="foreach: $parent.transfer_fees">
                                        <tr>
                                            <td data-bind="text:$index()+1" class="border-right"></td> 
                                            <td >
                                                <span  class=" input-xs"  data-bind="text:(feename)?feename:'None'"></span>
                                                <input  class="form-control input-xs" required data-bind="value:(id)?id:'none', attr: {name:'charges['+$index()+'][charge_id]'}" type="hidden">
                                            </td> 
                                            <td >
                                                <span class=" input-xs"  data-bind="text:curr_format((parseInt(cal_method_id)==1)?((parseFloat(amount)*1*parseInt($parentContext.$parent.transfer_amount()))/100):amount)" ></span>
                                                <input class="form-control input-xs" required data-bind="value:(parseInt(cal_method_id)==1)?((parseFloat(amount)*1*parseInt($parentContext.$parent.transfer_amount()))/100):amount, attr: {name:'charges['+$index()+'][charge_amount]'}" type="hidden">
                                             
                                            </td> 

                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><span class="pull-right ">Total charges :</span></td>
                                            <th data-bind="text: $parent.totaltransferCharges()"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Date<span class="text-danger">*</span></label>
                            <div class="form-group col-lg-8">
                                <div class="input-group date">
                                    <input  type="text" class="form-control" name="transaction_date" value="<?php echo mdate("%d-%m-%Y"); ?>" placeholder="Transaction date" required/>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>  
                         <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Narrative</label>
                            <div class="col-lg-8">
                                <textarea placeholder="" required class="form-control" id="narrative" name="narrative"></textarea>
                            </div>
                        </div>
                    </div>
                    </div>  
                    <!--/ko-->  
                     
                   <!--  <div class="form-group row">   
                     <div class="col-lg-8">
                    </div>
                    <div class="col-lg-4">
                     <div class="form-check">
                    <input type="checkbox" class="form-check-input " name="print" id="print">
                    <label class="form-check-label" for="print"><span class="text-success"><b>Print Receipt</b></span></label>
                  </div>
                  </div>
                  </div>  -->     
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <!--ko if:(parseInt($root.account_balance()>0)-->       
                    <button type="submit" class="btn btn-primary">
                        <?php
                        if (isset($saveButton)) {
                            echo $saveButton;
                        } else {
                            echo "Save";
                        }
                        ?>
                    </button>
                    <!--/ko-->
                </div>
            </form>
        </div>
    </div>
</div>
