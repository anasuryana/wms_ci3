<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 25px;
    }  
</style>

<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="rsumpartreq_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >From</span>
                    <input type="text" class="form-control" id="rsumpartreq_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >To</span>                    
                    <input type="text" class="form-control" id="rsumpartreq_txt_dt2" readonly>
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Business Group</span>                    
                    <input type="text" class="form-control" id="rsumpartreq_cmb_bg" readonly onclick="rsumpartreq_bisgrup_eC()">
                </div>
            </div>            
        </div>
       
        <div class="row" id="rsumpartreq_stack2">
            <div class="col-md-12 mb-1">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rsumpartreq_btn_gen" onclick="rsumpartreq_btn_gen_eCK()">Search</button>    
                    <div class="btn-group btn-group-sm" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export to
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="rsumpartreq_btn_xls" onclick="rsumpartreq_btn_xls_eCK()"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>                            
                        </ul>
                    </div>                   
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <span id="rsumpartreq_lblinfo" class="badge bg-info"></span>               
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rsumpartreq_divku">
                    <table id="rsumpartreq_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle text-center">Business</th>
                                <th  class="align-middle">Trans Date</th>
                                <th  class="align-middle">TX ID</th>
                                <th  class="align-middle">Invoice</th>
                                <th  class="align-middle">MEGA Doc</th>                                
                                <th  class="align-middle">Consignee</th>
                                <th  class="align-middle text-center">Reff Document</th>
                                <th  class="align-middle text-center">Description</th>
                                <th  class="align-middle text-center">Item Code</th>
                                <th  class="align-middle text-center">Item Name</th>
                                <th  class="align-middle text-center">Return Qty</th>
                                <th  class="align-middle text-center">Location From</th>
                                <th  class="align-middle text-center">Price</th>
                                <th  class="align-middle text-center">Amount</th>
                                <th  class="align-middle text-center">3rd Party</th>
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
<div class="modal fade" id="rsumpartreq_BG">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Business Group List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col" onclick="rsumpartreq_selectBG_eC(event)">
                    <div class="table-responsive" id="rsumpartreq_tblbg_div">
                        <table id="rsumpartreq_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center d-none">BG</th>
                                    <th class="text-center">Business</th>
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
var rsumpartreq_a_BG = [];
var rsumpartreq_a_BG_NM = [];

function rsumpartreq_btn_xls_eCK() {
    let mdate1 = document.getElementById('rsumpartreq_txt_dt').value;
    let mdate2 = document.getElementById('rsumpartreq_txt_dt2').value;
    let bgroup = rsumpartreq_a_BG
    let sbg = '';
    for(let i=0; i< bgroup.length; i++){
        sbg += bgroup[i] + "','";
    }
    if(sbg!=''){
        sbg = "'"+sbg+"'";
    }
    Cookies.set('CKPSI_BG', sbg, {expires:365});        
    Cookies.set('CKPSI_DATE1', mdate1, {expires:365});
    Cookies.set('CKPSI_DATE2', mdate2, {expires:365});
    window.open("<?=base_url('DELV/report_summary_return_as_xls')?>",'_blank');
}

$("#rsumpartreq_divku").css('height', $(window).height()   
    -document.getElementById('rsumpartreq_stack1').offsetHeight 
    -document.getElementById('rsumpartreq_stack2').offsetHeight    
    -100);
