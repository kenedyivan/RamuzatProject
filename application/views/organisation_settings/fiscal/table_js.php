if ($("#tblFiscal_year").length && tabClicked === "tab-fiscal") {
                if (typeof (dTable['tblFiscal_year']) !== 'undefined') {
                    $(".tab-pane").removeClass("active");
                    $("#tab-fiscal").addClass("active");
                    dTable['tblFiscal_year'].ajax.reload(null, true);
                } else {
                    dTable['tblFiscal_year'] = $('#tblFiscal_year').DataTable({
                        "dom": '<"html5buttons"B>lTfgitp',
                        order: [[1, 'asc']],
                        deferRender: true,
                        ajax: {
							"url":"<?php echo site_url('Fiscal_year/jsonList') ?>",
							"dataType":"json",
							"type":"POST",
							"data":
							function(e){
								e.organisation_id = <?php echo $organisation['id']; ?>;
								}
						},
                        "columnDefs": [{
                                "targets": [2],
                                "orderable": false,
                                "searchable": false
                            }],
                        columns: [
                        {data: 'start_date', render: function (data, type, full, meta) {
                            return data ? moment(data, 'YYYY-MM-DD').format('DD-MMM-YYYY') : '';
                        }},
                        {data: 'end_date', render: function (data, type, full, meta) {
                            return data ? moment(data, 'YYYY-MM-DD').format('DD-MMM-YYYY') : '';
                        }},
                        {data: 'status_id', render:function ( data, type, full, meta ) {
                            if(parseInt(data)===1){
                                var stat="Active";
                                return '<a href="#" role="button" class="btn btn-flat btn-success btn-xs" >'+ stat +'</a>';
                            }else if(parseInt(data)===2){
                                var stat="Inactive";
                                return '<a href="#" role="button" class="btn btn-flat btn-warning btn-xs" >'+ stat +'</a>';
                            }else{
                                var stat ="Deactivated";
                                return '<a href="#" role="button" class="btn btn-flat btn-danger btn-xs" >'+ stat +'</a>';
                            }
                           
                        }},
                        { data: 'id', render: function(data, type, full, meta) {
                            var display_btn = "<div class='btn-grp'>";
                            <?php if(in_array('3', $privileges)){ ?>
                                display_btn += "<a href='#fiscal-modal' data-toggle='modal' class='btn btn-sm edit_me' title='Fiscal Year details'><i class='fa fa-edit'></i></a>";
                            <?php } if(in_array('7', $privileges)){ ?>
                                if(parseInt(full.status_id) ===2){
                                display_btn += '<a href="#" title="Activate Fiscal Year"><span class="fa fa-check-circle text-warning change_status_active"></span></a>';

                                display_btn += '<a href="#" style="padding-left:15px;" title="Deactivate Fiscal Year"><span class="fa fa-ban text-warning change_status_deactivate"></span></a>';
                                } else if (parseInt(full.status_id) ===1){
                                display_btn += '<a href="#"  style="padding-left:15px;" title="Make Fiscal Year Inactive"><span class="fa fa-undo text-danger change_status_inactivate"></span></a>';

                                display_btn += '<a href="#" style="padding-left:15px;"title="Deactivate Fiscal Year"><span class="fa fa-ban text-warning change_status_deactivate"></span></a>';
                                } else {
                                    display_btn += '<a href="#" style="padding-left:15px;" title="Activate Fiscal Year"><span class="fa fa-check-circle text-warning change_status"></span></a>';
                                }

                             <?php }  if(in_array('7', $privileges)){ ?>
                                <!-- display_btn += '<a href="#" style="padding-left:10px;" title="Delete  record"><span class="fa fa-trash text-danger delete_me"></span></a>'; -->
                             <?php } ?>
                                display_btn += "</div>";
                                return display_btn; 
                            }
                        }
                        ],
                        buttons: <?php if(in_array('6', $privileges)){ ?> getBtnConfig('Fiscal Year'), <?php } else { echo "[],"; } ?>
                        responsive: true
                    });
                }
            }

      $('table tbody').on('click', 'tr .change_status_active', function (e) {
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
            var controller = tbl_id.replace("tbl", "");
            var url = "<?php echo site_url(); ?>" + controller.toLowerCase() + "/change_status";

            change_status({id: data.id, status_id: 1}, url, tbl_id);
        });

        $('table tbody').on('click', 'tr .change_status_inactivate', function (e) {
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
            var controller = tbl_id.replace("tbl", "");
            var url = "<?php echo site_url(); ?>" + controller.toLowerCase() + "/inactivate";

            change_status({id: data.id, status_id: 2}, url, tbl_id);
        });

      $('table tbody').on('click', 'tr .change_status_deactivate', function (e) {
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
            var controller = tbl_id.replace("tbl", "");
            var url = "<?php echo site_url(); ?>" + controller.toLowerCase() + "/deactivate";

            change_status({id: data.id, status_id: (parseInt(data.status_id) === 3)}, url, tbl_id);
        });
