<style>
    td.mycellactive {
        border: 1px solid #00C935;
        border-style:double;
    }
    td.mycellactive:hover {
        border: 1px solid #00A22A;
        border-style:double;
    }
    .anastylesel{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
</style>

<div style="padding: 10px" onpaste="shpfg_e_pastecol1(event)" tabindex="0" onkeydown="shpfg_e_kdown(event)" onclick="shpfg_e_click(event)">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="shpfg_btnnew" onclick="" class="btn btn-outline-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save"  id="shpfg_btnsave" class="btn btn-outline-primary" ><i class="fas fa-save"></i></button>
                    <button title="Print"  id="shpfg_btnprnt" class="btn btn-outline-primary" ><i class="fas fa-print"></i></button>
                    <input type="hidden" id="shpfg_isedit" value="n">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Customer</span>
                    <select class="form-select" id="shpfg_sel_cus" data-style="btn-info">
                        <option value="-">-</option>
                        <?php
foreach ($lcus as $r) {
    ?>
                            <option value="<?=trim($r->SSO2_CUSCD)?>"><?='[' . trim($r->MCUS_CURCD) . '] ' . $r->MCUS_CUSNM?></option>
                            <?php
}
?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >SI Doc</span>
                    <input type="text" class="form-control" id="shpfg_txt_doc" required placeholder="<<AutoNumber>>" readonly>
                    <button class="btn btn-primary" id="shpfg_btn_finddoc"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" title="Warehouse Code">WH</span>
                    <select class="form-select" id="shpfg_cmb_wh" ><?=$lwh?></select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <span class="badge bg-info" id="shpfg_lbltbl"> -</span>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" id="shpfg_btngetpo" title="get PO and Price ">Get SO and Price</button>
                    <button class="btn btn-outline-info" id="shpfg_btnjoin" title="Join item"><i class="fas fa-object-group"></i></button>
                    <button class="btn btn-outline-primary" id="shpfg_btnsort">Sort Default</button>
                    <button class="btn btn-primary" id="shpfg_btnplus"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="shpfg_btnmins"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="shpfg_divku">
                    <table id="shpfg_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th><!-- 0 -->
                                <th>Kanban No</th><!-- 1 -->
                                <th>Pur.Org</th><!-- 2 -->
                                <th>Item Code</th><!-- 3 -->
                                <th>Description</th><!-- 4 -->
                                <th>Model</th><!-- 5 -->
                                <th>Fr.User</th><!-- 6 -->
                                <th><i class="fas fa-calendar-alt"></i> Req. Date</th><!-- 7 -->
                                <th>QTY</th><!-- 8 -->
                                <th><i class="fas fa-calendar-alt"></i> Transit Date</th><!-- 9 -->
                                <th ><i class="fas fa-calendar-alt"></i> ETA</th><!-- 10 -->
                                <th >Str.Loc</th><!-- 11 -->
                                <th>Supply Area</th><!-- 12 -->
                                <th>Remark</th><!-- 13 -->
                                <th class="d-none">id</th><!-- 14 -->
                                <th >SO</th><!-- 15 -->
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td contenteditable="true">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="text-end" contenteditable="true">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td>&nbsp;</td>
                                <td class="d-none">&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="SHPFG_MOD">
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
                        <input type="text" class="form-control" id="shpfg_txtsearch" onkeypress="shpfg_e_search(event)" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search by</span>
                        <select id="shpfg_itm_srchby" onchange="shpfg_e_fokus()" class="form-select">
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
                        <input type="radio" id="shpfg_rad_today" onclick="shpfg_e_fokus()" class="form-check-input" name="optradio" value="today" checked>
                        <label class="form-check-label">
                        Today
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <input type="radio" id="shpfg_rad_all" onclick="shpfg_e_fokus()" class="form-check-input" name="optradio" value="all">
                        <label class="form-check-label">
                        All
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-end">
                    <span class="badge bg-info" id="shpfg_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="shpfg_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>SI Code</th>
                                    <th class="d-none">Customer ID</th>
                                    <th>Customer</th>
                                    <th>Str.Loc</th>
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
<div class="modal fade" id="shpfg_PO">
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
                    <span class="badge bg-info" id="shpfg_tbosso_info"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="shpfg_divostSO">
                        <table id="shpfg_tbosso" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="6" class="text-center">Outstanding SO</th>
                                </tr>
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">SO</th>
                                    <th rowspan="2" class="align-middle text-center">Assy Code</th>
                                    <th rowspan="2" class="align-middle text-center">Price</th>
                                    <th colspan="2" class="text-center">Qty</th>
                                    <th rowspan="2">LINE</th>
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
                    <span class="badge bg-info" id="shpfg_tbl_plotso_info"></span>
                </div>
                <div class="col mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="shpfgmod_btnfifo" title="Auto FIFO" onclick="shpfg_e_plot()">FIFO</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="shpfg_divplotSO">
                        <table id="shpfg_tbl_plotso" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:75%">
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
    var shpfg_tbllength         = 1;
    var shpfg_tblrowindexsel    = '';
    var shpfg_tblcolindexsel    = '';
    var shpfg_e_col1;

    function shpfg_e_checkeditedqty_plot(){
        let tabell = document.getElementById("shpfg_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let ttlrows = tableku2.getElementsByTagName("tr").length;

        let tabel_OSSO = document.getElementById("shpfg_tbosso");
        let tabel_OSSO_body0 = tabel_OSSO.getElementsByTagName("tbody")[0];
        let ttlrows_OSSO = tabel_OSSO_body0.getElementsByTagName("tr").length;

        let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        let ca_resumeplotted = [];
        let listre_fifo = [];
        if(document.getElementById('shpfg_isedit').value=='y'){
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
            for(let i=0;i<ttlrows; i++){
                let isfoundplot = false;
                for(let k in ca_resumeplotted){
                    if(ca_resumeplotted[k].mindex==i){
                        let maintblqty = numeral(tableku2.rows[ca_resumeplotted[k].mindex].cells[8].innerText).value();
                        if(maintblqty!=ca_resumeplotted[k].mqtyreq){
                            isfoundplot=true;
                            tableku2.rows[i].cells[15].innerText = 'not ok';
                            tableku2.rows[i].cells[15].style.backgroundColor = 'Crimson';
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
                                let qty = numeral(tableku2.rows[i].cells[8].innerText).value();
                                let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[3].innerText,
                                            reqqty: qty, supqty : 0};
                                areq.push(anobj);
                                break;
                            }
                        }
                    }
                    shpfg_e_thinkplot(areq,asup);
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
                let maintblqty = numeral(tableku2.rows[ca_resumeplotted[i].mindex].cells[8].innerText).value();
                if(maintblqty!=ca_resumeplotted[i].mqtyreq){
                    tableku2.rows[ca_resumeplotted[i].mindex].cells[15].innerText = 'not ok';
                    tableku2.rows[ca_resumeplotted[i].mindex].cells[15].style.backgroundColor = 'Crimson';
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
                                let qty = numeral(tableku2.rows[i].cells[8].innerText).value();
                                let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[3].innerText,
                                            reqqty: qty, supqty : 0};
                                areq.push(anobj);
                                break;
                            }
                        }
                    }
                    shpfg_e_thinkplot(areq,asup);
                }
            } else {
                alertify.message('re-FIFO successfully');
            }
        }

    }

    function shpfg_e_thinkplot(areq, asup){
        let tabell = document.getElementById("shpfg_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let ttlrows = tableku2.getElementsByTagName("tr").length;


        let tabel_OSSO = document.getElementById("shpfg_tbosso");
        let tabel_OSSO_body0 = tabel_OSSO.getElementsByTagName("tbody")[0];
        let ttlrows_OSSO = tabel_OSSO_body0.getElementsByTagName("tr").length;

        let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
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
                                        let pricebef = '';
                                        //check is there any plotted price ?
                                        for(let c in asup){
                                            if(asup[c].pindex==itemreqindex){
                                                pricebef = asup[c].pprice;
                                                break;
                                            }
                                        }
                                        if(pricebef!='' && pricebef!=tabel_OSSO_body0.rows[u].cells[2].innerText){
                                            think2=false; think1=false;
                                        } else {
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
                tableku2.rows[areq[i].reqindex].cells[15].innerText ='ok';
                tableku2.rows[areq[i].reqindex].cells[15].style.backgroundColor='Chartreuse';
                ttloksupplied++;
            } else {
                tableku2.rows[areq[i].reqindex].cells[15].innerText ='not ok';
                tableku2.rows[areq[i].reqindex].cells[15].style.backgroundColor='Crimson';
            }
        }
        document.getElementById('shpfgmod_btnfifo').disabled=false;
        document.getElementById('shpfgmod_btnfifo').innerText='FIFO';
        if(ttloksupplied==areq.length){
            $("#shpfg_PO").modal('hide') ;
        }
    }

    function shpfg_e_plot(){
        document.getElementById('shpfgmod_btnfifo').disabled=true;
        document.getElementById('shpfgmod_btnfifo').innerText = 'Please wait';
        let promise = new Promise((resolve, reject) => {
            setTimeout(() => {
                let tabell = document.getElementById("shpfg_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let mrows = tableku2.getElementsByTagName("tr");
                let ttlrows =  mrows.length;

                let tabel_OSSO = document.getElementById("shpfg_tbosso");
                let tabel_OSSO_body0 = tabel_OSSO.getElementsByTagName("tbody")[0];
                let ttlrows_OSSO = tabel_OSSO_body0.getElementsByTagName("tr").length;

                let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
                let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;

                if(ttlrows_PLOT>0){
                    document.getElementById('shpfgmod_btnfifo').disabled=false;
                    document.getElementById('shpfgmod_btnfifo').innerText='FIFO';


                    let ca_resumeplotted = [];
                    let listre_fifo = [];
                    if(document.getElementById('shpfg_isedit').value=='y'){
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
                                    let maintblqty = numeral(tableku2.rows[ca_resumeplotted[k].mindex].cells[8].innerText).value();
                                    if(maintblqty!=ca_resumeplotted[k].mqtyreq){
                                        isfoundplot=true;
                                        tableku2.rows[i].cells[15].innerText = 'not ok';
                                        tableku2.rows[i].cells[15].style.backgroundColor = 'Crimson';
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
                                        if(listre_fifo[f].mindex==i){
                                            let qty = numeral(tableku2.rows[i].cells[8].innerText).value();
                                            let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[3].innerText,
                                                        reqqty: qty, supqty : 0};
                                            areq.push(anobj);
                                            break;
                                        }
                                    }
                                }
                                
                                shpfg_e_thinkplot(areq,asup);
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
                            let maintblqty = numeral(tableku2.rows[ca_resumeplotted[i].mindex].cells[8].innerText).value();
                            if(maintblqty!=ca_resumeplotted[i].mqtyreq){
                                tableku2.rows[ca_resumeplotted[i].mindex].cells[15].innerText = 'not ok';
                                tableku2.rows[ca_resumeplotted[i].mindex].cells[15].style.backgroundColor = 'Crimson';
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
                                            let qty = numeral(tableku2.rows[i].cells[8].innerText).value();
                                            let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[3].innerText,
                                                        reqqty: qty, supqty : 0};
                                            areq.push(anobj);
                                            break;
                                        }
                                    }
                                }
                                shpfg_e_thinkplot(areq,asup);
                            }
                        } else {
                            alertify.message('Already plotted');
                        }
                    }

                } else {
                    let areq = [];
                    let asup = [];
                    for(let i=0;i<ttlrows; i++){
                        let qty = numeral(tableku2.rows[i].cells[8].innerText).value();
                        let anobj = {reqindex: i, reqitem: tableku2.rows[i].cells[3].innerText,
                                    reqqty: qty, supqty : 0};
                        areq.push(anobj);
                    }
                    shpfg_e_thinkplot(areq,asup);
                }
            },50);
        });
    }

    function shpfg_e_get_qty_plotted(pso, pso_line, passycode){
        let tabelplot = document.getElementById("shpfg_tbl_plotso");
        let tabelplotebody0 = tabelplot.getElementsByTagName("tbody")[0];
        let mrows = tabelplotebody0.getElementsByTagName("tr").length;
        let qtyplot = 0;
        for(let i=0 ; i < mrows; i++){
            if(tabelplotebody0.rows[i].cells[0].innerText.trim()==pso
                && tabelplotebody0.rows[i].cells[1].innerText.trim()==pso_line.toString()
                && tabelplotebody0.rows[i].cells[2].innerText.trim()==passycode
                ){
                    qtyplot += numeral(tabelplotebody0.rows[i].cells[5].innerText).value();
                }
        }
        return qtyplot;
    }

    $("#shpfg_btngetpo").click(function (e) {
        getosso();
        $("#shpfg_PO").modal('show');
    })

    function getosso(){
        let custcd = document.getElementById('shpfg_sel_cus').value;
        if(custcd=='-'){
            alertify.message('Please select customer');
            return;
        }
        let tabell = document.getElementById("shpfg_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let mitems = [];
        for(let x=0;x<mrows.length;x++){
            let citem = tableku2.rows[x].cells[3].innerText.trim();
            if(mitems.indexOf(citem)===-1 && citem!=''){
                mitems.push(citem);
            }
        }
        if(mitems.length==0){
            alertify.message('there is no item code in the table below');
            return;
        }

        document.getElementById('shpfg_tbosso_info').innerText = "Please wait...";


        $.ajax({
            type: "get",
            url: "<?=base_url('SI/get_ostso')?>",
            data: {incustomer: custcd, inbg : 'PSI1PPZIEP', initems: mitems },
            dataType: "json",
            success: function (response) {
                document.getElementById('shpfg_tbosso_info').innerText = "";
                if(response.status[0].cd!='0'){
                    let mydes = document.getElementById("shpfg_divostSO");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("shpfg_tbosso");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("shpfg_tbosso");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let ttlrows = response.data.length;
                    for (let i = 0; i<ttlrows; i++){
                        let ostqty =  numeral(response.data[i].SSO2_ORDQT).value()-numeral(response.data[i].SSO2_DELQT).value();
                        ostqty-= shpfg_e_get_qty_plotted(response.data[i].SSO2_CPONO.trim(),response.data[i].SSO2_SOLNO,response.data[i].SSO2_MDLCD.trim());
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
                        newText = document.createTextNode(response.data[i].SSO2_SOLNO);
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                } else {
                    $("#shpfg_tbosso tbody").empty();
                    $("#shpfg_tbl_plotso tbody").empty();
                    alertify.message(response.status[0].msg);
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }



    function shpfg_e_click(e){
        if(e.ctrlKey){
            if(e.target.tagName.toLowerCase()=='td'){
                if(e.target.cellIndex==3){
                    if(e.target.classList.contains('anastylesel')){
                        e.target.classList.remove('anastylesel');
                    } else {
                        let itemcd = e.target.innerText;
                        let mtbl = document.getElementById('shpfg_tbl');
                        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                        let mstrloc = tableku2.rows[shpfg_tblrowindexsel].cells[11].innerText.trim();
                        let mtbltr = tableku2.getElementsByTagName('tr');
                        let ttlrows = mtbltr.length;
                        let mitem ='';
                        let mloc = '';
                        let mqty = '';
                        let mcnt_sel = 0;
                        if(ttlrows>0){
                            for(let i=0;i<ttlrows;i++){
                                mqty = numeral(tableku2.rows[i].cells[8].innerText).value();
                                mitem = tableku2.rows[i].cells[3].innerText;
                                mloc = tableku2.rows[i].cells[11].innerText.trim();
                                if(mitem == itemcd && mloc==mstrloc && mqty > 0){
                                    tableku2.rows[i].cells[3].classList.add('anastylesel');
                                    mcnt_sel++;
                                }
                            }
                            if(mcnt_sel==1){
                                for(let i=0;i<ttlrows;i++){
                                    tableku2.rows[i].cells[3].classList.remove('anastylesel');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $("#shpfg_btnjoin").click(function (e) {
        let mtbl = document.getElementById('shpfg_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        if(ttlrows>0){
            let mitem = '';
            let mqty = '';
            let temp_mitem = [];
            let temp_qty = [];
            let temp_index = [];
            for(let i=0;i<ttlrows;i++){
                if(tableku2.rows[i].cells[3].classList.contains('anastylesel')){
                    mitem = tableku2.rows[i].cells[3].innerText;
                    mqty = numeral(tableku2.rows[i].cells[8].innerText).value();
                    temp_mitem.push(mitem);
                    temp_qty.push(mqty);
                    temp_index.push(i);
                }
            }
            let cnt_sel =temp_mitem.length;
            if(cnt_sel>1){
                let ttlqty =0;
                for(let i=0; i< cnt_sel;i++){
                    ttlqty += temp_qty[i];
                }
                tableku2.rows[temp_index[0]].cells[8].innerText = numeral(ttlqty).format(',');
                tableku2.rows[temp_index[0]].cells[3].classList.remove('anastylesel');
                for(let i=1; i< cnt_sel; i++){
                    tableku2.rows[temp_index[i]].cells[8].innerText = 0;
                    tableku2.rows[temp_index[i]].cells[3].classList.remove('anastylesel');
                }
                shpfg_reinitsort();
            }
        } else {
            alertify.message('nothing to be joined');
        }
    });
    $("#shpfg_btnsave").click(function (e) {
        let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;

        if(ttlrows_PLOT==0){
            alertify.warning('please add SO');
            return;
        } else {
            //check editqty
            shpfg_e_checkeditedqty_plot();
        }
        let mcus = document.getElementById('shpfg_sel_cus').value;
        if(mcus.trim()=='-'){
            alertify.message('Please select customer first');
            return;
        }

        let lang = navigator.languages ? navigator.languages : navigator.language;
        let mformat = moment().locale(lang).localeData()._longDateFormat['L'];
        let mtbl = document.getElementById('shpfg_tbl');
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let momobj;
        let mkanban,mpur_org, mdescription ,mitem, mmodel, mfruser, mreqdate,tmpreqdate, mreqqty, mremark, mplant, meta, mlineno, mstatus_so;
        let mtransitdt, mstrloc;
        let akanban = [],apur_org = [], adescrip = [],aitem = [], amodel = [],afruser = [], areqdate =[], areqqty = [],armrk=[], aplant=[] , aeta=[];
        let astrloc =[], atransitdt = [] , alineno = [];
        
        let dataBefore = JSON.parse(localStorage.getItem('shipping_local'))
        let dataAfter = []

        if(ttlrows>1){
            let gssformat = '';
            for(let i=1;i<ttlrows;i++){
                mkanban = mtbl.rows[i].cells[1].innerText;
                mpur_org = mtbl.rows[i].cells[2].innerText;
                mitem = mtbl.rows[i].cells[3].innerText;
                mdescription = mtbl.rows[i].cells[4].innerText;
                mmodel = mtbl.rows[i].cells[5].innerText;
                mfruser = mtbl.rows[i].cells[6].innerText;
                mreqdate = mtbl.rows[i].cells[7].innerText;
                if(mreqdate.length<8){
                    alertify.message('invalid req date format');
                    return;
                }
                tmpreqdate = mreqdate.split(' ');
                if(tmpreqdate[0].includes('-')){
                    gssformat = "YYYY-MM-DD";
                } else {
                    gssformat = "YYYY/MM/DD";
                }
                momobj = moment(tmpreqdate, gssformat);
                mreqdate= momobj.format('YYYY-MM-DD') + ' ' + tmpreqdate[1];
                mreqqty = numeral(mtbl.rows[i].cells[8].innerText).value();
                mtransitdt = mtbl.rows[i].cells[9].innerText;
                if(mtransitdt.length<8){
                    alertify.message('invalid transit date format');
                    return;
                }
                tmpreqdate = mtransitdt.split(' ');
                if(tmpreqdate[0].includes('-')){
                    gssformat = "YYYY-MM-DD";
                } else {
                    gssformat = "YYYY/MM/DD";
                }
                momobj = moment(tmpreqdate, gssformat);
                mtransitdt= momobj.format('YYYY-MM-DD') + ' ' + tmpreqdate[1];
                meta = mtbl.rows[i].cells[10].innerText;
                tmpreqdate = meta.split(' ');
                if(tmpreqdate[0].includes('-')){
                    gssformat = "YYYY-MM-DD";
                } else {
                    gssformat = "YYYY/MM/DD";
                }
                momobj = moment(tmpreqdate, gssformat);
                meta= momobj.format('YYYY-MM-DD') + ' ' + tmpreqdate[1];
                mstrloc = mtbl.rows[i].cells[11].innerText;
                mplant = mtbl.rows[i].cells[12].innerText;
                mremark= mtbl.rows[i].cells[13].innerText;
                mlineno= mtbl.rows[i].cells[14].innerText;
                mstatus_so= mtbl.rows[i].cells[15].innerText;
                if (mstatus_so.trim()!=='ok' && mstatus_so.trim()!=='not ok' ){
                    alertify.message('please check SO');
                    return;
                }
                if(mitem.trim()!=''){
                    akanban.push(mkanban);
                    apur_org.push(mpur_org);
                    aitem.push(mitem);
                    adescrip.push(mdescription);
                    amodel.push(mmodel);
                    afruser.push(mfruser);
                    areqdate.push(mreqdate);
                    areqqty.push(mreqqty);
                    atransitdt.push(mtransitdt);
                    aeta.push(meta);
                    astrloc.push(mstrloc);
                    aplant.push(mplant);
                    armrk.push(mremark);
                    alineno.push(mlineno);
                }

                dataAfter.push([mitem, mreqqty, mlineno])
            }

            if(aitem.length>0){
                if(document.getElementById('shpfg_isedit').value=='y') {
                    // mulai analisa perubahan data
                    
                    const isSameIDRP = (a, b) => a[0] === b[0] && a[1] === b[1]
                    const onlyInA = onlyInLeft(dataBefore, dataAfter, isSameIDRP);
                    const onlyInB = onlyInLeft(dataAfter, dataBefore, isSameIDRP);

                    const result = [...onlyInA, ...onlyInB]

                    if(result.length === 2) {
                        let _akanban = []
                        let _apur_org = []
                        let _adescrip = []
                        let _aitem = []
                        let _amodel = []
                        let _afruser = []
                        let _areqdate = []
                        let _areqqty = []
                        let _armrk = []
                        let _aplant = []
                        let _aeta = []
    
                        let _astrloc =[]
                        let _atransitdt = []
                        let _alineno = []
                        

                        const totalRowsResult = onlyInB.length
                        for(let i=0;i<ttlrows;i++) { 
                            for(let j=0;j<totalRowsResult; j++) {
                                if(alineno[i] == onlyInB[j][2]) {
                                    _akanban.push(akanban[i]);
                                    _apur_org.push(apur_org[i]);
                                    _aitem.push(aitem[i]);
                                    _adescrip.push(adescrip[i]);
                                    _amodel.push(amodel[i]);
                                    _afruser.push(afruser[i]);
                                    _areqdate.push(areqdate[i]);
                                    _areqqty.push(areqqty[i]);
                                    _atransitdt.push(atransitdt[i]);
                                    _aeta.push(aeta[i]);
                                    _astrloc.push(astrloc[i]);
                                    _aplant.push(aplant[i]);
                                    _armrk.push(armrk[i]);
                                    _alineno.push(alineno[i]);
                                    break;
                                }
                            }
                        }

                        akanban = _akanban
                        apur_org = _apur_org
                        aitem = _aitem
                        adescrip = _adescrip
                        amodel = _amodel
                        afruser = _afruser
                        areqdate = _areqdate
                        areqqty = _areqqty
                        atransitdt = _atransitdt
                        aeta = _aeta
                        astrloc = _astrloc
                        aplant = _aplant
                        armrk = _armrk
                        alineno = _alineno

                    }

                    // akhir analisa

                    let msi = document.getElementById('shpfg_txt_doc').value;
                    let konf = confirm('Are you sure want to save changes ?');
                    if(konf){
                        let whcode = document.getElementById('shpfg_cmb_wh').value;
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
                        document.getElementById('shpfg_btnsave').disabled = true;
                        document.getElementById("shpfg_lbltbl").innerText= "please wait..";
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('SI/edit')?>",
                            data: {inbg: 'PSI1PPZIEP',incus: mcus, inkanban: akanban, inpurorg: apur_org, initem:aitem, indescr: adescrip, inmodel: amodel, infruser: afruser,
                            inreqdate: areqdate, inreqqty : areqqty, intransitdate: atransitdt, instrloc: astrloc ,inrmrk: armrk, inplant: aplant, ineta: aeta,
                            insi: msi, inlineno: alineno, inwh: whcode
                            ,inp_so: pl_so, inp_soline: pl_soline, inp_soqty: pl_soqty, inflineno: pl_sisoline,
                            inp_siline: pl_siline, inp_idx: pl_index},
                            dataType: "json",
                            success: function (response) {
                                $("#shpfg_tbl tbody").empty();
                                document.getElementById("shpfg_lbltbl").innerText= "";
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);
                                } else{
                                    alertify.message(response.data[0].msg);
                                }
                                document.getElementById("shpfg_lbltbl").innerText= "";
                            }, error:function(xhr,xopt,xthrow){
                                alertify.error(xthrow);
                            }
                        });
                    }
                } else{
                    let konf = confirm('Are you sure want to save ?');
                    if(konf){
                        let whcode = document.getElementById('shpfg_cmb_wh').value;
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
                        document.getElementById('shpfg_btnsave').disabled = true;
                        document.getElementById("shpfg_lbltbl").innerText= "please wait..";
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('SI/set')?>",
                            data: {incus: mcus, inkanban: akanban, inpurorg: apur_org, initem:aitem, indescr: adescrip, inmodel: amodel, infruser: afruser,
                            inreqdate: areqdate, inreqqty : areqqty, intransitdate: atransitdt, instrloc: astrloc ,inrmrk: armrk, inplant: aplant, ineta: aeta
                            ,inp_so: pl_so, inp_soline: pl_soline, inp_soqty: pl_soqty, inp_idx: pl_index
                            ,inwh: whcode},
                            dataType: "json",
                            success: function (response) {
                                document.getElementById("shpfg_lbltbl").innerText= "done.";
                                if(response.data[0].cd=='0'){
                                    alertify.warning(response.data[0].msg);
                                } else{
                                    alertify.message(response.data[0].msg);
                                    document.getElementById('shpfg_txt_doc').value=response.data[0].ref;
                                    $("#shpfg_tbl tbody").empty();
                                    document.getElementById('shpfg_isedit').value="y";
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
    $("#shpfg_btnnew").click(function (e) {
        $("#shpfg_tbl tbody").empty();
        $("#shpfg_tbl_plotso tbody").empty();
        $("#shpfg_tbosso tbody").empty();
        document.getElementById('shpfg_sel_cus').value='-';
        document.getElementById('shpfg_txt_doc').value='';
        document.getElementById('shpfg_isedit').value="n";
        shpfg_btnadd();
        document.getElementById('shpfg_btnsave').disabled = false;
    });

    function getindexmaintable_bysiline(psiline){
        let toret = 0;
        let tabell = document.getElementById("shpfg_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let ttlrowsv = tableku2.getElementsByTagName("tr").length;
        for(let v=0;v<ttlrowsv;v++){
            if (tableku2.rows[v].cells[14].innerText==psiline){
                toret= v;break;
            }
        }
        return toret;
    }
    $("#shpfg_divku").css('height', $(window).height()*68/100);
    $("#shpfg_btnplus").click(function () {
        if(document.getElementById('shpfg_isedit').value=="n"){
            shpfg_btnadd();
            $("#shpfg_tbl_plotso tbody").empty();
        } else {
            shpfg_btnadd();
        }
    });
    $("#shpfg_btnmins").click(function(){
        let konf = confirm("Are you sure want to delete ?");
        if(konf){
            let msi = document.getElementById('shpfg_txt_doc').value;
            let table = $("#shpfg_tbl tbody");
            let mline = table.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(14).text();
            if(document.getElementById('shpfg_isedit').value=="n"){
                table.find('tr').eq(shpfg_tblrowindexsel).remove();
                shpfg_tbllength = $('#shpfg_tbl tbody > tr').length;
                shpfg_reinitsort();
                $("#shpfg_tbl_plotso tbody").empty();
            } else {
                if(mline.trim()==''){
                    table.find('tr').eq(shpfg_tblrowindexsel).remove();
                    shpfg_e_removebysiindex(shpfg_tblrowindexsel);
                    shpfg_e_renindexplot_bysiline();

                    ///RESET    EDITED AFTER SAVED
                    let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
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
                        }
                    }

                } else {
                    $.ajax({
                        type: "get",
                        url: "<?=base_url('SI/remove')?>",
                        data: {insi: msi, inline: mline },
                        dataType: "json",
                        success: function (response) {
                            switch(response.data[0].cd){
                                case '00':
                                    alertify.warning(response.data[0].msg);break;
                                case '01':
                                    shpfg_e_removebysiindex(shpfg_tblrowindexsel);
                                    alertify.message('deleted..');break;
                                case '11':
                                    $("#shpfg_tbl tbody").empty();
                                    alertify.success(response.data[0].msg);
                                    alertify.message('Please reload document SI to see the changes');

                                    break;
                            }
                            shpfg_e_renindexplot_bysiline();
                        }, error:function(xhr,xopt,xthrow){
                            alertify.error(xthrow);
                        }
                    });
                }
            }
        }
    });

    function shpfg_e_renindexplot_bysiline(){
        let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrowsplot = tabel_PLOT_body0.getElementsByTagName('tr').length;
        for(let i =0;i<ttlrowsplot;i++){
            let siline = tabel_PLOT_body0.rows[i].cells[6].innerText;
            if(siline.trim()!=''){
                tabel_PLOT_body0.rows[i].cells[7].innerText= getindexmaintable_bysiline(siline);
            }
        }
    }

    function shpfg_e_removebysiline(){
        let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        for(let i=0;i<ttlrows_PLOT; i++){
            if(tabel_PLOT_body0.rows[i].cells[6].innerText==psiline){
                tabel_PLOT_body0.deleteRow(i);
                break;
            }
        }
    }
    function shpfg_e_removebysiindex(psiindex){
        let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        for(let i=0;i<ttlrows_PLOT; i++){
            if(tabel_PLOT_body0.rows[i].cells[7].innerText==psiindex){
                tabel_PLOT_body0.deleteRow(i);
                break;
            }
        }
    }


    function shpfg_btnadd(){
        $('#shpfg_tbl > tbody:last-child').append('<tr style="cursor:pointer">'+
        '<td>&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td contenteditable="true">&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td class="text-end" contenteditable="true">&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td >&nbsp</td>'+
        '<td onkeydown="shpfg_e_col1(event)">&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td>&nbsp</td>'+
        '<td class="d-none">&nbsp</td>'+
        '<td>&nbsp</td>'+
        '</tr>');
        shpfg_tbllength = $('#shpfg_tbl tbody > tr').length;
        shpfg_reinitsort();
    }

    function shpfg_e_kdown (event){
        if (event.ctrlKey) {
            switch(event.keyCode){
                case 78:
                    shpfg_btnadd();
                    break;
                case 68:
                    $("#shpfg_tbl tbody").find('tr').eq(shpfg_tblrowindexsel).remove();
                    break;
            }
        } else {
            let merow =$('#shpfg_tbl tbody');
            let meselcell= merow.find("tr").eq(shpfg_tblrowindexsel).find("td").eq(shpfg_tblcolindexsel).attr("contentEditable");
            switch(event.keyCode){
                case 40: //arrow down
                    if(shpfg_tblrowindexsel<(shpfg_tbllength-1)){
                        merow.find('tr').eq(shpfg_tblrowindexsel).find('td').removeClass('mycellactive');
                        shpfg_tblrowindexsel++;
                        merow.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).addClass('mycellactive');
                    }
                    break;
                case 38: //arrow up
                    if(shpfg_tblrowindexsel>0){
                        merow.find('tr').eq(shpfg_tblrowindexsel).find('td').removeClass('mycellactive');
                        --shpfg_tblrowindexsel;
                        merow.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).addClass('mycellactive');
                    }
                    break;
                case 39: //arrow right
                    if (typeof meselcell === 'undefined'){
                        merow.find('tr').eq(shpfg_tblrowindexsel).find('td').removeClass('mycellactive');
                        if(shpfg_tblcolindexsel<13){
                            shpfg_tblcolindexsel++;
                            merow.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).addClass('mycellactive');
                        } else {
                            shpfg_tblcolindexsel=0;shpfg_tblrowindexsel++;
                            merow.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).addClass('mycellactive');
                        }
                    }
                    break;
                case 37: //arrow left

                    if (typeof meselcell === 'undefined'){
                        merow.find('tr').eq(shpfg_tblrowindexsel).find('td').removeClass('mycellactive');
                        if(shpfg_tblcolindexsel>0){
                            --shpfg_tblcolindexsel;
                            merow.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).addClass('mycellactive');
                        }
                    }
                    break;
                case 113:
                    if (typeof meselcell !== 'undefined'){
                        merow.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).focus();
                    }
                    break;
            }
        }
    }

    $('#shpfg_tbl tbody').on( 'click', 'tr', function () {
        $('#shpfg_tbl tbody').find('tr').eq(shpfg_tblrowindexsel).find('td').removeClass('mycellactive');
        shpfg_tblrowindexsel =$(this).index();


        if ($(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#shpfg_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }

        $(this).find('td').eq(shpfg_tblcolindexsel).addClass('mycellactive');


    });
    $('#shpfg_tbl tbody').on( 'click', 'td', function () {
        shpfg_tblcolindexsel = $(this).index();
    });

    shpfg_e_col1 = function (e) {
        e = e || window.event;
        var keyCode = e.keyCode || e.which,
        arrow = {left: 37, up: 38, right: 39, down: 40 };

        if(e.shiftKey && keyCode==9){
            if(shpfg_tblcolindexsel>1){
                shpfg_tblcolindexsel--;
            }
        } else{
            switch(keyCode){
                case 9:
                    if(shpfg_tblcolindexsel<8)
                        { shpfg_tblcolindexsel++; }
                    else{
                        shpfg_tblcolindexsel=1;
                        if(shpfg_tblrowindexsel<(shpfg_tbllength-1))
                        {shpfg_tblrowindexsel++;}
                    }
                    break;
                case arrow.up:
                    if(shpfg_tblrowindexsel>0 && !e.ctrlKey){
                        let tables = $("#shpfg_tbl tbody");
                        tables.find('tr').eq(shpfg_tblrowindexsel-1).find('td').eq(shpfg_tblcolindexsel).focus();
                    }
                    break;
                case arrow.down:
                    if(shpfg_tblrowindexsel<(shpfg_tbllength-1) && !e.ctrlKey){
                        let tables = $("#shpfg_tbl tbody");
                        tables.find('tr').eq(shpfg_tblrowindexsel+1).find('td').eq(shpfg_tblcolindexsel).focus();
                    }
                    break;
            }
        }
        if (e.ctrlKey) {
            switch (keyCode) {
                case arrow.down:
                    if(shpfg_tblrowindexsel<(shpfg_tbllength-1)){
                        let tables = $("#shpfg_tbl tbody");
                        let curval = tables.find('tr').eq(shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).find('input').val();
                        tables.find('tr').eq(++shpfg_tblrowindexsel).find('td').eq(shpfg_tblcolindexsel).find('input').focus();
                        if(shpfg_tblcolindexsel==7 || shpfg_tblcolindexsel==8){
                            let mitemval, mplantval;
                            for(let rw=shpfg_tblrowindexsel;rw<shpfg_tbllength;rw++){
                                mitemval = tables.find('tr').eq(rw).find('td').eq(2).find('input').val();
                                mplantval = tables.find('tr').eq(rw).find('td').eq(shpfg_tblcolindexsel).find('input').val();
                                if(mitemval.trim()!='' && mplantval.trim()==''){
                                    tables.find('tr').eq(rw).find('td').eq(shpfg_tblcolindexsel).find('input').val(curval);
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                    break;
                case 78:///N
                    shpfg_btnadd();
                    break;
                case 68://D
                    $("#shpfg_tbl tbody > tr:last").remove();
                    break;
            }
        }
    };
    function shpfg_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/html');
        if(datapas===""){
            datapas = event.clipboardData.getData('text');
            let adatapas = datapas.split('\n');
            let ttlrowspasted = 0;
            for(let c=0;c<adatapas.length;c++){
                if(adatapas[c].trim()!=''){
                    ttlrowspasted++;
                }
            }
            let table = $("#shpfg_tbl tbody");
            let incr =0;
            if((shpfg_tbllength-shpfg_tblrowindexsel)<ttlrowspasted){
                let needRows = ttlrowspasted - (shpfg_tbllength-shpfg_tblrowindexsel);
                for(let i = 0;i<needRows;i++){
                    shpfg_btnadd();
                }
            }
            for(let i=0;i<ttlrowspasted;i++){
                let mcol = adatapas[i].split('\t');
                let ttlcol = mcol.length;
                for(let k=0;(k<ttlcol) && (k<9);k++){
                    table.find('tr').eq((i+shpfg_tblrowindexsel)).find('td').eq((k+shpfg_tblcolindexsel)).text(mcol[k].trim());
                }
            }
        } else {
            let tmpdom = document.createElement('html');
            tmpdom.innerHTML = datapas;
            let myhead = tmpdom.getElementsByTagName('head')[0];
            let myscript = myhead.getElementsByTagName('script')[0];
            let mybody = tmpdom.getElementsByTagName('body')[0];
            let mytable = mybody.getElementsByTagName('table')[0];
            let mytbody = mytable.getElementsByTagName('tbody')[0];
            let mytrlength = mytbody.getElementsByTagName('tr').length;
            let table = $("#shpfg_tbl tbody");
            let incr =0;
            let startin =0;

            if(typeof(myscript) != 'undefined'){ //check if clipboard from IE
                startin =3;
            }
            if((shpfg_tbllength-shpfg_tblrowindexsel)<(mytrlength-startin)){
                let needRows = (mytrlength-startin) - (shpfg_tbllength-shpfg_tblrowindexsel);
                for(let i = 0;i<needRows;i++){
                    shpfg_btnadd();
                }
            }

            let b = 0;
            for(let i=startin;i<(mytrlength);i++){
                let ttlcol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td').length;
                for(let k=0;(k<ttlcol) && (k<14);k++){
                    let dkol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td')[k].innerText;
                    table.find('tr').eq((b+shpfg_tblrowindexsel)).find('td').eq((k+shpfg_tblcolindexsel)).text(dkol.trim());
                }
                b++;
            }
        }
        shpfg_reinitsort();
        if(document.getElementById('shpfg_isedit').value=="n"){
            $("#shpfg_tbl_plotso tbody").empty();
        } else {
            shpfg_e_renindexplot_bysiline();
            getosso();
        }
        event.preventDefault();
    }
    $("#shpfg_btn_finddoc").click(function (e) {
        $("#SHPFG_MOD").modal('show');
    });
    $("#SHPFG_MOD").on('shown.bs.modal', function(){
        document.getElementById('shpfg_txtsearch').focus();
    });

    function shpfg_e_fokus(){
        document.getElementById('shpfg_txtsearch').focus();
    }

    function shpfg_e_search(e){
        if(e.which==13){
            let msearch = document.getElementById('shpfg_txtsearch').value;
            let mby = document.getElementById('shpfg_itm_srchby').value;
            let mlist = '';
            if(document.getElementById('shpfg_rad_today').checked){
                mlist=document.getElementById('shpfg_rad_today').value;
            } else{
                mlist=document.getElementById('shpfg_rad_all').value;
            }
            document.getElementById('shpfg_lblinfo').innerText ='please wait...';
            $.ajax({
                type: "get",
                url: "<?=base_url('SI/search')?>",
                data: {inid: msearch, inby: mby, inlist:mlist},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.length;
                    let tohtml = '';
                    for(let i=0;i<ttlrows;i++){
                        tohtml += "<tr style='cursor:pointer'>"+
                        "<td>"+response[i].SI_DOC+"</td>"+
                        "<td class='d-none'>"+response[i].SI_CUSCD+"</td>"+
                        "<td>"+response[i].MCUS_CUSNM+"</td>"+
                        "<td>"+response[i].SI_OTHRMRK+"</td>"+
                        "<td>"+response[i].SI_WH+"</td>"+
                        "</tr>";
                    }
                    $("#shpfg_tblsaved tbody").html(tohtml);
                    document.getElementById('shpfg_lblinfo').innerText = ttlrows + ' row(s) found';
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    $('#shpfg_tblsaved tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#shpfg_tblsaved tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        let mitem = $(this).closest("tr").find('td:eq(0)').text();
        let mcusid = $(this).closest("tr").find('td:eq(1)').text();
        document.getElementById('shpfg_cmb_wh').value = $(this).closest("tr").find('td:eq(4)').text();
        document.getElementById('shpfg_sel_cus').value = mcusid.trim();
        document.getElementById('shpfg_txt_doc').value = mitem;
        $('#SHPFG_MOD').modal('hide');shpfg_e_getdetail(mitem);
        document.getElementById('shpfg_isedit').value="y";
        document.getElementById('shpfg_btnsave').disabled = false;
    });

    function shpfg_e_getdetail(psi){
        document.getElementById('shpfg_lbltbl').innerText='Please wait...';
        $("#shpfg_tbl_plotso tbody").empty();
        $("#shpfg_tbosso tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/getbyid')?>",
            data: {insi: psi},
            dataType: "json",
            success: function (response) {
                let ttlrowsso = response.sodata.length;
                let ttlrows = response.mdata.length;
                let tohtml = '';
                let meta;
                let shipping_local = []
                for(let i=0;i<ttlrows;i++) {
                    
                    meta = moment(response.mdata[i].SI_DOCREFFETA);
                    let detailqty = 0;
                    for(let u=0;u<ttlrowsso;u++) {
                        if(response.sodata[u].SISO_HLINE==response.mdata[i].SI_LINENO) {
                            detailqty+=numeral(response.sodata[u].SISO_QTY).value();
                        }
                    }
                    let kolstatus = detailqty == numeral(response.mdata[i].SI_QTY).value() ? '<td style="background-Color: Chartreuse">ok</td>' : '<td style="background-Color: Crimson">not ok</td>';

                    tohtml += "<tr style='cursor:pointer'>"+
                    '<td>'+(i+1)+'</td>'+
                    '<td>'+response.mdata[i].SI_DOCREFF+'</td>'+
                    '<td>'+response.mdata[i].SI_PURORG+'</td>'+
                    '<td contenteditable="true">'+response.mdata[i].SI_ITMCD+'</td>'+
                    '<td>'+response.mdata[i].SI_DESCR+'</td>'+
                    '<td>'+response.mdata[i].SI_MDL+'</td>'+
                    '<td>'+response.mdata[i].SI_FRUSER+'</td>'+
                    '<td>'+response.mdata[i].SI_DOCREFFDT+'</td>'+
                    '<td class="text-end" contenteditable="true">'+numeral(response.mdata[i].SI_QTY).value()+'</td>'+
                    '<td>'+response.mdata[i].SI_TRANSITDT+'</td>'+
                    '<td >'+meta.format("YYYY/MM/DD HH:mm:ss")+'</td>'+
                    '<td onkeydown="shpfg_e_col1(event)">'+response.mdata[i].SI_OTHRMRK+'</td>'+
                    '<td>'+response.mdata[i].SI_HRMRK+'</td>'+
                    '<td>'+response.mdata[i].SI_RMRK+'</td>'+
                    '<td class="d-none">'+response.mdata[i].SI_LINENO+'</td>'+
                    kolstatus+
                    '</tr>';
                }
                $("#shpfg_tbl tbody").html(tohtml);
                document.getElementById('shpfg_lbltbl').innerText = ttlrows + ' row(s) found';
                shpfg_tbllength = $('#shpfg_tbl tbody > tr').length;
                shpfg_reinitsort();
                
                // setelah disusun ulang coba ambil dan taruh di local storage 
                // untuk dijadikan bahan komparasi ketika edit
                let mtbl = document.getElementById('shpfg_tbl');
                let mtbltr = mtbl.getElementsByTagName('tr');
                
                for(let i=1;i<=ttlrows;i++) {
                    let _itemCode = mtbl.rows[i].cells[3].innerText
                    let _itemLine = mtbl.rows[i].cells[14].innerText
                    let _itemQty = numeral(mtbl.rows[i].cells[8].innerText).value();

                    shipping_local.push([_itemCode, _itemQty, _itemLine])
                }
                localStorage.setItem('shipping_local', JSON.stringify(shipping_local))

                //get data saved so

                $("#shpfg_tbl_plotso tbody").empty();
                let tabel_PLOT = document.getElementById("shpfg_tbl_plotso");
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
                    newText = document.createTextNode(getindexmaintable_bysiline(response.sodata[i].SISO_HLINE));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.sodata[i].SISO_FLINE);
                    newcell.appendChild(newText);
                }
                getosso();

            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#shpfg_btnprnt").click(function (e) {
        let msi = document.getElementById('shpfg_txt_doc').value;
        if(msi.trim()==''){
            alertify.message('Please select SI Docuemnt first');
            document.getElementById('shpfg_txt_doc').focus();
            return;
        }
        let mwh = document.getElementById('shpfg_cmb_wh').value;
        Cookies.set('PRINTLABEL_SI', msi, {expires:365});
        Cookies.set('PRINTLABEL_SIWH', mwh, {expires:365});
        window.open("<?=base_url('printlabel_si')?>",'_blank');
    });
    $("#shpfg_tbl").tablesorter({
        sortRestart : true,
        sortList: [[11,0],[10,0],[3,0]]
    });
    $("#shpfg_btnsort").click(function (e) {
        shpfg_reinitsort();
    });

    function shpfg_reinitsort(){
        let resort = true;
        $("#shpfg_tbl").trigger("update", [resort]);
        $("#shpfg_tbl").trigger('sorton', [ [[11,0],[10,0],[3,0]] ]);
        document.getElementById('shpfg_lbltbl').innerHTML= shpfg_tbllength + " row(s)";

    }

</script>