function rsumpartreq_btn_gen_eCK() {
    const date1 = document.getElementById('rsumpartreq_txt_dt').value
    const date2 = document.getElementById('rsumpartreq_txt_dt2').value
    let bgroup = rsumpartreq_a_BG
    console.log(bgroup)
    const btnprc = document.getElementById('rsumpartreq_btn_gen')
    btnprc.disabled = true
    btnprc.innerHTML = 'Please wait'
    $.ajax({
        type: "GET",
        url: "<?=base_url('DELV/report_summary_part_return')?>",
        data: {date1: date1, date2: date2, bsgrp: bgroup },
        dataType: "json",
        success: function (response) {
            btnprc.disabled = false
            btnprc.innerHTML = 'Search'
            let ttlrows = response.data.length
            let mydes = document.getElementById("rsumpartreq_divku");
            let myfrag = document.createDocumentFragment()
            let mtabel = document.getElementById("rsumpartreq_tbl");
            let cln = mtabel.cloneNode(true);
            myfrag.appendChild(cln);
            let tabell = myfrag.getElementById("rsumpartreq_tbl");
            let tableku2 = tabell.getElementsByTagName("tbody")[0];
            let newrow, newcell, newText;
			let myitmttl = 0;
            tableku2.innerHTML=''
            for (let i = 0; i<ttlrows; i++){
                newrow = tableku2.insertRow(-1);
                newcell = newrow.insertCell(0)
                newcell.innerHTML = response.data[i].DLV_BSGRP
                newcell = newrow.insertCell(1)
                newcell.innerHTML = response.data[i].DLV_DATE
                newcell = newrow.insertCell(2)
                newcell.innerHTML = response.data[i].ITH_DOC
                newcell = newrow.insertCell(3)
                newcell.innerHTML = response.data[i].DLV_SMTINVNO
                newcell = newrow.insertCell(4)
                newcell.innerHTML = response.data[i].DLV_PARENTDOC
                newcell = newrow.insertCell(5)
                newcell.innerHTML = response.data[i].DLV_CONSIGN
                newcell = newrow.insertCell(6)
                newcell.innerHTML = response.data[i].DLV_RPRDOC
                newcell = newrow.insertCell(7)
                newcell.innerHTML = response.data[i].DLV_DSCRPTN
                newcell = newrow.insertCell(8)
                newcell.innerHTML = response.data[i].ITH_ITMCD
                newcell = newrow.insertCell(9)
                newcell.innerHTML = response.data[i].MITM_SPTNO
                newcell = newrow.insertCell(10)
                newcell.classList.add('text-end')
                newcell.innerHTML = numeral(response.data[i].DLVPRC_QTY).format(',')
                newcell = newrow.insertCell(11)
                newcell.innerHTML = response.data[i].DLV_LOCFR
                newcell = newrow.insertCell(12)
                newcell.classList.add('text-end')
                newcell.innerHTML = response.data[i].DLVPRC_PRC.substr(0,1) == '.' ? '0'+response.data[i].DLVPRC_PRC : response.data[i].DLVPRC_PRC
                newcell = newrow.insertCell(13)
                newcell.classList.add('text-end')
                newcell.innerHTML = response.data[i].AMOUNT.substr(0,1) == '.' ? '0'+response.data[i].AMOUNT : response.data[i].AMOUNT
                newcell = newrow.insertCell(14)
                newcell.innerHTML = response.data[i].MSUP_SUPCR
            }
            mydes.innerHTML=''
            mydes.appendChild(myfrag)
        }, error : function(xhr, xopt, xthrow){
            btnprc.disabled = false
            alertify.error(xthrow);
        }
    });
}
    function rsumpartreq_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('rsumpartreq_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = rsumpartreq_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        rsumpartreq_a_BG.splice(getINDX, 1)
                        rsumpartreq_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        rsumpartreq_a_BG.push(e.target.previousElementSibling.innerText)
                        rsumpartreq_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }

    function rsumpartreq_bisgrup_eC() {
        $("#rsumpartreq_BG").modal('show')
    }
    $("#rsumpartreq_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        rsumpartreq_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('rsumpartreq_cmb_bg').value = strDisplay.substr(0,strDisplay.length-2)
    })
    rsumpartreq_e_getBG()
    function rsumpartreq_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>",            
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("rsumpartreq_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rsumpartreq_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rsumpartreq_tblbg")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    newcell = newrow.insertCell(0);
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].id
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].text
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#rsumpartreq_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rsumpartreq_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rsumpartreq_txt_dt").datepicker('update', new Date());
    $("#rsumpartreq_txt_dt2").datepicker('update', new Date());
</script>
