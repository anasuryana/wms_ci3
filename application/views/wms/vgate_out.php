<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="mb-3">
                    <label for="platNomor" class="form-label">Vehicle Registration Number</label>
                    <div class="input-group input-group-sm">
                        <select class="form-control" id="go_platNomor">
                            <option value="-">-</option>
                            <?=$ltrans?>
                        </select>
                        <button class="btn btn-sm btn-outline-primary" id="go_conf_btn_sync" onclick="go_conf_btn_sync_e_click()"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-1">
            </div>
        </div>
        <div class="row">            
            <div class="col-md-6 mb-1 text-end">
                <button class="btn btn-sm btn-outline-primary" id="go_conf_btn_confirm" onclick="go_conf_btn_confirm_e_click()">Confirm</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <table id="go_conf_tbl" class="table table-sm table-striped table-bordered table-hover compact" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">TX ID</th>
                            <th class="text-center">Consignee</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</div>