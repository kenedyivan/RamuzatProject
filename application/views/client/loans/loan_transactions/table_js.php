if ($("#tblLoan_installment_payment").length && tabClicked === "tab-loan_installment_payment") {
                if (typeof (dTable['tblLoan_installment_payment']) !== 'undefined') {
                    $(".tab-pane").removeClass("active");
                    $("#tab-loan_installment_payment").addClass("active");
                    dTable['tblLoan_installment_payment'].ajax.reload(null, true);
                } else {
                    dTable['tblLoan_installment_payment'] = $('#tblLoan_installment_payment').DataTable({
                        "dom": '<"html5buttons"B>lTfgitp',
                        "ajax":{
                            "url": "<?php echo base_url('loan_installment_payment/jsonList'); ?>",
                            "dataType": "json",
                            "type": "POST",
                            "data": function (d) {
                             d.client_loan_id = <?php echo (isset($loan_detail['id']))? $loan_detail['id']:'0'; ?>,
                             d.status_id=1
                            }
                        },
              "footerCallback": function (tfoot, data, start, end, display) {
                    var api = this.api();
                $.each([2,3,4], function(key,val){
                    var total_page_amount = api.column(val, {page: 'current'}).data().sum();
                    var total_overall_amount = api.column(val).data().sum();
                    $(api.column(val).footer()).html(curr_format(round(total_page_amount,2)) + " (" + curr_format(round(total_overall_amount,2)) + ") ");
                    });
                },
            "columns": [
                      { data: "loan_no"},
                      { data: "installment_number", render:function( data, type, full, meta ){
                          return (full.installment_number !='')?data:'Pay off';}},
                      { data: "paid_interest", render:function( data, type, full, meta ){
                          return curr_format(data*1);}
                      },
                      { data: "paid_principal", render:function( data, type, full, meta ){
                          return curr_format(data*1);}
                      },
                      { data: "paid_penalty", render:function( data, type, full, meta ){
                          return curr_format(data*1);}
                      },
                      { data: "payment_date", render:function( data, type, full, meta ){
                          if (type === "sort" || type === "filter") {
                              return data;
                          }
                        return (!(data=='0000-00-00'))?moment(data,'YYYY-MM-DD').format('D-MMM-YYYY'):'';
                          }  
                      },
                      { data: "firstname", render:function( data, type, full, meta ){
                          return full.staff_no+'-'+full.firstname+' '+full.lastname+' '+full.othernames;}
                      },                      
                      { data: "comment"}
                   ],
                        buttons: getBtnConfig('Loan Payment Transactions'),
                        responsive: true
                    });
               }
            }