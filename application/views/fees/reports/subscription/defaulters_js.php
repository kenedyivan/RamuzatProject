if ($('#tblDefaulters_subscription_fees').length && tabClicked === "tab-subscription_fees") {
                if(typeof dTable['tblDefaulters_subscription_fees'] !=='undefined'){
                    dTable['tblDefaulters_subscription_fees'].ajax.reload(null,true);
                }else{
                    dTable['tblDefaulters_subscription_fees'] = $('#tblDefaulters_subscription_fees').DataTable({
                    "pageLength": 10,
                    "responsive": true,
                    "dom": '<"html5buttons"B>lTfgitp',
                    buttons: <?php if(in_array('6', $member_privilege)){ ?> getBtnConfig('<?php echo $title; ?>- Member Fees'), <?php } else { echo "[],"; } ?>
                    "ajax": {
                        "url": "<?php echo site_url('automated_fees/jsonList2'); ?>",
                        "dataType": "json",
                        "type": "POST",
                        "data": function(d){}
                    },
                    "footerCallback": function (tfoot, data, start, end, display) {
                        var api = this.api();
                        var amount_page = api.column(2, {page: 'current'}).data().sum();
                        var amount_overall = api.column(2).data().sum();                        
                        $(api.column(2).footer()).html(curr_format(amount_page) );
                    },
                    "columns": [                    
                        {"data": "member_name", render:function(data, type, full, meta){
                            return data;
                        }},                    
                        {"data": "plan_name"},
                        {"data": "amount", render: function(data, type, full, meta){ 
                            return data?curr_format(data*1):'';
                            }},
                        { data: "subscription_date", render:function( data, type, full, meta ){
                            return (data)?moment(data,'YYYY-MM-DD').format('D-MMM-YYYY'):'None';;
                        }},
                        {"data": "state_name", render:function(data, type,full ,meta){
                            return '<a href="#" role="button" class="badge badge-danger" >'+data+'</a>';
                        }},
                        
                        
                        {"data": "id", render: function (data, type, full, meta) {
                            var ret_txt ="";
                                <?php if(in_array('6', $member_privilege)){ ?>
                                   ret_txt += '<a href="#pay_subscription_fees-modal" data-toggle="modal"   title="Pay Fee" class="btn btn-xs btn-success edit_me6"><i class="fa fa-money" ></i> Pay</a>';                                
                                <?php } if(in_array('4', $member_privilege)){ ?>
                                    
                                <?php } ?>
                                return ret_txt;
                            }}
                    ]
                });
                }
            }

$('table tbody').on('click', 'tr .edit_me6', function (e) {
    e.preventDefault();
    var row = $(this).closest('tr');
    var tbl = row.parent().parent();
    var tbl_id = $(tbl).attr("id");
    var dt = dTable[tbl_id];
    var data = dt.row(row).data();
    if (typeof (data) === 'undefined') {
        data = dt.row($(row).prev()).data();
        if (typeof (data) === 'undefined') {
            data = dt.row($(row).prev().prev()).data();
        }
    }
    get_user_savings_accounts(data.member_id);
    var formId = tbl_id.replace("tbl", "form");
    edit_data(data, formId);
});