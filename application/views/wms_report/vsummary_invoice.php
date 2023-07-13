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
        <div class="row" id="rsuminv_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >From</span>
                    <input type="text" class="form-control" id="rsuminv_txt_dt" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >To</span>                    
                    <input type="text" class="form-control" id="rsuminv_txt_dt2" readonly>
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Business Group</span>
                    <input type="text" class="form-control" id="rsuminv_cmb_bg" readonly onclick="rsuminv_bisgrup_eC()">
                </div>
            </div>
        </div>
       
        <div class="row" id="rsuminv_stack2">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="rsuminv_btn_gen" onclick="rsuminv_btn_gen_eCK()">Search</button>    
                    <div class="btn-group btn-group-sm" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export to
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="rsuminv_btn_xls" onclick="rsuminv_btn_xls_eCK()"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>                            
                        </ul>
                    </div>                   
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <span id="rsuminv_lblinfo" class="badge bg-info"></span>               
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rsuminv_divku">
                    <table id="rsuminv_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle text-center">Ship Date</th>
                                <th  class="align-middle">DO. NO.</th>
                                <th  class="align-middle">Customer DO. NO.</th>
                                <th  class="align-middle">Delivery Code</th>
                                <th  class="align-middle">No.AJU</th>                                
                                <th  class="align-middle">Nopen</th>
                                <th  class="align-middle">SPPB No</th>
                                <th  class="align-middle text-center">Invoice Date <br> (Confirmation)</th>
                                <th  class="align-middle text-center">Invoice Date <br> (Document)</th>
                                <th  class="align-middle text-center">NO. Invoice STX</th>
                                <th  class="align-middle text-center">NO. Invoice SMT</th>
                                <th  class="align-middle text-center">Model Code</th>
                                <th  class="align-middle text-center">Model Description</th>
                                <th  class="align-middle text-center">CPO NO</th>
                                <th  class="align-middle text-center">Ship QTY</th>
                                <th  class="align-middle text-center">Sales Price</th>
                                <th  class="align-middle text-center">Amount</th>                                
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
<div class="modal fade" id="rsuminv_BG">
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
                <div class="col" onclick="rsuminv_selectBG_eC(event)">
                    <div class="table-responsive" id="rsuminv_tblbg_div">
                        <table id="rsuminv_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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
var rsuminv_a_BG = [];
var rsuminv_a_BG_NM = [];

function rsuminv_btn_xls_eCK() {
    let mdate1 = document.getElementById('rsuminv_txt_dt').value;
    let mdate2 = document.getElementById('rsuminv_txt_dt2').value;
    let bgroup = rsuminv_a_BG
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
    window.open("<?=base_url('DELVHistory/report_summary_inv_as_xls')?>",'_blank');
}

$("#rsuminv_divku").css('height', $(window).height()   
    -document.getElementById('rsuminv_stack1').offsetHeight 
    -document.getElementById('rsuminv_stack2').offsetHeight    
    -100);
function rsuminv_btn_gen_eCK() {
    const date1 = document.getElementById('rsuminv_txt_dt').value
    const date2 = document.getElementById('rsuminv_txt_dt2').value
    let bgroup = rsuminv_a_BG
    console.log(bgroup)
    const btnprc = document.getElementById('rsuminv_btn_gen')
    btnprc.disabled = true
    btnprc.innerHTML = 'Please wait'
    $.ajax({
        type: "GET",
        url: "<?=base_url('DELVHistory/report_summary_inv')?>",
        data: {date1: date1, date2: date2, bsgrp: bgroup },
        dataType: "json",
        success: function (response) {
            btnprc.disabled = false
            btnprc.innerHTML = 'Search'
            let ttlrows = response.data.length;            
            let mydes = document.getElementById("rsuminv_divku");
            let myfrag = document.createDocumentFragment();
            let mtabel = document.getElementById("rsuminv_tbl");
            let cln = mtabel.cloneNode(true);
            myfrag.appendChild(cln);
            let tabell = myfrag.getElementById("rsuminv_tbl");
            let tableku2 = tabell.getElementsByTagName("tbody")[0];
            let newrow, newcell, newText;
			let myitmttl = 0;
            tableku2.innerHTML='';            
            for (let i = 0; i<ttlrows; i++){
                newrow = tableku2.insertRow(-1);
                newcell = newrow.insertCell(0)
                newcell.innerHTML = response.data[i].ITH_DATEC
                newcell = newrow.insertCell(1)
                newcell.innerHTML = response.data[i].ITH_DOC
                newcell = newrow.insertCell(2)
                newcell.innerHTML = response.data[i].DLV_CUSTDO
                newcell = newrow.insertCell(3)
                newcell.innerHTML = response.data[i].DLV_CONSIGN
                newcell = newrow.insertCell(4)
                newcell.innerHTML = response.data[i].NOMAJU
                newcell = newrow.insertCell(5)
                newcell.innerHTML = response.data[i].NOMPEN
                newcell = newrow.insertCell(6)
                newcell.innerHTML = response.data[i].DLV_SPPBDOC
                newcell = newrow.insertCell(7)
                newcell.innerHTML = response.data[i].ITH_DATEC
                newcell = newrow.insertCell(8)
                newcell.innerHTML = response.data[i].INVDT
                newcell = newrow.insertCell(9)
                newcell.innerHTML = response.data[i].DLV_INVNO
                newcell = newrow.insertCell(10)
                newcell.innerHTML = response.data[i].DLV_SMTINVNO
                newcell = newrow.insertCell(11)
                newcell.innerHTML = response.data[i].ITH_ITMCD
                newcell = newrow.insertCell(12)
                newcell.innerHTML = response.data[i].ITMDESCD
                newcell = newrow.insertCell(13)
                newcell.innerHTML = response.data[i].DLVPRC_CPO
                newcell = newrow.insertCell(14)
                newcell.classList.add('text-end')
                newcell.innerHTML = response.data[i].DLVPRC_QTY
                newcell = newrow.insertCell(15)
                newcell.classList.add('text-end')
                if(response.data[i].DLVPRC_PRC) {
                    newcell.innerHTML = response.data[i].DLVPRC_PRC.substr(0,1)=='.' ? '0'+response.data[i].DLVPRC_PRC : response.data[i].DLVPRC_PRC
                } else {
                    newcell.innerHTML = response.data[i].DLVPRC_PRC

                }
                newcell = newrow.insertCell(16)
                newcell.classList.add('text-end')
                newcell.innerHTML = response.data[i].AMOUNT
            }
            mydes.innerHTML='';
            mydes.appendChild(myfrag);
        }, error : function(xhr, xopt, xthrow){
            btnprc.disabled = false
            btnprc.innerHTML = 'Search'
            alertify.error(xthrow);
        }
    });
}
function rsuminv_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('rsuminv_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = rsuminv_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        rsuminv_a_BG.splice(getINDX, 1)
                        rsuminv_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        rsuminv_a_BG.push(e.target.previousElementSibling.innerText)
                        rsuminv_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }

    function rsuminv_bisgrup_eC() {
        $("#rsuminv_BG").modal('show')
    }
    $("#rsuminv_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        rsuminv_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('rsuminv_cmb_bg').value = strDisplay.substr(0,strDisplay.length-2)
    })
    rsuminv_e_getBG()
    function rsuminv_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>",            
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("rsuminv_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rsuminv_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rsuminv_tblbg")
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
    $("#rsuminv_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rsuminv_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rsuminv_txt_dt").datepicker('update', new Date());
    $("#rsuminv_txt_dt2").datepicker('update', new Date());
</script>
