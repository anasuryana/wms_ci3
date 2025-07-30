<div style="padding: 5px">
	<div class="container-xxl">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Reff Code</label>
                    <input type="text" class="form-control" id="combinelbl2_reffcd" placeholder="Scan or type reff number here" maxlength="200">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="combinelbl2_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="combinelbl2_divku">
                    <table id="combinelbl2_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">Reff No</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="align-middle">Item Code</th>
                                <th  class="align-middle">Item Name</th>
                                <th  class="text-right">QTY</th>
                                <th  class="d-none">QTY /Sheet</th>
                                <th class="align-middle" title="Current Location">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col ">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">New Reff Code</label>
                    <input type="text" class="form-control" id="combinelbl2_reffcdnew"  readonly title="it will show You new reff no, triggered when You save desired labels">
                    <button class="btn btn-primary" id="combinelbl2_btnmod"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col text-center ">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="combinelbl2_btnnew"><i class="fas fa-file"></i></button>
                    <button class="btn btn-primary" type="button" id="combinelbl2_btn_save"><i class="fas fa-save"></i> </button>
                    <button class="btn btn-primary" type="button" id="combinelbl2_btn_print"><i class="fas fa-print"></i> </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="combinelbl2_MOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <select id="combinelbl2_srchby" onchange="combinelbl2_e_fokus()" class="form-select">
                            <option value="1">New Reff</option>
                            <option value="2">Old Reff</option>
                        </select>
                        <input type="text" class="form-control" id="combinelbl2_txtsearch" onkeypress="combinelbl2_e_search(event)" maxlength="16" onfocus="this.select()" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-right">
                    <span class="badge bg-info" id="combinelbl2_lblinfo_mod"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="combinelbl2_divkudet">
                        <table id="combinelbl2_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>New Reff</th>
                                    <th>Old Reff</th>
                                    <th>QTY</th>
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
    function combinelbl2_e_fokus(){
        document.getElementById('combinelbl2_txtsearch').focus();
    }
    $("#combinelbl2_divku").css('height', $(window).height()*67/100);
    $("#combinelbl2_btnmod").click(function (e) {
        $("#combinelbl2_MOD").modal('show');
    });
    $("#combinelbl2_MOD").on('shown.bs.modal', function(){
        document.getElementById('combinelbl2_txtsearch').focus();
    });
    $("#combinelbl2_txtsearch").keypress(function (e) {
        if(e.which==13){
            let msearch = document.getElementById('combinelbl2_txtsearch').value;
            let msearch_by = document.getElementById('combinelbl2_srchby').value;
            document.getElementById('combinelbl2_lblinfo_mod').innerText = 'Please wait..' ;
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/combined2_list')?>",
                data: {insearch: msearch, insearch_by: msearch_by},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd!='0'){
                        document.getElementById('combinelbl2_lblinfo_mod').innerText = '' ;
                        let ttlrows = response.data.length;
                        let mydes = document.getElementById("combinelbl2_divkudet");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("combinelbl2_tblsaved");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("combinelbl2_tblsaved");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(response.data[i].SERC_NEWID);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[i].SERC_COMID);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.data[i].SERC_COMQTY);
                            newcell.appendChild(newText);
                        }
                        let mrows = tableku2.getElementsByTagName("tr");
                        function combine2_getselected(prow){
                            let tcell0 = prow.getElementsByTagName("td")[0];
                            document.getElementById('combinelbl2_reffcdnew').value = tcell0.innerText;
                            $("#combinelbl2_MOD").modal('hide');
                        }
                        for(let x=0;x<mrows.length;x++){
                            tableku2.rows[x].cells[0].onclick = function(){combine2_getselected(tableku2.rows[x])};
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        document.getElementById('combinelbl2_lblinfo_mod').innerText =response.status[0].msg;
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#combinelbl2_btn_print").click(function (e) {
        let mreff  = document.getElementById('combinelbl2_reffcdnew').value;
        if(mreff.trim()==''){
            document.getElementById('combinelbl2_reffcdnew').focus();
            alertify.message('Reff No Could not be empty');
            return;
        }
        let serlist = [];
        serlist.push(mreff);

        Cookies.set('PRINTLABEL_FG', serlist, {expires:365});
        Cookies.set('PRINTLABEL_FG_LBLTYPE', '1', {expires:365});
        Cookies.set('PRINTLABEL_FG_SIZE', 'A4', {expires:365});
        window.open("<?=base_url('printlabel_fg')?>",'_blank');
    });
    $("#combinelbl2_btn_save").click(function (e) {
        let mtbl = document.getElementById('combinelbl2_tbl');
        let mtbltr = mtbl.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;

        if(ttlrows>1) {
            let aid = [];
            let ajob = [];
            let aitemcd = [];
            let aqty = [];
            let qty = 0;
            let sheetqty = 0;
            let locations = [];
            for(let i=1;i<ttlrows;i++){
                let _location = mtbl.rows[i].cells[6].innerText
                aid.push(mtbl.rows[i].cells[0].innerText);
                ajob.push(mtbl.rows[i].cells[1].innerText.trim());
                aitemcd.push(mtbl.rows[i].cells[2].innerText.trim());
                aqty.push(numeral(mtbl.rows[i].cells[4].innerText.trim()).value());
                qty += numeral(mtbl.rows[i].cells[4].innerText).value();
                sheetqty += numeral(mtbl.rows[i].cells[5].innerText).value();
                if(!locations.includes(_location)) {
                    locations.push(_location)
                }
            }
            if(locations.length > 1) {
                alertify.warning(`Unable to join label from different location`)
                return
            }
            if(aid.length>1){
                if(!confirm(`Are you sure ?`)) {
                    return
                }
                document.getElementById('combinelbl2_btn_save').disabled=true;
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SER/combine2_save')?>",
                    data: {inid: aid, injob: ajob, initemcd: aitemcd , inqty: aqty ,inqtyt: qty, insheetqty: sheetqty},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd!='0'){
                            $("#combinelbl2_tbl tbody").empty();
                            alertify.success(response.status[0].msg);
                            document.getElementById('combinelbl2_reffcdnew').value = response.status[0].nreffcode;
                        } else {
                            alertify.message(response.status[0].msg);
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.message('combine 1 label ?');
            }
        } else {
            alertify.message('nothing to be processed');
        }
    });
    $("#combinelbl2_reffcd").keypress(function (e) {
        if(e.which==13){
            let mreff = $(this).val();
            if(mreff.includes("|")){
                let ar = mreff.split("|");
                mreff = ar[5].substr(2,ar[5].length-2);
                $(this).val(mreff);
            }
            if(mreff.length==18 || mreff.length==16){
                let mtbl = document.getElementById('combinelbl2_tbl');
                let mtbltr = mtbl.getElementsByTagName('tr');
                let ttlrows = mtbltr.length;
                for(let i=0;i<ttlrows;i++){
                    if(mtbl.rows[i].cells[0].innerText==mreff){
                        alertify.warning('the label is already in the list below');
                        document.getElementById('combinelbl2_reffcd').value='';
                        return;
                    }
                }
                if(document.getElementById('combinelbl2_reffcd').readOnly){
                    alertify.message('Please wait');
                    return;
                }
                document.getElementById('combinelbl2_reffcd').readOnly=true;
                document.getElementById('combinelbl2_lblinfo').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SER/combine2_prep')?>",
                    data: {inreffcd: mreff},
                    dataType: "json",
                    success: function (response) {
                        document.getElementById('combinelbl2_lblinfo').innerHTML = '';
                        document.getElementById('combinelbl2_reffcd').readOnly=false;
                        document.getElementById('combinelbl2_reffcd').value='';
                        if(response.status[0].cd!='0'){
                            document.getElementById('combinelbl2_reffcd').value='';
                            let mtbl = document.getElementById('combinelbl2_tbl');
                            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                            let mtbltr = mtbl.getElementsByTagName('tr');
                            let ttlrows = response.data.length;
                            let ttlrowsTEMP = mtbltr.length;
                            let newrow, newcell, newText;
                            for(let i=1;i<ttlrowsTEMP;i++){
                                if(mtbl.rows[i].cells[2].innerText.trim()!=response.data[0].SER_ITMID.trim()){
                                    alertify.warning('Currently, system only allow to combine same Assy Code ');
                                    return;
                                }
                            }
                            for(let i=0;i<ttlrows;i++){
                                newrow = tableku2.insertRow(-1);
                                newcell = newrow.insertCell(0);
                                newText = document.createTextNode(response.data[i].SER_ID);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(1);
                                newText = document.createTextNode(response.data[i].SER_DOC);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(2);
                                newText = document.createTextNode(response.data[i].SER_ITMID);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(3);
                                newText = document.createTextNode(response.data[i].MITM_ITMD1);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(4);
                                newText = document.createTextNode(numeral(response.data[i].SER_QTY).value());
                                newcell.style.cssText = 'text-align: right;';
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(5);
                                newText = document.createTextNode(numeral(response.data[i].SER_SHEET).value());
                                newcell.style.cssText = 'display: none;';
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(-1);
                                newcell.innerText = response.last_location
                                newcell.classList.add('text-center')
                            }
                        } else {
                            alertify.warning(response.status[0].msg);
                        }

                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('Invalid Reff No');
                document.getElementById('combinelbl2_reffcd').value='';
            }
        }
    });
    $("#combinelbl2_btnnew").click(function (e) {
        document.getElementById('combinelbl2_reffcd').value='';
        document.getElementById('combinelbl2_reffcd').readOnly=false;
        document.getElementById('combinelbl2_btn_save').disabled=false;
        document.getElementById('combinelbl2_reffcd').focus();
        $("#combinelbl2_tbl tbody").empty();
        document.getElementById('combinelbl2_reffcdnew').value='';
    });

</script>