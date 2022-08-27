<style>

</style>
<div class="modal fade" id="gen_loan_report" tabindex="-1" role="dialog" aria-labelledby="printLayoutTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:  80vw; width: 80vw;">
        <div class="modal-content">
            <div class="d-flex flex-row-reverse mt-4 ml-4 mr-4">
                <button onclick="printJS({printable: 'printable_gen_loan_report', type: 'html', targetStyles: ['*'], documentTitle: 'Income-Statement'})" type="button" class="btn btn-primary mx-1">Print</button>
                <button type="button" class="btn btn-secondary mx-1" data-dismiss="modal">Close</button>

            </div>
            <div class="modal-body">

                <div id="printable_gen_loan_report">
                    <div class="row d-flex flex-column align-items-center mx-auto">
                        <img style="height: 50px;" src="<?php echo base_url("uploads/organisation_" . $_SESSION['organisation_id'] . "/logo/" . $org['organisation_logo']);  ?>" alt="logo">

                        <div class="mx-auto text-center mb-2">
                            <span>
                                <?php echo $org['name']; ?> ,
                            </span>
                            <span>
                                <?php echo $branch['physical_address']; ?>, <?php echo $branch['branch_name']; ?>
                            </span><br>
                            <span>
                                <?php echo $branch['postal_address']; ?> ,
                            </span>
                            <span>
                                <b>Tel:</b> <?php echo $branch['office_phone']; ?>
                            </span>
                            <br><br>
                        </div>
                        <h3 class="text-success text-center mb-4">
                            GENERAL LOAN REPORT
                        </h3>
                    </div>

                    <div>
                        <table class="table table-sm table-bordered mx-auto">
                            <thead>
                                <tr>
                                    <th style="background-color: #3057d6; color: #fff;">PARTICULARS</th>
                                    <th style="background-color: #3057d6; color: #fff;">LOAN PRODUCT</th>
                                    <th style="background-color: #3057d6; color: #fff;">NUMBERS/AMOUNT</th>
                                    <th style="background-color: #3057d6; color: #fff;">PERCENTAGE(%)</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr data-bind="visible: $root.statuses()">
                                    <td colspan="4">
                                        <h3 class="no-margins" style="color: #1C84C9;font-weight: bold;">LOAN STATUSES
                                        </h3>
                                    </td>
                                </tr>

                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Applications<br> (Partial, Pending & Approved)</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: $root.Applications -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(total,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(total)/parseInt( ($root.Applications_total())?$root.Applications_total():1 ))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.statuses()" style="border: 1px solid black; ">
                                    <td style="border: 1px solid black;">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: $root.Applications_total"></h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: ($root.Applications_total())?'100%':'0%'"></h3>
                                    </td>
                                </tr>

                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Active Loans<br> (Active & Loacked)</h4>
                                    </td>
                                </tr>
                                <!-- ko foreach: $root.Active_loans -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(total,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(total)/parseInt( ($root.Active_loans_total())?$root.Active_loans_total():1 ))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.statuses()" style="border: 1px solid black; ">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: $root.Active_loans_total"></h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: ($root.Active_loans_total())?'100%':'0%'"></h3>
                                    </td>
                                </tr>


                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Closed Loans<br> (Paid-off, Refinaced, Obligations-met)</h4>
                                    </td>
                                </tr>
                                <!-- ko foreach: $root.Closed_loans -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(total,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(total)/parseInt( ($root.Closed_loans_total())?$root.Closed_loans_total():1 ))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: $root.Closed_loans_total"></h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: ($root.Closed_loans_total())?'100%':'0%'"></h3>
                                    </td>
                                </tr>

                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Loans In Arrears <br>(Past Lona Tenure)</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: $root.In_arrear_loans -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(total,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(total)/parseInt( ($root.In_arrear_loans_total())?$root.In_arrear_loans_total():1 ))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: $root.In_arrear_loans_total"></h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: ($root.In_arrear_loans_total())?'100%':'0%'"></h3>
                                    </td>
                                </tr>


                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Written off Loans</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: $root.written_off_loans -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(total,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(total)/parseInt( ($root.written_off_loans_total())?$root.written_off_loans_total():1 ))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.statuses()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: $root.written_off_loans_total"></h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: ($root.written_off_loans_total())?'100%':'0%'"></h3>
                                    </td>
                                </tr>


                                <tr data-bind="visible: $root.amounts()">
                                    <td colspan="4">
                                        <h3 style="color: #1C84C9;font-weight: bold;"> LOAN AMOUNTS</h3>
                                    </td>
                                </tr>
                                <!-- ko with: $root.loan_amounts -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Disbursed Amount</h4>
                                    </td>
                                </tr>
                                <!-- ko foreach: disbursed -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().disbursed)?$root.loan_amounts_totals().disbursed:1 ):1))*100,2)+'%'">
                                    </td>

                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().disbursed,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().disbursed)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Collected Amount</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: collected -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().collected)?$root.loan_amounts_totals().collected:1 ):1))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().collected,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:  (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().collected)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Outstanding Amount</h4>
                                    </td>

                                </tr>

                                <!-- ko foreach: outstanding -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().outstanding)?$root.loan_amounts_totals().outstanding:1 ):1))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().outstanding,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:  (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().outstanding)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Loan Interest</h4>
                                    </td>

                                </tr>

                                <!-- ko foreach: interest -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().interest)?$root.loan_amounts_totals().interest:1 ):1))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().interest,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:  (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().interest)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Loan Penalty</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: penalty -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().penalty)?$root.loan_amounts_totals().penalty:1 ):1))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().penalty,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:  (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().penalty)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Written off Amount</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: written_off -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().written_off)?$root.loan_amounts_totals().written_off:1 ):1))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:(typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().written_off,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:  (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().written_off)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Average loan balance</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: average_loan_balance -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().average_loan_balance)?$root.loan_amounts_totals().average_loan_balance:1 ):1))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().average_loan_balance,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:  (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().average_loan_balance)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">
                                        <h4>Projected Loan Interest</h4>
                                    </td>

                                </tr>
                                <!-- ko foreach: projected_interest_amount -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_amounts_totals() !='undefined')? (($root.loan_amounts_totals().projected_interest_amount)?$root.loan_amounts_totals().projected_interest_amount:1 ):1))*100,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.amounts()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_amounts_totals() !='undefined')?curr_format(round($root.loan_amounts_totals().projected_interest_amount,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text:  (typeof $root.loan_amounts_totals() !='undefined')?(($root.loan_amounts_totals().projected_interest_amount)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <!-- /ko -->

                                <tr data-bind="visible: $root.portfolio()">
                                    <td colspan="4">
                                        <h3 style="color: #1C84C9;font-weight: bold;">LOAN PORTFOLIO</h3>
                                    </td>
                                </tr>
                                <!-- ko with: $root.loan_portfolio -->
                                <tr data-bind="visible: $root.portfolio()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">Portfolio pending approval
                                    </td>

                                </tr>
                                <!-- ko foreach: portfolio_pending_approval -->
                                <tr data-bind="visible: $root.portfolio()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_portfolio_totals() !='undefined')? (($root.loan_portfolio_totals().portfolio_pending_approval)?$root.loan_portfolio_totals().portfolio_pending_approval:1 ):1))*100,2,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.portfolio()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_portfolio_totals() !='undefined')?curr_format(round($root.loan_portfolio_totals().portfolio_pending_approval,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_portfolio_totals() !='undefined')?(($root.loan_portfolio_totals().portfolio_pending_approval)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>

                                <tr data-bind="visible: $root.portfolio()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">Gross loan portfolio</td>

                                </tr>
                                <!-- ko foreach: gross_loan_portfolio -->
                                <tr data-bind="visible: $root.portfolio()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.loan_portfolio_totals() !='undefined')? (($root.loan_portfolio_totals().gross_loan_portfolio)?$root.loan_portfolio_totals().gross_loan_portfolio:1 ):1))*100,2,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.portfolio()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_portfolio_totals() !='undefined')?curr_format(round($root.loan_portfolio_totals().gross_loan_portfolio,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.loan_portfolio_totals() !='undefined')?(($root.loan_portfolio_totals().gross_loan_portfolio)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <!-- /ko -->

                                <tr data-bind="visible: $root.indicators()">
                                    <td colspan="4">
                                        <h3 style="color: #1C84C9;font-weight: bold;">RISK INDICATORS</h3>
                                    </td>
                                </tr>

                                <!-- ko with: $root.risk_indicators -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">Unpaid Penalty</td>

                                </tr>
                                <!-- ko foreach: unpaid_penalty -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.risk_indicators_totals() !='undefined')? (($root.risk_indicators_totals().unpaid_penalty)?$root.risk_indicators_totals().unpaid_penalty:1 ):1))*100,2,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?curr_format(round($root.risk_indicators_totals().unpaid_penalty,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?(($root.risk_indicators_totals().unpaid_penalty)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>


                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">Value at Risk</td>

                                </tr>
                                <!-- ko foreach: value_at_risk -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.risk_indicators_totals() !='undefined')? (($root.risk_indicators_totals().value_at_risk)?$root.risk_indicators_totals().value_at_risk:1 ):1))*100,2,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?curr_format(round($root.risk_indicators_totals().value_at_risk,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?(($root.risk_indicators_totals().value_at_risk)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>


                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">Portifolio at Risk</td>

                                </tr>
                                <!-- ko foreach: portfolio_at_risk -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.risk_indicators_totals() !='undefined')? (($root.risk_indicators_totals().portfolio_at_risk)?$root.risk_indicators_totals().portfolio_at_risk:1 ):1))*100,2,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?curr_format(round($root.risk_indicators_totals().portfolio_at_risk,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?(($root.risk_indicators_totals().portfolio_at_risk)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>

                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="attr:{'rowspan': $root.rowSpan_value()}">Interest in Suspense </td>

                                </tr>
                                <!-- ko foreach: intrest_in_suspense -->
                                <tr data-bind="visible: $root.indicators()">
                                    <td data-bind="text: product_name"></td>
                                    <td data-bind="text: curr_format(round(amount,2)*1)"></td>
                                    <td data-bind="text: round((parseInt(amount)/parseInt( 
                                (typeof $root.risk_indicators_totals() !='undefined')? (($root.risk_indicators_totals().intrest_in_suspense)?$root.risk_indicators_totals().intrest_in_suspense:1 ):1))*100,2,2)+'%'">
                                    </td>
                                </tr>
                                <!-- /ko -->
                                <tr style="border: 1px solid black; " data-bind="visible: $root.indicators()">
                                    <td style="border: 1px solid black; ">
                                        <h3>Total</h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?curr_format(round($root.risk_indicators_totals().intrest_in_suspense,2)*1):''">
                                        </h3>
                                    </td>
                                    <td style="border: 1px solid black; ">
                                        <h3 data-bind="text: (typeof $root.risk_indicators_totals() !='undefined')?(($root.risk_indicators_totals().intrest_in_suspense)?'100%':'0%'):'0%'">
                                        </h3>
                                    </td>
                                </tr>
                                <!-- /ko -->
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button onclick="printJS({printable: 'printable_gen_loan_report', type: 'html', targetStyles: ['*'], documentTitle: 'Income-Statement'})" type="button" class="btn btn-primary">Print</button>
                </div>
            </div>
        </div>
    </div>