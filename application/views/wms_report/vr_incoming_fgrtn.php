<style>
.accordion_ana {
    background-color: #fff;
    color: #444;
    cursor: pointer;
    padding: 18px;
    width: 100%;
    border: none;
    text-align: left;
    outline: none;
    font-size: 15px;
    transition: 0.4s;
}

.active_ana, .accordion_ana:hover {
    background-color: #DAEEF5;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.5s;
  
}

.accordion_ana:after {
  content: '\002B';
  color: #777;
  font-weight: bold;
  float: right;
  margin-left: 5px;
}

.active_ana:after {
  content: "\2212";
}

.panel_ana {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
</style>
<div style="padding: 5px">
    <div class="container-fluid">       
        <!-- <div class="row"> -->
        <button class="accordion_ana">Resume</button>
        <div class="panel_ana">
            <div style="padding: 3px">
                <div class="container-fluid">       
                    <div class="row">
                        <div class="col-md-3 mb-1">
                            <div class="input-group input-group-sm">                    
                                <span class="input-group-text" >Business Group</span>                    
                                <select class="form-select" id="rincfgrtn_bisgrup">                        
                                    <?php 
                                    $todis = '';
                                    foreach($lgroup as $r){
                                        $todis .= '<option value="'.trim($r->MBSG_BSGRP).'">'.trim($r->MBSG_DESC).'</option>';
                                    }
                                    echo $todis;
                                    ?>
                                </select>                                   
                            </div>                                
                        </div>
                        <div class="col-md-4 mb-1">
                            <div class="input-group input-group-sm">                    
                                <span class="input-group-text" >Doc No</span>
                                <input type="text" class="form-control" id="rincfgrtn_txt_item">
                            </div>
                        </div>
                        <div class="col-md-3 mb-1">
                            <div class="input-group input-group-sm">                    
                                <span class="input-group-text" >Item Id</span>
                                <input type="text" class="form-control" id="rincfgrtn_txt_itemid">
                                <button class="btn btn-primary" type="button" id="rincfgrtn_btn_gen"><i class="fas fa-search"></i></button>                    
                            </div>
                        </div>
                        <div class="col-md-2 mb-1">                
                            <div class="form-check-inline">
                                <input type="radio" id="rincfgrtn_rdopen" class="form-check-input" name="rincfgrtn_sts" value="0" checked>
                                <label class="form-check-label" for="rincfgrtn_rdopen">
                                Open
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <input type="radio" id="rincfgrtn_rdclose" class="form-check-input" name="rincfgrtn_sts" value="1">
                                <label class="form-check-label" for="rincfgrtn_rdclose">
                                Close
                                </label>
                            </div>
                        </div>
                    </div>        
                    <div class="row">
                        <div class="col-md-12 mb-1">                       
                            <span id="rincfgrtn_lblinfo" class="badge bg-info"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="table-responsive" id="rincfgrtn_divku">
                                <table id="rincfgrtn_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                                    <thead class="table-light">
                                        <tr>                                
                                            <th rowspan="2" class="align-middle">Business Group</th>
                                            <th rowspan="2" class="align-middle">Doc No.</th>
                                            <th rowspan="2" class="align-middle">Notice No.</th>
                                            <th rowspan="2" class="align-middle">Item Id</th>
                                            <th rowspan="2" class="align-middle">New Item Id</th>
                                            <th rowspan="2" class="align-middle">Item Name</th>
                                            <th colspan="5" class="text-center">Qty</th>                                
                                        </tr>
                                        <tr>
                                            <th class="text-center">Incoming</th>
                                            <th class="text-center">QA</th>
                                            <th class="text-center">RC</th>
                                            <th class="text-center">QC</th>
                                            <th class="text-center">WH</th>
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

        <button class="accordion_ana">Outstanding</button>
        <div class="panel_ana">
            ._.
        </div>
        <!-- </div> -->
    </div>
</div>
<div class="modal fade" id="RINCFGRTN_MOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Reff. No. List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Document No.</span>
                        <input type="text" class="form-control" id="rincfgrtn_docno" readonly >
                    </div>
                </div>
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Item Id</span>
                        <input type="text" class="form-control" id="rincfgrtn_itmid" readonly >
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col mb-1 text-end">
                    <span class="badge bg-info" id="lblinfo_rincfgrtn_tbl_ost"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rincfgrtn_div_ost">
                        <table id="rincfgrtn_tbl_ost" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Reff No.</th>
                                    <th colspan="2" class="text-center">QTY</th>
                                </tr>
                                <tr>
                                    <th>Incoming</th>
                                    <th>Balance</th>
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

    function rincfgrtn_accord_init(){
        let acc = document.getElementsByClassName("accordion_ana");
        let i;

        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active_ana");
                let panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                } 
            });
        }
    }

    rincfgrtn_accord_init();
    
    $("#rincfgrtn_divku").css('height', $(window).height()*68/100);    
    function rincfgrtn_e_report(purl){
        let mdoc = document.getElementById("rincfgrtn_txt_item").value.trim();        
        let mitem = document.getElementById("rincfgrtn_txt_itemid").value.trim();        
        let mbg = $('#rincfgrtn_bisgrup').val(); 
        const rbs = document.querySelectorAll('input[name="rincfgrtn_sts"]');
        let selectedValue;
        for (const rb of rbs) {
            if (rb.checked) {
                selectedValue = rb.value;
                break;
            }
        }
        document.getElementById("rincfgrtn_lblinfo").innerText= "Please wait . . .";
        
        $.ajax({
            type: "post",
            url: purl,
            data: {indoc : mdoc, initem: mitem, inbg: mbg, insts: selectedValue},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                document.getElementById("rincfgrtn_lblinfo").innerText= ttlrows + " row(s) found";
                let mydes = document.getElementById("rincfgrtn_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rincfgrtn_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rincfgrtn_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                let qtyprd , qtyqc, qtywh, qtylot,qtygrn,qtygrnaks;
                tableku2.innerHTML='';
                let newitemcd = '';
                for (let i = 0; i<ttlrows; i++){
                    if(response.data[i].CUSTITMCD){
                        if(response.data[i].RETFG_ITMCD!=response.data[i].CUSTITMCD){
                            newitemcd = response.data[i].CUSTITMCD;
                        }
                    } else {
                        newitemcd = '';
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.data[i].MBSG_DESC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].RETFG_DOCNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].RETFG_NTCNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    if(selectedValue==0){
                        newcell.style.cssText = "cursor:pointer";
                        newcell.onclick = (event) => {
                            if(event.ctrlKey){
                                $("#RINCFGRTN_MOD").modal('show');
                                document.getElementById('rincfgrtn_docno').value = response.data[i].RETFG_DOCNO;
                                document.getElementById('rincfgrtn_itmid').value = response.data[i].RETFG_ITMCD;
                                rincfgrtn_e_showost(response.data[i].RETFG_DOCNO,response.data[i].RETFG_ITMCD );
                            }
                        }
                    }
                    newText = document.createTextNode(response.data[i].RETFG_ITMCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(newitemcd);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newcell.classList.add('text-end');
                    newText = document.createTextNode(response.data[i].RETFG_QTY);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newcell.classList.add('text-end');
                    newText = document.createTextNode(numeral(response.data[i].SER_QTY).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newcell.classList.add('text-end');
                    newText = document.createTextNode(numeral(response.data[i].RCQTY).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newcell.classList.add('text-end');
                    newText = document.createTextNode(numeral(response.data[i].QCQTY).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(10);
                    newcell.classList.add('text-end');
                    newText = document.createTextNode(numeral(response.data[i].WHQTY).format(','));
                    newcell.appendChild(newText);
                 
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#rincfgrtn_btn_gen").click(function (e) { 
        rincfgrtn_e_report("<?=base_url('ITH/getdataincfgrtn')?>");
    });
    $("#rincfgrtn_txt_item").keypress(function (e) { 
        if(e.which==13){
            rincfgrtn_e_report("<?=base_url('ITH/getdataincfgrtn')?>");
        }
    });
    function rincfgrtn_e_showost(pdocno, pitemcd){
        document.getElementById('lblinfo_rincfgrtn_tbl_ost').innerText = "Please wait";
        $("#rincfgrtn_tbl_ost tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/show_reffno_rad')?>",
            data: {docno: pdocno, itemcd: pitemcd},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                document.getElementById("lblinfo_rincfgrtn_tbl_ost").innerText= ttlrows + " row(s) found";
                let mydes = document.getElementById("rincfgrtn_div_ost");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rincfgrtn_tbl_ost");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rincfgrtn_tbl_ost");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];                                
                tableku2.innerHTML='';
                let newitemcd = '';
                for (let i = 0; i<ttlrows; i++){                
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.data[i].SER_ID);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newcell.classList.add('text-end');
                    newcell.innerHTML = numeral(response.data[i].SER_QTY).format(',');
                    newcell = newrow.insertCell(2);
                    newcell.classList.add('text-end');
                    newText = document.createTextNode(numeral(response.data[i].BALQTY).format(','));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('lblinfo_rincfgrtn_tbl_ost').innerText = "";
            }
        });
    }
</script>