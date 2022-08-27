<div class="panel-body">
    <div>
        <strong><?php echo $title; ?> details</strong> 
        <?php if (in_array('3', $accounts_privilege)) { ?>
                <!--a data-toggle="modal" href="#add_asset-modal"  class="btn btn-sm btn-primary pull-right"><i class="fa fa-pencil"></i> Update</a-->
        <?php } ?>
    </div>
    <table class="table table-stripped  m-t-md">
        <tbody data-bind="with: expense_detail">
            <tr>
                <td>
                    <i class="fa fa-houzz text-navy"></i> Expense ID
                </td>
                <td data-bind="text: id" class="text-muted">
                </td>
                <td class="no-borders">
                    <i class="fa fa-briefcase text-navy"></i>Cash Account:
                </td>
                <td class="text-muted">
                    <a title='Click to view account details' data-bind='attr: {href:"<?php echo site_url("accounts/view");?>/" +cash_account_id}, text: "["+account_code+ "]  "+account_name'></a>
                </td>
            </tr>
            <tr>
                <td class="no-borders">
                    <i class="fa fa-user-circle text-navy"></i>Supplier/Vendor
                </td>
                <td class="text-muted" colspan="3">
                    <a title='Click to view details' data-bind='attr: {href:"<?php echo site_url("supplier/view");?>/" +supplier_id}, text: supplier_names'></a>
                </td>
            </tr>
            <tr>
                <td class="no-borders">
                    <i class="fa fa-calendar text-navy"></i> Payment date
                </td>
                <td class="text-muted" data-bind="text: moment(payment_date, 'YYYY-MM-DD').format('D-MMM-YYYY')">
                </td>
                <td class="no-borders">
                    <i class="fa fa-money text-navy"></i>Total Expenditure:
                </td>
                <td data-bind="text: curr_format(total_amount*1)" class="text-muted">
                </td>
            </tr>
            <tr>
                <td>
                    <i class="fa fa-hashtag text-navy"></i> Description
                </td>
                <td colspan="3" class="text-muted" data-bind="text: description">
                </td>
            </tr>
            <tr>
                <td class="no-borders">
                    <span data-bind="visible:attachment_url&&attachment_url!==''"><i class="fa fa-image text-navy"></i>Attachment</span>
                </td>
                <td class="text-muted">
                    <img data-bind="attr:{src: '<?php echo base_url();?>/' +attachment_url}, visible:attachment_url&&attachment_url!==''" title="attachment"class="img-md"/>
                </td>
                <td class="no-borders">
                    <i class="fa fa-hashtag text-navy"></i> Authorized By
                </td>
                <td class="text-muted">
                    <a title='Click to view staff details' data-bind='attr: {href:"<?php echo site_url("staff/view");?>/" +authorizer_id}, text: authorizer_names '></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php //$this->load->view('accounts/fixed_asset/add_modal');