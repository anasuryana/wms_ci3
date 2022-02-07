<div style="padding: 10px">
	<div class="col-md-12 order-md-1">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="scr_btn_new"><i class="fas fa-file"></i> </button>
                    <button class="btn btn-primary" id="scr_btn_save"><i class="fas fa-save"></i> </button>
                    <button class="btn btn-primary" id="scr_btn_print"><i class="fas fa-print"></i> </button>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <label class="input-group-text">Document</label>
                    </div>
                    <input type="text" class="form-control" id="scr_txt_doc" placeholder="<<auto number>>" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-primary" id="scr_btnmod"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <label class="input-group-text">Date</label>
                    </div>
                    <input type="text" class="form-control" id="scr_txt_date">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-right">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="scr_btnplus" ><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="scr_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="scr_divku">
                    <table id="scr_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%s">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Doc. of pending</th>
                                <th>Item Code</th>
                                <th>Lot No</th>
                                <th class="text-right">QTY</th>
                                <th class="text-right">Scrap QTY</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>