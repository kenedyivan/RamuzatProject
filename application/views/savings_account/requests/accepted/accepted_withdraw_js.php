
if ($("#tblAcceptedWithdraw_requests").length && tabClicked === "tab-accepted_withdraw_requests") {
    if (typeof (dTable['tblAcceptedWithdraw_requests']) !== 'undefined') {
        $(".tab-pane").removeClass("active");
        $(".savings").removeClass("active");
        $("#tab-accepted_withdraw_requests").addClass("active");
        dTable['tblAcceptedWithdraw_requests'].ajax.reload(null, true);
    } else {
        dTable['tblAcceptedWithdraw_requests'] = $('#tblAcceptedWithdraw_requests').DataTable({
            "dom": '<"html5buttons"B>lTfgitp',
            order: [[1, 'asc']],
            deferRender: true,
            ajax: {
                "url": "<?php echo site_url('u/Withdraw_requests/get_withdraw_requestsToJson/2') ?>",
                "dataType": "json",
                "type": "POST",
                "dataSrc": function ( json ) {
                    savingsModel.accepted_requests_totals(json.data.length);
                    return json.data;
                } 
            },
            "columnDefs": [{
                "targets": [3],
                "orderable": false,
                "searchable": false
            }],
            columns: [
                {
                    data: 'account_no_id', render: function (data, type, full, meta) {
                        if (type === "sort" || type === "filter") {
                            return data;
                        }
                        return "<a href='#'>" + data + "</a>";
                    }
                },
                {
                    data: 'member_id', render: function (data, type, full, meta) {
                        return full.salutation +" " +full.firstname +" "+ full.lastname;
                    }
                },
                {
                    data: 'amount', render: function (data, type, full, meta) {
                        return data;
                    }
                },
                {
                    data: 'reason', render: function (data, type, full, meta) {
                        return data;
                    }
                },
            ],
            buttons: <?php if(in_array('6', $savings_privilege)){ ?> getBtnConfig('Charges'), <?php } else { echo "[],"; } ?>
responsive: true
});
}
}



