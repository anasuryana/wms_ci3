<div style="padding: 10px" onpaste="shpfgoth_e_pastecol1(event)" tabindex="0">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="shpfgoth_btnnew" onclick="" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="shpfgoth_btnsave" class="btn btn-outline-primary" ><i class="fas fa-save"></i></button>
                    <button title="Print" id="shpfgoth_btnprnt" class="btn btn-outline-primary" ><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <input type="hidden" id="shpfgoth_isedit" value="n">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" title="Business Group">BG</span>
                    <select class="form-select" id="shpfgoth_sel_bg" data-style="btn-info" onchange="shpfgoth_e_onchange_bg()">
                        <option value="-">-</option>
                        <?php
foreach ($lbg as $r) {
    ?>
                            <option value="<?=trim($r->MBSG_BSGRP)?>"><?=$r->MBSG_DESC?></option>
                            <?php
}
?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Customer</span>
                    <select class="form-select" id="shpfgoth_sel_cus" data-style="btn-info">
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >SI Doc</span>
                    <input type="text" class="form-control" id="shpfgoth_txt_doc" required value="<<AutoNumber>>" readonly>
                    <button class="btn btn-primary" id="shpfgoth_btn_finddoc"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" title="Warehouse Code">WH</span>
                    <select class="form-select" id="shpfgoth_cmb_wh" ><?=$lwh?></select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <span class="badge bg-info" id="shpfgoth_lblinfo_d"></span>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" id="shpfgoth_btngetpo" title="get SO and Price ">Get SO and Price</button>
                    <button class="btn btn-primary" id="shpfgoth_btnplus"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="shpfgoth_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="shpfgoth_divku">
                    <table id="shpfgoth_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th><!-- 0 -->
                                <th>PO No</th><!-- 1 -->
                                <th>Item Code</th><!-- 2 -->
                                <th>Model</th><!-- 3 -->
                                <th><i class="fas fa-calendar-alt"></i> Req. Date</th><!-- 4 -->
                                <th>Req. QTY</th><!-- 5 -->
                                <th>Remark</th><!-- 6 -->
                                <th>Plant</th><!-- 7 -->
                                <th>ETA</th><!-- 8 -->
                                <th class="d-none">LINENO</th> <!-- 9 -->
                                <th >SO</th><!-- 10 -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td contenteditable="true"></td>
                                <td class="d-none"></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="shpfgoth_MOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">SI List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <input type="text" class="form-control" id="shpfgoth_txtsearch" onkeypress="shpfgoth_e_search(event)" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search by</span>
                        <select id="shpfgoth_itm_srchby" onchange="shpfgoth_e_fokus()" class="form-select">
                            <option value="si">SI Code</option>
                            <option value="cs">Customer</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-center">
                    <div class="form-check-inline">
                        <label class="form-check-label">
                           List type
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="shpfgoth_rad_today" onclick="shpfgoth_e_fokus()" class="form-check-input" name="optradio" value="today" checked>
                        <label class="form-check-label" for="shpfgoth_rad_today">
                        Today
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="shpfgoth_rad_all" onclick="shpfgoth_e_fokus()" class="form-check-input" name="optradio" value="all">
                        <label class="form-check-label" for="shpfgoth_rad_all">
                        All
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-end">
                    <span class="badge bg-info" id="shpfgoth_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="shpfgoth_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>SI Code</th>
                                    <th class="d-none">Customer ID</th>
                                    <th>Customer</th>
                                    <th class="d-none">BG</th>
                                    <th>Business Group</th>
                                    <th title="Warehouse Code">WH</th>
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
    </div>
</div>
<div class="modal fade" id="shpfgoth_PO">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">SO Plotting</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <span class="badge bg-info" id="shpfgoth_tbosso_info"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="shpfgoth_divostSO">
                        <table id="shpfgoth_tbosso" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="6" class="text-center">Outstanding SO</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">SO</th>
                                    <th rowspan="2" class="align-middle text-center">Assy Code</th>
                                    <th rowspan="2" class="align-middle text-center">Price</th>
                                    <th colspan="2" class="text-center">Qty</th>
                                    <th rowspan="2" class="d-none">LINE</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Order</th>
                                    <th class="text-center">Ost</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="shpfgoth_tbl_plotso_info"></span>
                </div>
                <div class="col mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="shpfgmod_btnfifo" title="Auto FIFO" onclick="shpfgoth_e_plot()">FIFO</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="shpfgoth_divplotSO">
                        <table id="shpfgoth_tbl_plotso" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="6" class="text-center">Plotted SO</th>
                                </tr>
                                <tr>
                                    <th  class="text-center">SO</th> <!-- 0-->
                                    <th  class="d-none">so line</th> <!-- 1-->
                                    <th  class="text-center">Assy Code</th><!-- 2-->
                                    <th  class="text-center">Price</th> <!-- 3-->
                                    <th  class="text-center">Qty Req</th><!-- 4-->
                                    <th  class="text-center">Qty SO Plot</th><!-- 5-->
                                    <th  class="d-none">idsi</th>       <!-- 6-->
                                    <th  class="d-none">idmaintbl_idx</th>       <!-- 7-->
                                    <th  class="text-center"></th><!-- 8-->
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
    </div>
