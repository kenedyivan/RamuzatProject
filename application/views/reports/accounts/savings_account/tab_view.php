<div role="tabpanel" id="tab-savings_report" class="tab-pane">
    <div class="panel-body">
        <div style="text-align: center;" id="reports_title"><h3>Savings Accounts - Reports</h3></div>
        <div class="row">
        <div class="float-right col-lg-12">
            <div class="form-row col-md-12">
                <label class="col-form-label">From<span class="text-danger">*</span></label>
                <div class="col-md-2 form-group">
                    <div class="input-group date">
                        <input class="form-control" id="start" autocomplete="off" name="start_date" data-bind="datepicker: $root.start_date,textInput:$root.start_date, event:{ change: $root.updateData}" type="text">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <label class="col-form-label"> To<span class="text-danger">*</span></label>
                <div class="col-md-2 form-group">
                    <div class="input-group date">
                        <input class="form-control" id="end" autocomplete="off" name="end_date" data-bind="datepicker: $root.end_date,textInput:$root.end_date, event:{ change: $root.updateData}" type="text"/><span  class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
             
                
                <div class="col-md-1 form-group">
                    <button type="button" class="btn mb-1 btn-primary" onclick="filter_savings_reports_data(event)"><i class="fa fa-filter"></i> Filter</button>

                </div>
            </div>
        </div>
        <br>
        <br>
            
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-bordered display compact nowrap table-hover" width="100%"
                           id="tbl_savings_report">
                        <thead>
                        <tr>
                            <th>Account No</th>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Minimum Balance</th>
                            <th>Locked Amount</th>
                            <th>Cash Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Account No</th>
                            <th>Name</th>
                            <th>Product</th>
                            <th>Minimum Balance</th>
                            <th>Locked Amount</th>
                            <th>Cash Balance</th>
                        </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>