if ($("#tblLoan_collateral").length && tabClicked === "tab-collateral") {
                if (typeof (dTable['tblLoan_collateral']) !== 'undefined') {
                    $(".tab-pane").removeClass("active");
                    $("#tab-collateral").addClass("active");
                    dTable['tblLoan_collateral'].ajax.reload(null, true);
                } else {
                    dTable['tblLoan_collateral'] = $('#tblLoan_collateral').DataTable({
                        "dom": '<"html5buttons"B>lTfgitp',
                        order: [[1, 'asc']],
                        deferRender: true,
                        "initComplete": function( settings, json ){
                            loanDetailModel.collateral_amount( sumUp( json.data, 'amount_locked' ) );
                         },
                        "ajax":{
                            "url": "<?php echo base_url('loan_collateral/jsonList/'); ?>",
                            "dataType": "json",
                            "type": "POST",
                            "data": function (d) {
                              d.status_id = 1,
                              d.client_loan_id = <?php echo $loan_detail['id']; ?>
                            }
                        },
                        "columnDefs": [{
                                "targets": [3],
                                "orderable": false,
                                "searchable": false
                            }],
                "columns": [
                      { "data": "collateral_type_name" },
                      { "data": "description" },
                      { "data": "item_value",render:function(data, type,full ,meta ){
                            return curr_format(data*1);
                          } },
                      { "data": "file_name", render: function(data, type, full,meta){
                                if(full.file_name!==""){
                                    var organisation_id=<?php echo $_SESSION['organisation_id']; ?>;
                                    //var link="<a target='blank' href='http://docs.google.com/gview?url=<?php echo site_url(); ?>uploads/organisation_"+organisation_id+"/loan_docs/collateral/"+data+"&amp;embedded=true' width='500' height='250' style='border-style:none;'>view file </a>";
                                    var link= "<a target='blank' href='<?php echo site_url(); ?>uploads/organisation_"+organisation_id+"/loan_docs/collateral/"+data+"' title='View document details'>View File</a>";
                                    return link;
                                    }
                                return "No file";
                        } }
                   ],
                        buttons: getBtnConfig('Loan Collateral'), 
                        responsive: true
                    });
                }
            }