</div>
<script>
    var shpfgoth_tbllength         = 1;
    var shpfgoth_tblrowindexsel    = '';
    var shpfgoth_tblcolindexsel    = '';
    var shpfgoth_e_col1;

    function shpfgoth_e_plot(){
        document.getElementById('shpfgmod_btnfifo').disabled=true;
        document.getElementById('shpfgmod_btnfifo').innerText = 'Please wait';
        let promise = new Promise((resolve, reject) => {
            setTimeout(() => {
                let tabell = document.getElementById("shpfgoth_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let mrows = tableku2.getElementsByTagName("tr");
                let ttlrows =  mrows.length;

                let tabel_OSSO = document.getElementById("shpfgoth_tbosso");
                let tabel_OSSO_body0 = tabel_OSSO.getElementsByTagName("tbody")[0];
                let ttlrows_OSSO = tabel_OSSO_body0.getElementsByTagName("tr").length;

                let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
                let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;

                if(ttlrows_PLOT>0){
                    document.getElementById('shpfgmod_btnfifo').disabled=false;
                    document.getElementById('shpfgmod_btnfifo').innerText='FIFO';

                    let ca_resumeplotted = [];
                    let listre_fifo = [];
                    if(document.getElementById('shpfgoth_isedit').value=='y'){
                        for(let i=0;i<ttlrows_PLOT; i++){
                            let isfound = false;
                            for(let c in ca_resumeplotted){
                                if(ca_resumeplotted[c].mindex == Number(tabel_PLOT_body0.rows[i].cells[7].innerText)){
                                    ca_resumeplotted[c].mqtysup += numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value();
                                    isfound =true; break;
                                }
                            }
                            if(!isfound){
                                let newobku = {mindex : Number(tabel_PLOT_body0.rows[i].cells[7].innerText),
                                                mqtyreq: numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value()
                                                ,mqtysup : numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value() };
                                ca_resumeplotted.push(newobku);
                            }
                        }

                        for(let i=0;i<ttlrows; i++){
                            let isfoundplot = false;
                            for(let k in ca_resumeplotted){
                                if(ca_resumeplotted[k].mindex==i){
                                    let maintblqty = numeral(tableku2.rows[ca_resumeplotted[k].mindex].cells[5].innerText).value();
                                    if(maintblqty!=ca_resumeplotted[k].mqtyreq){
                                        isfoundplot=true;
                                        tableku2.rows[i].cells[10].innerText = 'not ok';
                                        tableku2.rows[i].cells[10].style.backgroundColor = 'Crimson';
                                        let isfound = false;
                                        for(let c in listre_fifo){
                                            if(listre_fifo[c].mindex == ca_resumeplotted[k].mindex){
                                                isfound =true; break;
                                            }
                                        }
                                        if(!isfound){
                                            let newobku = {mindex : ca_resumeplotted[k].mindex };
                                            listre_fifo.push(newobku);
                                        }
                                    }
                                }
                            }
                            if(!isfoundplot){
                                let newobku = {mindex : i };
                                listre_fifo.push(newobku);
                            }
                        }

                        if(listre_fifo.length>0){//saved mode
                            let konfirm = confirm("Re-fifo is required, are You sure want to continue ?");
                            if(konfirm){
                                // let listre_fifo_plot_4delete = [];
                                let listre_fifo_os =[];
                                for(let i in listre_fifo){
                                    for(let b=0;b<ttlrows_PLOT;b++){
                                        if(tabel_PLOT_body0.rows[b].cells[7].innerText==listre_fifo[i].mindex){
                                            let newobj  = {so:tabel_PLOT_body0.rows[b].cells[0].innerText.trim()
                                                        ,soline: tabel_PLOT_body0.rows[b].cells[1].innerText
                                                        ,soitem: tabel_PLOT_body0.rows[b].cells[2].innerText.trim()
                                                        ,qtyret: numeral(tabel_PLOT_body0.rows[b].cells[5].innerText).value() };
                                            listre_fifo_os.push(newobj);
                                            tabel_PLOT_body0.rows[b].cells[4].innerText=0;
                                            tabel_PLOT_body0.rows[b].cells[5].innerText=0;
                                            // listre_fifo_plot_4delete.push(b);
                                        }
                                    }
                                }
                                for(let i in listre_fifo_os){
                                    for(let r = 0 ; r<ttlrows_OSSO; r++){
                                        if(listre_fifo_os[i].so==tabel_OSSO_body0.rows[r].cells[0].innerText.trim()
                                        && listre_fifo_os[i].soline==tabel_OSSO_body0.rows[r].cells[5].innerText.trim()
                                        && listre_fifo_os[i].soitem==tabel_OSSO_body0.rows[r].cells[1].innerText.trim()
                                        ){
                                            tabel_OSSO_body0.rows[r].cells[4].innerText = numeral(tabel_OSSO_body0.rows[r].cells[4].innerText).value()+Number(listre_fifo_os[i].qtyret);
                                        }
                                    }
                                }
                                let areq = [];
                                let asup = [];
                                for(let f in listre_fifo){
                                    for(let i=0;i<ttlrows; i++){
                                        if(listre_fifo[f].mindex==i){//tableku2.rows[i].cells[14].innerText
                                            let qty = numeral(tableku2.rows[i].cells[5].innerText).value();
                                            let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[2].innerText,
                                                        reqqty: qty, supqty : 0};
                                            areq.push(anobj);
                                            break;
                                        }
                                    }
                                }
                                // console.log('edit save mode before think fplot promise');
                                shpfgoth_e_thinkplot(areq,asup);
                            }
                        } else {
                            alertify.message('nothing difference edit mode');
                        }
                    } else {
                        //get distinct plotted so
                        for(let i=0;i<ttlrows_PLOT; i++){
                            let isfound = false;
                            for(let c in ca_resumeplotted){
                                if(ca_resumeplotted[c].mindex == Number(tabel_PLOT_body0.rows[i].cells[7].innerText)){
                                    ca_resumeplotted[c].mqtysup += numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value();
                                    isfound =true; break;
                                }
                            }
                            if(!isfound){
                                let newobku = {mindex : Number(tabel_PLOT_body0.rows[i].cells[7].innerText),
                                                mqtyreq: numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value()
                                                ,mqtysup : numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value() };
                                ca_resumeplotted.push(newobku);
                            }
                        }

                        for(let i in ca_resumeplotted){
                            let maintblqty = numeral(tableku2.rows[ca_resumeplotted[i].mindex].cells[5].innerText).value();
                            if(maintblqty!=ca_resumeplotted[i].mqtyreq){
                                tableku2.rows[ca_resumeplotted[i].mindex].cells[10].innerText = 'not ok';
                                tableku2.rows[ca_resumeplotted[i].mindex].cells[10].style.backgroundColor = 'Crimson';
                                let isfound = false;
                                for(let c in listre_fifo){
                                    if(listre_fifo[c].mindex == ca_resumeplotted[i].mindex){
                                        isfound =true; break;
                                    }
                                }
                                if(!isfound){
                                    let newobku = {mindex : ca_resumeplotted[i].mindex };
                                    listre_fifo.push(newobku);
                                }
                            }
                        }

                        if(listre_fifo.length>0){
                            let konfirm = confirm("Re-fifo is required, are You sure want to continue ?");
                            if(konfirm){
                                let listre_fifo_plot_4delete = [];
                                let listre_fifo_os =[];
                                for(let i in listre_fifo){
                                    for(let b=0;b<ttlrows_PLOT;b++){
                                        if(Number(tabel_PLOT_body0.rows[b].cells[7].innerText)==listre_fifo[i].mindex){
                                            let newobj  = {so:tabel_PLOT_body0.rows[b].cells[0].innerText.trim()
                                                        ,soline: tabel_PLOT_body0.rows[b].cells[1].innerText
                                                        ,soitem: tabel_PLOT_body0.rows[b].cells[2].innerText.trim()
                                                        ,qtyret: numeral(tabel_PLOT_body0.rows[b].cells[5].innerText).value() };
                                            listre_fifo_os.push(newobj);
                                            listre_fifo_plot_4delete.push(b);
                                        }
                                    }
                                }
                                for(let i in listre_fifo_os){
                                    for(let r = 0 ; r<ttlrows_OSSO; r++){
                                        if(listre_fifo_os[i].so==tabel_OSSO_body0.rows[r].cells[0].innerText.trim()
                                        && listre_fifo_os[i].soline==tabel_OSSO_body0.rows[r].cells[5].innerText.trim()
                                        && listre_fifo_os[i].soitem==tabel_OSSO_body0.rows[r].cells[1].innerText.trim()
                                        ){
                                            tabel_OSSO_body0.rows[r].cells[4].innerText = numeral(tabel_OSSO_body0.rows[r].cells[4].innerText).value()+Number(listre_fifo_os[i].qtyret);
                                        }
                                    }
                                }
                                for(let i =listre_fifo_plot_4delete.length-1;i>-1;i--){
                                    tabel_PLOT_body0.deleteRow(listre_fifo_plot_4delete[i]);
                                }
                                let areq = [];
                                let asup = [];
                                for(let f in listre_fifo){
                                    for(let i=0;i<ttlrows; i++){
                                        if(listre_fifo[f].mindex==i){
                                            let qty = numeral(tableku2.rows[i].cells[5].innerText).value();
                                            let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[2].innerText,
                                                        reqqty: qty, supqty : 0};
                                            areq.push(anobj);
                                            break;
                                        }
                                    }
                                }

                                shpfgoth_e_thinkplot(areq,asup);
                            }
                        } else {
                            alertify.message('Already plotted');
                        }
                    }

                } else {
                    let areq = [];
                    let asup = [];
                    for(let i=0;i<ttlrows; i++){
                        let qty = numeral(tableku2.rows[i].cells[5].innerText).value();
                        let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[2].innerText,
                                    reqqty: qty, supqty : 0};
                        areq.push(anobj);
                    }
                    shpfgoth_e_thinkplot(areq,asup);
                }
            },50);
        });
    }

    function shpfgoth_e_thinkplot(areq, asup){
        let tabell = document.getElementById("shpfgoth_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let ttlrows = tableku2.getElementsByTagName("tr").length;


        let tabel_OSSO = document.getElementById("shpfgoth_tbosso");
        let tabel_OSSO_body0 = tabel_OSSO.getElementsByTagName("tbody")[0];
        let ttlrows_OSSO = tabel_OSSO_body0.getElementsByTagName("tr").length;

        let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        for(let i in areq){
            let think1 = true;
            let itemreq = areq[i].reqitem.trim();
            let itemreqindex = areq[i].reqindex;
            while(think1){
                let iscontinue = false;
                for(let u =0; u< ttlrows_OSSO; u++){
                    let itemso = tabel_OSSO_body0.rows[u].cells[1].innerText.trim();
                    let itemsoqty = numeral(tabel_OSSO_body0.rows[u].cells[4].innerText).value();
                    if(itemreq==itemso && itemsoqty>0 ){
                        iscontinue = true; break;
                    }
                }
                if(iscontinue){
                    for(let u =0; u< ttlrows_OSSO; u++){
                        let itemso = tabel_OSSO_body0.rows[u].cells[1].innerText.trim();
                        if(itemreq == itemso){
                            let think2 = true;
                            while(think2){
                                if(Number(areq[i].reqqty) > Number(areq[i].supqty)){
                                    if(numeral(tabel_OSSO_body0.rows[u].cells[4].innerText).value()>0){

                                        let isfound = false;
                                        areq[i].supqty +=1;
                                        tabel_OSSO_body0.rows[u].cells[4].innerText = numeral(tabel_OSSO_body0.rows[u].cells[4].innerText).value()-1;
                                        for(let c in asup){
                                            if(asup[c].pindex==itemreqindex
                                                && asup[c].pso==tabel_OSSO_body0.rows[u].cells[0].innerText.trim()
                                                && asup[c].psoline==tabel_OSSO_body0.rows[u].cells[5].innerText.trim() ){
                                                asup[c].pqty += 1;
                                                isfound=true;break;
                                            }
                                        }
                                        if(!isfound){
                                            let newobku = {pitem : itemreq, pso: tabel_OSSO_body0.rows[u].cells[0].innerText.trim(),
                                                psoline : tabel_OSSO_body0.rows[u].cells[5].innerText.trim(),
                                                pprice : tabel_OSSO_body0.rows[u].cells[2].innerText,
                                                pqtyreq : areq[i].reqqty,pqty: 1 ,pindex : itemreqindex};
                                            asup.push(newobku);
                                        }

                                    } else {
                                        think2 =false;
                                    }
                                } else {
                                    if(Number(areq[i].reqqty)==0){
                                        let newobku = {pitem : itemreq, pso: '', psoline: '', pprice: 0,
                                            pqtyreq : areq[i].reqqty, pqty: 0, pindex: itemreqindex };
                                        asup.push(newobku);
                                    }
                                    think2 =false;
                                    think1 = false;
                                }
                            }
                        }
                    }
                } else {
                    think1=false;
                }
            }
        }
        for(let i in asup){
            newrow = tabel_PLOT_body0.insertRow(-1);
            newcell = newrow.insertCell(0);
            newText = document.createTextNode(asup[i].pso);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(1);
            newcell.classList.add('d-none');
            newText = document.createTextNode(asup[i].psoline);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(2);
            newText = document.createTextNode(asup[i].pitem);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(3);
            newText = document.createTextNode(asup[i].pprice);
            newcell.appendChild(newText);
            newcell.classList.add('text-end');
            newcell = newrow.insertCell(4);
            newText = document.createTextNode(asup[i].pqtyreq);
            newcell.appendChild(newText);
            newcell.classList.add('text-end');
            newcell = newrow.insertCell(5);
            newText = document.createTextNode(asup[i].pqty);
            newcell.classList.add('text-end');
            newcell.appendChild(newText);
            newcell = newrow.insertCell(6);
            newcell.classList.add('d-none');
            newText = document.createTextNode('');
            newcell.appendChild(newText);
            newcell = newrow.insertCell(7);
            newcell.classList.add('d-none');
            newText = document.createTextNode(asup[i].pindex);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(8);
            newText = document.createTextNode('');
            newcell.appendChild(newText);
        }
        let ttloksupplied = 0;
        for(let i in areq){
            if(areq[i].reqqty==areq[i].supqty){
                tableku2.rows[areq[i].reqindex].cells[10].innerText ='ok';
                tableku2.rows[areq[i].reqindex].cells[10].style.backgroundColor='Chartreuse';
                ttloksupplied++;
            } else {
                tableku2.rows[areq[i].reqindex].cells[10].innerText ='not ok';
                tableku2.rows[areq[i].reqindex].cells[10].style.backgroundColor='Crimson';
            }
        }
        document.getElementById('shpfgmod_btnfifo').disabled=false;
        document.getElementById('shpfgmod_btnfifo').innerText='FIFO';
        if(ttloksupplied==areq.length){
            $("#shpfgoth_PO").modal('hide') ;
        }
    }


    $("#shpfgoth_btngetpo").click(function (e) {
        getosso_oth();
        $("#shpfgoth_PO").modal('show');
    });

    function getosso_oth(){
        let custbg = document.getElementById('shpfgoth_sel_bg').value;
        if(custbg=='-'){
            alertify.message('Please select business group');
            return;
        }
        let custcd = document.getElementById('shpfgoth_sel_cus').value;
        if(custcd=='-'){
            alertify.message('Please select customer');
            return;
        }
        let tabell = document.getElementById("shpfgoth_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let mitems = [];
        for(let x=0;x<mrows.length;x++){
            let citem = tableku2.rows[x].cells[2].innerText.trim();
            if(mitems.indexOf(citem)===-1 && citem!=''){
                mitems.push(citem);
            }
        }
        if(mitems.length==0){
            alertify.message('there is no item code in the table below');
            return;
        }
        document.getElementById('shpfgoth_tbosso_info').innerText = "Please wait..."
        const wh = document.getElementById('shpfgoth_cmb_wh').value
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/get_ostso')?>",
            data: {incustomer: custcd, inbg : custbg, initems: mitems, wh: wh },
            dataType: "json",
            success: function (response) {
                document.getElementById('shpfgoth_tbosso_info').innerText = "";
                if(response.status[0].cd!='0'){
                    let mydes = document.getElementById("shpfgoth_divostSO");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("shpfgoth_tbosso");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("shpfgoth_tbosso");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let ttlrows = response.data.length;
                    for (let i = 0; i<ttlrows; i++){
                        let ostqty =  numeral(response.data[i].SSO2_ORDQT).value()-numeral(response.data[i].SSO2_DELQT).value();
                        ostqty-= shpfgoth_e_get_qty_plotted(response.data[i].SSO2_CPONO.trim(),response.data[i].SSO2_SOLNO,response.data[i].SSO2_MDLCD.trim());
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.data[i].SSO2_CPONO);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].SSO2_MDLCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(response.data[i].SSO2_SLPRC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(response.data[i].SSO2_ORDQT).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(ostqty).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.classList.add('d-none');
                        newText = document.createTextNode(response.data[i].SSO2_SOLNO);
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    $("#shpfgoth_tbosso tbody").empty();
                    $("#shpfgoth_tbl_plotso tbody").empty();
                    alertify.message(response.status[0].msg);
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
    function shpfgoth_e_onchange_bg(){
        let bg = document.getElementById('shpfgoth_sel_bg').value;
        document.getElementById('shpfgoth_sel_cus').innerHTML = '<option value="-">Please wait</option>';
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/get_customer_ost_so')?>",
            data: {inbg : bg},
            dataType: "json",
            success: function (response) {
                if(response.status[0].cd!='0'){
                    let strtodis  = '<option value="-">Choose</option>';
                    for(let i=0;i<response.data.length; i++){
                        if(response.data[i].SSO2_CUSCD.trim()=='IEP001U'){

                        } else {
                            strtodis += '<option value="'+response.data[i].SSO2_CUSCD.trim()+'">'+response.data[i].MCUS_CUSNM.trim()+'</option>';
                        }
                    }
                    document.getElementById('shpfgoth_sel_cus').innerHTML = strtodis;
                } else {
                    document.getElementById('shpfgoth_sel_cus').innerHTML = '<option value="-">-</option>';
                    alertify.message(response.status[0].msg);
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function shpfgoth_e_checkeditedqty_plot(){
        let tabell = document.getElementById("shpfgoth_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let ttlrows = tableku2.getElementsByTagName("tr").length;

        let tabel_OSSO = document.getElementById("shpfgoth_tbosso");
        let tabel_OSSO_body0 = tabel_OSSO.getElementsByTagName("tbody")[0];
        let ttlrows_OSSO = tabel_OSSO_body0.getElementsByTagName("tr").length;

        let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        let ca_resumeplotted = [];
        let listre_fifo = [];
        if(document.getElementById('shpfgoth_isedit').value=='y'){
            //handle edited qty after saved
            for(let i=0;i<ttlrows_PLOT; i++){
                let isfound = false;
                for(let c in ca_resumeplotted){
                    if(ca_resumeplotted[c].mindex == Number(tabel_PLOT_body0.rows[i].cells[7].innerText)){
                        ca_resumeplotted[c].mqtysup += numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value();
                        isfound =true; break;
                    }
                }
                if(!isfound){
                    let newobku = {mindex : Number(tabel_PLOT_body0.rows[i].cells[7].innerText),
                                    mqtyreq: numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value()
                                    ,mqtysup : numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value() };
                    ca_resumeplotted.push(newobku);
                }
            }

            // for(let i in ca_resumeplotted){
            //     let maintblqty = numeral(tableku2.rows[ca_resumeplotted[i].mindex].cells[5].innerText).value();
            //     if(maintblqty!=ca_resumeplotted[i].mqtyreq){
            //         tableku2.rows[ca_resumeplotted[i].mindex].cells[10].innerText = 'not ok';
            //         tableku2.rows[ca_resumeplotted[i].mindex].cells[10].style.backgroundColor = 'Crimson';
            //         let isfound = false;
            //         for(let c in listre_fifo){
            //             if(listre_fifo[c].mindex == ca_resumeplotted[i].mindex){
            //                 isfound =true; break;
            //             }
            //         }
            //         if(!isfound){
            //             let newobku = {mindex : ca_resumeplotted[i].mindex };
            //             listre_fifo.push(newobku);
            //         }
            //     }
            // }

            for(let i=0;i<ttlrows; i++){
                let isfoundplot = false;
                for(let k in ca_resumeplotted){
                    if(ca_resumeplotted[k].mindex==i){
                        let maintblqty = numeral(tableku2.rows[ca_resumeplotted[k].mindex].cells[5].innerText).value();
                        if(maintblqty!=ca_resumeplotted[k].mqtyreq){
                            isfoundplot=true;
                            tableku2.rows[i].cells[10].innerText = 'not ok';
                            tableku2.rows[i].cells[10].style.backgroundColor = 'Crimson';
                            let isfound = false;
                            for(let c in listre_fifo){
                                if(listre_fifo[c].mindex == ca_resumeplotted[k].mindex){
                                    isfound =true; break;
                                }
                            }
                            if(!isfound){
                                let newobku = {mindex : ca_resumeplotted[k].mindex };
                                listre_fifo.push(newobku);
                            }
                        }
                    }
                }
                if(!isfoundplot){
                    let newobku = {mindex : i };
                    listre_fifo.push(newobku);
                }
            }

            if(listre_fifo.length>0){//saved mode
                let konfirm = confirm("Re-fifo is required, are You sure want to continue ?");
                if(konfirm){
                    // let listre_fifo_plot_4delete = [];
                    let listre_fifo_os =[];
                    for(let i in listre_fifo){
                        for(let b=0;b<ttlrows_PLOT;b++){
                            if(tabel_PLOT_body0.rows[b].cells[7].innerText==listre_fifo[i].mindex){
                                let newobj  = {so:tabel_PLOT_body0.rows[b].cells[0].innerText.trim()
                                            ,soline: tabel_PLOT_body0.rows[b].cells[1].innerText
                                            ,soitem: tabel_PLOT_body0.rows[b].cells[2].innerText.trim()
                                            ,qtyret: numeral(tabel_PLOT_body0.rows[b].cells[5].innerText).value() };
                                listre_fifo_os.push(newobj);
                                tabel_PLOT_body0.rows[b].cells[4].innerText=0;
                                tabel_PLOT_body0.rows[b].cells[5].innerText=0;
                                // listre_fifo_plot_4delete.push(b);
                            }
                        }
                    }
                    for(let i in listre_fifo_os){
                        for(let r = 0 ; r<ttlrows_OSSO; r++){
                            if(listre_fifo_os[i].so==tabel_OSSO_body0.rows[r].cells[0].innerText.trim()
                            && listre_fifo_os[i].soline==tabel_OSSO_body0.rows[r].cells[5].innerText.trim()
                            && listre_fifo_os[i].soitem==tabel_OSSO_body0.rows[r].cells[1].innerText.trim()
                            ){
                                tabel_OSSO_body0.rows[r].cells[4].innerText = numeral(tabel_OSSO_body0.rows[r].cells[4].innerText).value()+Number(listre_fifo_os[i].qtyret);
                            }
                        }
                    }

                    let areq = [];
                    let asup = [];
                    for(let f in listre_fifo){
                        for(let i=0;i<ttlrows; i++){
                            if(listre_fifo[f].mindex==i){//tableku2.rows[i].cells[14].innerText
                                let qty = numeral(tableku2.rows[i].cells[5].innerText).value();
                                let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[2].innerText,
                                            reqqty: qty, supqty : 0};
                                areq.push(anobj);
                                break;
                            }
                        }
                    }
                    console.log('edit save mode before think fplot');
                    console.log(areq);
                    console.log(asup);
                    shpfgoth_e_thinkplot(areq,asup);
                }
            } else {
                alertify.message('nothing difference edit mode');
            }
        } else {
            for(let i=0;i<ttlrows_PLOT; i++){
                let isfound = false;
                for(let c in ca_resumeplotted){
                    if(ca_resumeplotted[c].mindex == Number(tabel_PLOT_body0.rows[i].cells[7].innerText)){
                        ca_resumeplotted[c].mqtysup += numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value();
                        isfound =true; break;
                    }
                }
                if(!isfound){
                    let newobku = {mindex : Number(tabel_PLOT_body0.rows[i].cells[7].innerText),
                                    mqtyreq: numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value()
                                    ,mqtysup : numeral(tabel_PLOT_body0.rows[i].cells[5].innerText).value() };
                    ca_resumeplotted.push(newobku);
                }
            }
            for(let i in ca_resumeplotted){
                let maintblqty = numeral(tableku2.rows[ca_resumeplotted[i].mindex].cells[5].innerText).value();
                if(maintblqty!=ca_resumeplotted[i].mqtyreq){
                    tableku2.rows[ca_resumeplotted[i].mindex].cells[10].innerText = 'not ok';
                    tableku2.rows[ca_resumeplotted[i].mindex].cells[10].style.backgroundColor = 'Crimson';
                    let isfound = false;
                    for(let c in listre_fifo){
                        if(listre_fifo[c].mindex == ca_resumeplotted[i].mindex){
                            isfound =true; break;
                        }
                    }
                    if(!isfound){
                        let newobku = {mindex : ca_resumeplotted[i].mindex };
                        listre_fifo.push(newobku);
                    }
                }
            }
            if(listre_fifo.length>0){
                let konfirm = confirm("Re-fifo is required, are You sure want to continue ?");
                if(konfirm){
                    let listre_fifo_plot_4delete = [];
                    let listre_fifo_os =[];
                    for(let i in listre_fifo){
                        for(let b=0;b<ttlrows_PLOT;b++){
                            if(Number(tabel_PLOT_body0.rows[b].cells[7].innerText)==listre_fifo[i].mindex){
                                let newobj  = {so:tabel_PLOT_body0.rows[b].cells[0].innerText.trim()
                                            ,soline: tabel_PLOT_body0.rows[b].cells[1].innerText
                                            ,soitem: tabel_PLOT_body0.rows[b].cells[2].innerText.trim()
                                            ,qtyret: numeral(tabel_PLOT_body0.rows[b].cells[5].innerText).value() };
                                listre_fifo_os.push(newobj);
                                listre_fifo_plot_4delete.push(b);
                            }
                        }
                    }
                    for(let i in listre_fifo_os){
                        for(let r = 0 ; r<ttlrows_OSSO; r++){
                            if(listre_fifo_os[i].so==tabel_OSSO_body0.rows[r].cells[0].innerText.trim()
                            && listre_fifo_os[i].soline==tabel_OSSO_body0.rows[r].cells[5].innerText.trim()
                            && listre_fifo_os[i].soitem==tabel_OSSO_body0.rows[r].cells[1].innerText.trim()
                            ){
                                tabel_OSSO_body0.rows[r].cells[4].innerText = numeral(tabel_OSSO_body0.rows[r].cells[4].innerText).value()+Number(listre_fifo_os[i].qtyret);
                            }
                        }
                    }
                    for(let i =listre_fifo_plot_4delete.length-1;i>-1;i--){
                        tabel_PLOT_body0.deleteRow(listre_fifo_plot_4delete[i]);
                    }
                    let areq = [];
                    let asup = [];
                    for(let f in listre_fifo){
                        for(let i=0;i<ttlrows; i++){
                            if(listre_fifo[f].mindex==i){
                                let qty = numeral(tableku2.rows[i].cells[5].innerText).value();
                                let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[2].innerText,
                                            reqqty: qty, supqty : 0};
                                areq.push(anobj);
                                break;
                            }
                        }
                    }
                    // console.log('hasil req baru');
                    // console.log(areq);
                    shpfgoth_e_thinkplot(areq,asup);
                }
            } else {
                alertify.message('re-FIFO successfully');
            }
        }

    }

    $("#shpfgoth_btnsave").click(function (e) {
        let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        let whcode = document.getElementById('shpfgoth_cmb_wh').value;
        if(whcode=='AFWH3'){
            if(ttlrows_PLOT==0){
                alertify.warning('please add SO');
                return;
            } else {
                //check editqty
                shpfgoth_e_checkeditedqty_plot()
            }
        }
        let mbg = document.getElementById('shpfgoth_sel_bg').value;
        let mcus = document.getElementById('shpfgoth_sel_cus').value;
        if(mbg.trim()=='-'){
            alertify.message('Please select business group first');
            return;
        }
        if(mcus.trim()=='-'){
            alertify.message('Please select customer first');
            return;
        }
        let lang = navigator.languages ? navigator.languages : navigator.language;
        let mformat = moment().locale(lang).localeData()._longDateFormat['L'];
        let mtbl = document.getElementById('shpfgoth_tbl');
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let mkanban, mitem, mmodel, mreqdate,tmpreqdate, momobj ,mreqqty, mremark, mplant, meta,mlineno,mstatus_so;
        let akanban = [],aitem = [], amodel = [], areqdate =[], areqqty = [],armrk=[], aplant=[] , aeta=[];
        let alineno = [];
        if(ttlrows>1){
            let gssformat = '';
            let waktu = '';
            for(let i=1;i<ttlrows;i++){
                mkanban = mtbl.rows[i].cells[1].innerText;
                mitem = mtbl.rows[i].cells[2].innerText;
                mmodel = mtbl.rows[i].cells[3].innerText;
                mreqdate = mtbl.rows[i].cells[4].innerText;
                tmpreqdate = mreqdate.split(' ');
                if(tmpreqdate[0].includes('-')){
                    gssformat = "YYYY-MM-DD";
                } else {
                    gssformat = "YYYY/MM/DD";
                }
                momobj = moment(tmpreqdate, gssformat);
                if(tmpreqdate[1]){
                    waktu = tmpreqdate[1];
                } else {
                    waktu = '00:00:00';
                }
                mreqdate= momobj.format('YYYY-MM-DD') + ' ' + waktu;
                mreqqty = mtbl.rows[i].cells[5].innerText;
                mremark = mtbl.rows[i].cells[6].innerText;
                mplant = mtbl.rows[i].cells[7].innerText;
                meta = mtbl.rows[i].cells[8].innerText;
                mlineno= mtbl.rows[i].cells[9].innerText;
                if(meta.trim().length!=19){
                    alertify.warning("ETA value is not valid");
                    return;
                }
                mstatus_so= mtbl.rows[i].cells[10].innerText;
                if (mstatus_so.trim()!=='ok' && mbg.trim()!='PSI1PPZIEP' && whcode=='AFWH3'){
                    alertify.message('please check "not ok" row');
                    return;
                }
                if(mitem.trim()!=''){
                    akanban.push(mkanban);
                    aitem.push(mitem);
                    amodel.push(mmodel);
                    areqdate.push(mreqdate);
                    areqqty.push(mreqqty);
                    armrk.push(mremark);
                    aplant.push(mplant);
                    aeta.push(meta);
                    alineno.push(mlineno);
                }
            }
            if(aitem.length>0){
                if(document.getElementById('shpfgoth_isedit').value=='y'){
                    let msi = document.getElementById('shpfgoth_txt_doc').value;
                    let konf = confirm('Are you sure want to save changes?');
                    if(konf){
                        let pl_so = [];
                        let pl_soline = [];
                        let pl_soqty = [];
                        let pl_index = [];
                        let pl_siline = [];
                        let pl_sisoline = [];
                        ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
                        for(let u=0;u<ttlrows_PLOT;u++){
                            pl_so.push(tabel_PLOT_body0.rows[u].cells[0].innerText.trim());
                            pl_soline.push(tabel_PLOT_body0.rows[u].cells[1].innerText.trim());
                            pl_soqty.push(numeral(tabel_PLOT_body0.rows[u].cells[5].innerText).value());
                            pl_siline.push(tabel_PLOT_body0.rows[u].cells[6].innerText.trim());
                            pl_index.push(tabel_PLOT_body0.rows[u].cells[7].innerText.trim());
                            pl_sisoline.push(tabel_PLOT_body0.rows[u].cells[8].innerText.trim());
                        }
                        document.getElementById('shpfgoth_btnsave').disabled = true;
                        document.getElementById("shpfgoth_lblinfo_d").innerText= "please wait..";
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('SI/edit')?>",
                            data: {inbg: mbg,incus: mcus, inkanban: akanban, initem:aitem, inmodel: amodel, inreqdate: areqdate, inlineno: alineno
                            ,inreqqty : areqqty, inrmrk: armrk, inplant: aplant, ineta: aeta, insi: msi, inwh: whcode
                            ,inp_so: pl_so, inp_soline: pl_soline, inp_soqty: pl_soqty, inflineno: pl_sisoline,
                            inp_siline: pl_siline, inp_idx: pl_index},
                            dataType: "json",
                            success: function (response) {
                                $("#shpfgoth_tbl tbody").empty();
                                document.getElementById("shpfgoth_lblinfo_d").innerText= "";
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);
                                } else{
                                    alertify.message(response.data[0].msg);
                                }
                            }, error:function(xhr,xopt,xthrow){
                                alertify.error(xthrow);
                            }
                        });
                    }
                } else{
                    let konf = confirm('Are you sure want to save ?');
                    if(konf){
                        let whcode = document.getElementById('shpfgoth_cmb_wh').value;
                        let pl_so = [];
                        let pl_soline = [];
                        let pl_soqty = [];
                        let pl_index = [];
                        for(let u=0;u<ttlrows_PLOT;u++){
                            pl_so.push(tabel_PLOT_body0.rows[u].cells[0].innerText.trim());
                            pl_soline.push(tabel_PLOT_body0.rows[u].cells[1].innerText.trim());
                            pl_soqty.push(numeral(tabel_PLOT_body0.rows[u].cells[5].innerText).value());
                            pl_index.push(tabel_PLOT_body0.rows[u].cells[7].innerText.trim());
                        }
                        document.getElementById('shpfgoth_btnsave').disabled = true;
                        document.getElementById("shpfgoth_lblinfo_d").innerText= "please wait..";
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('SI/setoth')?>",
                            data: {inbg: mbg,incus: mcus, inkanban: akanban, initem:aitem, inmodel: amodel, inreqdate: areqdate,
                            inreqqty : areqqty, inrmrk: armrk, inplant: aplant, ineta: aeta, inwh:whcode
                            ,inp_so: pl_so, inp_soline: pl_soline, inp_soqty: pl_soqty, inp_idx: pl_index},
                            dataType: "json",
                            success: function (response) {
                                document.getElementById("shpfgoth_lblinfo_d").innerText= "done.";
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);
                                } else{
                                    alertify.message(response.data[0].msg);
                                    document.getElementById('shpfgoth_txt_doc').value=response.data[0].ref;
                                    $("#shpfgoth_tbl tbody").empty();
                                }
                            }, error:function(xhr,xopt,xthrow){
                                alertify.error(xthrow);
                            }
                        });
                    }
                }
            }
        }
    });
    $("#shpfgoth_btnnew").click(function (e) {
        $("#shpfgoth_tbl tbody").empty();
        $("#shpfgoth_tbl_plotso tbody").empty();
        $("#shpfgoth_tbosso tbody").empty();
        document.getElementById('shpfgoth_sel_cus').value='-';
        document.getElementById('shpfgoth_txt_doc').value='';
        document.getElementById('shpfgoth_isedit').value="n";
        document.getElementById('shpfgoth_btnsave').disabled = false;
    });
    $("#shpfgoth_divku").css('height', $(window).height()*68/100);
    $("#shpfgoth_btnplus").click(function () {
        if(document.getElementById('shpfgoth_isedit').value=="n"){
            shpfgoth_btnadd();
            $("#shpfgoth_tbl_plotso tbody").empty();
        } else {
            shpfgoth_btnadd();
        }
    });
    $("#shpfgoth_btnmins").click(function(){
        let konf = confirm("Are you sure want to delete ?");
        if(konf){
            let msi = document.getElementById('shpfgoth_txt_doc').value;
            let table = $("#shpfgoth_tbl tbody");
            let mlineno = table.find('tr').eq(shpfgoth_tblrowindexsel).find('td').eq(9).text();
            if(document.getElementById('shpfgoth_isedit').value=="n"){
                table.find('tr').eq(shpfgoth_tblrowindexsel).remove();
                shpfgoth_tbllength = $('#shpfgoth_tbl tbody > tr').length;
                shpfgoth_renumberrow();
                $("#shpfgoth_tbl_plotso tbody").empty();
            } else {
                if(mlineno.trim()==''){
                    table.find('tr').eq(shpfgoth_tblrowindexsel).remove();
                    shpfgoth_e_removebysiindex(shpfgoth_tblrowindexsel);
                    shpfgoth_e_renindexplot_bysiline();

                    ///RESET    EDITED AFTER SAVED
                    let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
                    let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                    let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
                    let thinkdelete = true;
                    let ifind = ttlrows_PLOT-1;
                    while(ifind>0){
                        let idsisoline =tabel_PLOT_body0.rows[ifind].cells[8].innerText.trim();
                        if(idsisoline==''){
                            tabel_PLOT_body0.deleteRow(ifind);
                            ifind=tabel_PLOT_body0.getElementsByTagName("tr").length-1;
                        }
                        let iscontinue = false;
                        for(let k=0;k<tabel_PLOT_body0.getElementsByTagName("tr").length;k++){
                            if(tabel_PLOT_body0.rows[k].cells[8].innerText.trim()==''){
                                iscontinue= true;
                            }
                        }
                        if (!iscontinue){
                            break;
                        } else {
                        }
                    }
                } else {
                    $.ajax({
                        type: "get",
                        url: "<?=base_url('SI/remove')?>",
                        data: {insi: msi ,inline : mlineno},
                        dataType: "json",
                        success: function (response) {
                            switch(response.data[0].cd){
                                case '00':
                                    alertify.warning(response.data[0].msg);break;
                                case '01':
                                    shpfgoth_e_removebysiindex(shpfgoth_tblrowindexsel);
                                    alertify.message(response.data[0].msg);break;
                                case '11':
                                    $("#shpfgoth_tbl tbody").empty();
                                    alertify.success(response.data[0].msg);
                                    alertify.message('Please reload document SI to see the changes');
                                    break;
                            }
                            shpfgoth_e_renindexplot_bysiline();
                        }, error:function(xhr,xopt,xthrow){
                            alertify.error(xthrow);
                        }
                    });
                }
            }
        }
    });
    function shpfgoth_e_removebysiline(psiline){
        let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        for(let i=0;i<ttlrows_PLOT; i++){
            if(tabel_PLOT_body0.rows[i].cells[6].innerText==psiline){
                tabel_PLOT_body0.deleteRow(i);
                break;
            }
        }
    }
    function shpfgoth_e_removebysiindex(psiindex){
        let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        for(let i=0;i<ttlrows_PLOT; i++){
            if(tabel_PLOT_body0.rows[i].cells[7].innerText==psiindex){
                tabel_PLOT_body0.deleteRow(i);
                break;
            }
        }
    }
    function shpfgoth_btnadd(){
        $('#shpfgoth_tbl > tbody:last-child').append('<tr style="cursor:pointer">'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td contenteditable="true"></td>'+
        '<td class="d-none"></td>'+
        '<td></td>'+
        '</tr>');
        shpfgoth_tbllength = $('#shpfgoth_tbl tbody > tr').length;
        shpfgoth_renumberrow();
        $(".shpfgoth_dtp").datetimepicker({
            format: 'YYYY-MM-DD'
        });
    }

    function shpfgoth_renumberrow(){
        let rows =1;
        let table = $("#shpfgoth_tbl tbody");
        table.find('tr').each(function (i) {
            let $tds = $(this).find('td');
                $tds.eq(0).text(rows);
            rows++;
        });
    }

    $('#shpfgoth_tbl tbody').on( 'click', 'tr', function () {
        shpfgoth_tblrowindexsel =$(this).index();
        if ($(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#shpfgoth_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
    });
    $('#shpfgoth_tbl tbody').on( 'click', 'td', function () {
        shpfgoth_tblcolindexsel = $(this).index();
    });

    shpfgoth_e_col1 = function (e) {
        e = e || window.event;
        var keyCode = e.keyCode || e.which,
        arrow = {left: 37, up: 38, right: 39, down: 40 };

        if(e.shiftKey && keyCode==9){
            if(shpfgoth_tblcolindexsel>1){
                shpfgoth_tblcolindexsel--;
            }
        } else{
            switch(keyCode){
                case 9:
                    if(shpfgoth_tblcolindexsel<8)
                        { shpfgoth_tblcolindexsel++; }
                    else{
                        shpfgoth_tblcolindexsel=1;
                        if(shpfgoth_tblrowindexsel<(shpfgoth_tbllength-1))
                        {shpfgoth_tblrowindexsel++;}
                    }
                    break;
                case arrow.up:
                    if(shpfgoth_tblrowindexsel>0 && !e.ctrlKey){
                        var tables = $("#shpfgoth_tbl tbody");
                        tables.find('tr').eq(--shpfgoth_tblrowindexsel).find('td').eq(shpfgoth_tblcolindexsel).find('input').focus();
                    }
                    break;
                case arrow.down:
                    if(shpfgoth_tblrowindexsel<(shpfgoth_tbllength-1) && !e.ctrlKey){
                        let tables = $("#shpfgoth_tbl tbody");
                        tables.find('tr').eq(++shpfgoth_tblrowindexsel).find('td').eq(shpfgoth_tblcolindexsel).find('input').focus();
                    }
                    break;
            }
        }
        if (e.ctrlKey) {
            switch (keyCode) {
                case arrow.up:
                    if(shpfgoth_tblrowindexsel>0){
                        var tables = $("#shpfgoth_tbl tbody");
                        tables.find('tr').eq(--shpfgoth_tblrowindexsel).find('td').eq(shpfgoth_tblcolindexsel).find('input').focus();
                    }
                    break;
                case arrow.down:
                    if(shpfgoth_tblrowindexsel<(shpfgoth_tbllength-1)){
                        let tables = $("#shpfgoth_tbl tbody");
                        let curval = tables.find('tr').eq(shpfgoth_tblrowindexsel).find('td').eq(shpfgoth_tblcolindexsel).find('input').val();
                        tables.find('tr').eq(++shpfgoth_tblrowindexsel).find('td').eq(shpfgoth_tblcolindexsel).find('input').focus();
                        if(shpfgoth_tblcolindexsel==7 || shpfgoth_tblcolindexsel==8){
                            let mitemval, mplantval;
                            for(let rw=shpfgoth_tblrowindexsel;rw<shpfgoth_tbllength;rw++){
                                mitemval = tables.find('tr').eq(rw).find('td').eq(2).find('input').val();
                                mplantval = tables.find('tr').eq(rw).find('td').eq(shpfgoth_tblcolindexsel).find('input').val();
                                if(mitemval.trim()!='' && mplantval.trim()==''){
                                    tables.find('tr').eq(rw).find('td').eq(shpfgoth_tblcolindexsel).find('input').val(curval);
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                    break;
                case 78:///N
                    shpfgoth_btnadd();
                    break;
                case 68://D
                    $("#shpfgoth_tbl tbody > tr:last").remove();shpfgoth_renumberrow();
                    break;
            }
        }
    };
    function shpfgoth_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/plain');
        let adatapas = datapas.split('\n');
        let ttlrowspasted = 0;
        for(let c=0;c<adatapas.length;c++){
            if(adatapas[c].trim()!=''){
                ttlrowspasted++;
            }
        }
        let table = $("#shpfgoth_tbl tbody");
        let incr =0;
        if((shpfgoth_tbllength-shpfgoth_tblrowindexsel)<ttlrowspasted){
            let needRows = ttlrowspasted - (shpfgoth_tbllength-shpfgoth_tblrowindexsel);
            for(let i = 0;i<needRows;i++){
                shpfgoth_btnadd();
            }
        }
        for(let i=0;i<ttlrowspasted;i++){
            let mcol = adatapas[i].split('\t')
            let ttlcol = mcol.length
            for(let k=0;(k<ttlcol) && (k<9);k++){
                table.find('tr').eq((i+shpfgoth_tblrowindexsel)).find('td').eq((k+shpfgoth_tblcolindexsel)).text(mcol[k].trim());
            }
        }
        if(document.getElementById('shpfgoth_isedit').value=="n"){
            $("#shpfgoth_tbl_plotso tbody").empty();
        } else {
            shpfgoth_e_renindexplot_bysiline();
            getosso_oth();
        }
        event.preventDefault();
    }

    function shpfgoth_e_renindexplot_bysiline(){
        let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrowsplot = tabel_PLOT_body0.getElementsByTagName('tr').length;
        for(let i =0;i<ttlrowsplot;i++){
            let siline = tabel_PLOT_body0.rows[i].cells[6].innerText;
            if(siline.trim()!=''){
                tabel_PLOT_body0.rows[i].cells[7].innerText= getindexmaintable_bysiline_oth(siline);
            }
        }
    }

    $("#shpfgoth_btn_finddoc").click(function (e) {
        $("#shpfgoth_MOD").modal('show');
    });
    $("#shpfgoth_MOD").on('shown.bs.modal', function(){
        document.getElementById('shpfgoth_txtsearch').focus();
    });

    function shpfgoth_e_fokus(){
        document.getElementById('shpfgoth_txtsearch').focus();
    }

    function shpfgoth_e_search(e){
        if(e.which==13){
            let msearch = document.getElementById('shpfgoth_txtsearch').value;
            let mby = document.getElementById('shpfgoth_itm_srchby').value;
            let mlist = '';
            if(document.getElementById('shpfgoth_rad_today').checked){
                mlist=document.getElementById('shpfgoth_rad_today').value;
            } else{
                mlist=document.getElementById('shpfgoth_rad_all').value;
            }
            document.getElementById('shpfgoth_lblinfo').innerText ='please wait...';
            $.ajax({
                type: "get",
                url: "<?=base_url('SI/searchoth')?>",
                data: {inid: msearch, inby: mby, inlist:mlist},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';
                    for(let i=0;i<ttlrows;i++){
                        let bg = response[i].SI_BSGRP ? response[i].SI_BSGRP.trim() : '-';
                        let bg_desc = response[i].MBSG_DESC ? response[i].MBSG_DESC.trim() : '-';
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].SI_DOC+"</td>"+
                        "<td class='d-none'>"+response[i].SI_CUSCD+"</td>"+
                        "<td>"+response[i].MCUS_CUSNM+"</td>"+
                        "<td class='d-none'>"+bg+"</td>"+
                        "<td>"+bg_desc+"</td>"+
                        "<td>"+response[i].SI_WH+"</td>"+
                        "</tr>";
                    }
                    $("#shpfgoth_tblsaved tbody").html(tohtml);
                    document.getElementById('shpfgoth_lblinfo').innerText = ttlrows + ' row(s) found';
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    $('#shpfgoth_tblsaved tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#shpfgoth_tblsaved tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        let mitem = $(this).closest("tr").find('td:eq(0)').text();
        let mcusid = $(this).closest("tr").find('td:eq(1)').text();
        let mcusnm = $(this).closest("tr").find('td:eq(2)').text();
        let mbg = $(this).closest("tr").find('td:eq(3)').text();
        document.getElementById('shpfgoth_cmb_wh').value = $(this).closest("tr").find('td:eq(5)').text();
        document.getElementById('shpfgoth_sel_bg').value=mbg;
        document.getElementById('shpfgoth_sel_cus').innerHTML='<option value="'+mcusid.trim()+'">'+mcusnm.trim()+'</option>';
        document.getElementById('shpfgoth_txt_doc').value=mitem;
        $('#shpfgoth_MOD').modal('hide');
        shpfgoth_e_getdetail(mitem);
        document.getElementById('shpfgoth_isedit').value="y";
        document.getElementById('shpfgoth_btnsave').disabled = false;
    });

    function shpfgoth_e_getdetail(psi){
        document.getElementById('shpfgoth_lblinfo_d').innerText ='please wait...';
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/getbyid')?>",
            data: {insi: psi},
            dataType: "json",
            success: function (response) {
                let ttlrowsso = response.sodata.length;
                let ttlrows = response.mdata.length;
                let tohtml = '';
                for(let i=0;i<ttlrows;i++){
                    let detailqty = 0;
                    for(let u=0;u<ttlrowsso;u++){
                        if(response.sodata[u].SISO_HLINE==response.mdata[i].SI_LINENO){
                            detailqty+=numeral(response.sodata[u].SISO_QTY).value();
                        }
                    }
                    let kolstatus = detailqty == numeral(response.mdata[i].SI_QTY).value() ? '<td style="background-Color: Chartreuse">ok</td>' : '<td style="background-Color: Crimson">not ok</td>';
                    tohtml += "<tr style='cursor:pointer'>"+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+response.mdata[i].SI_DOCREFF+'</td>'+
                    '<td>'+response.mdata[i].SI_ITMCD+'</td>'+
                    '<td>'+response.mdata[i].SI_MDL+'</td>'+
                    '<td>'+response.mdata[i].SI_REQDT+'</td>'+
                    '<td contenteditable="true">'+numeral(response.mdata[i].SI_QTY).value()+'</td>'+
                    '<td>'+response.mdata[i].SI_OTHRMRK+'</td>'+
                    '<td>'+response.mdata[i].SI_HRMRK+'</td>'+
                    '<td contenteditable="true">'+response.mdata[i].SI_DOCREFFETA.substr(0,19)+'</td>'+
                    '<td class="d-none">'+response.mdata[i].SI_LINENO+'</td>'+
                    kolstatus+
                    "</tr>";
                }
                $("#shpfgoth_tbl tbody").html(tohtml);
                document.getElementById('shpfgoth_lblinfo_d').innerText = ttlrows + ' row(s) found';
                shpfgoth_tbllength = $('#shpfgoth_tbl tbody > tr').length;

                //get data saved so

                $("#shpfgoth_tbl_plotso tbody").empty();
                let tabel_PLOT = document.getElementById("shpfgoth_tbl_plotso");
                let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                for(let i=0; i < ttlrowsso; i++){
                    newrow = tabel_PLOT_body0.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.sodata[i].SISO_CPONO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newcell.classList.add('d-none');
                    newText = document.createTextNode(response.sodata[i].SISO_SOLINE);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.sodata[i].SI_ITMCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.sodata[i].SSO2_SLPRC);
                    newcell.appendChild(newText);
                    newcell.classList.add('text-end');
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(numeral(response.sodata[i].SI_QTY).value());
                    newcell.appendChild(newText);
                    newcell.classList.add('text-end');
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.sodata[i].SISO_QTY);
                    newcell.classList.add('text-end');
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newcell.classList.add('d-none');
                    newText = document.createTextNode(response.sodata[i].SISO_HLINE);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newcell.classList.add('d-none');
                    newText = document.createTextNode(getindexmaintable_bysiline_oth(response.sodata[i].SISO_HLINE));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.sodata[i].SISO_FLINE);
                    newcell.appendChild(newText);
                }
                getosso_oth();
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    function getindexmaintable_bysiline_oth(psiline){
        let toret = 0;
        let tabell = document.getElementById("shpfgoth_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let ttlrowsv = tableku2.getElementsByTagName("tr").length;
        for(let v=0;v<ttlrowsv;v++){
            if (tableku2.rows[v].cells[9].innerText==psiline){
                toret= v;break;
            }
        }
        return toret;
    }
    $("#shpfgoth_btnprnt").click(function (e) {
        let msi = document.getElementById('shpfgoth_txt_doc').value;
        if(msi.trim()==''){
            alertify.message('Please select SI Docuemnt first');
            document.getElementById('shpfgoth_txt_doc').focus();
            return;
        }
        let mwh = document.getElementById('shpfgoth_cmb_wh').value;
        Cookies.set('PRINTLABEL_SIOTH', msi, {expires:365});
        Cookies.set('PRINTLABEL_SIWH', mwh, {expires:365});
        window.open("<?=base_url('printlabel_sioth')?>",'_blank');
    });

    function shpfgoth_e_get_qty_plotted(pso, pso_line, passycode){
        let tabelplot = document.getElementById("shpfgoth_tbl_plotso");
        let tabelplotebody0 = tabelplot.getElementsByTagName("tbody")[0];
        let mrows = tabelplotebody0.getElementsByTagName("tr").length;
        let qtyplot = 0;
        for(let i=0 ; i < mrows; i++){
            if(tabelplotebody0.rows[i].cells[0].innerText.trim()==pso
                && tabelplotebody0.rows[i].cells[1].innerText.trim()==pso_line.toString()
                && tabelplotebody0.rows[i].cells[2].innerText.trim()==passycode
                ){//&& tabelplotebody0.rows[i].cells[6].innerText.trim()==''
                    qtyplot += numeral(tabelplotebody0.rows[i].cells[5].innerText).value();
                }
        }
        return qtyplot;
    }
</script>