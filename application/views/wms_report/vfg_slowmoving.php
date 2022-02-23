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
        top: 26px;
    }   
</style>
<div style="padding: 10px">
    <div class="container-fluid">                     
        <div class="row" id="fgslowmoving_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Business Group</span>
                    <input type="text" class="form-control" id="fgslowmoving_txt_bg" readonly onclick="fgslowmoving_txt_bg_eCK()">
                </div>
            </div>
            <div class="col-md-6 mb-1 text-center">                       
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="fgslowmoving_btn_gen" onclick="fgslowmoving_btn_gen_eCK()"><i class="fas fa-sync"></i></button>                    
                    <button class="btn btn-outline-primary" title="copy the data below" type="button" id="fgslowmoving_btn_copy" onclick="fgslowmoving_btn_copy_eCK()"><i class="fas fa-copy"></i></button>                    
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="fgslowmoving_divku">
                    <table id="fgslowmoving_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>                                
                                <th  class="text-end">Qty</th>                                
                                <th  class="text-center">Last Transaction</th>
                                <th  class="text-center">Last Incoming</th>
                                <th  class="text-end">Day (Today-Last Transaction)</th>
                                <th  class="text-end">Day (Today-Last Incoming)</th>
                            </tr>                           
                        </thead>
                        <tbody>                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>    
        <div class="row" id="fgslowmoving_stack3">
            <div class="col-md-6 mb-1 text-end">
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >TOTAL</span>                    
                    <input type="text" class="form-control" id="fgslowmoving_txt_total" readonly>
                </div>
            </div>
        </div>   	
    </div>
</div>
<div class="modal fade" id="fgslowmoving_BG">
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
                <div class="col" onclick="fgslowmoving_selectBG_eC(event)">
                    <div class="table-responsive" id="fgslowmoving_tblbg_div">
                        <table id="fgslowmoving_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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
    var fgslowmoving_a_BG = [];
    var fgslowmoving_a_BG_NM = [];
    function fgslowmoving_txt_bg_eCK(){
        $("#fgslowmoving_BG").modal('show')
    }
    function fgslowmoving_btn_copy_eCK(){
        cmpr_selectElementContents(document.getElementById('fgslowmoving_tbl'))
        document.execCommand("copy");
        alertify.message("Copied")
    }
    fgslowmoving_e_getBG()
    function fgslowmoving_e_getBG(){
        $.ajax({            
            url: "<?=base_url('ITH/get_bs_group')?>",            
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("fgslowmoving_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("fgslowmoving_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("fgslowmoving_tblbg")
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
    function fgslowmoving_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('fgslowmoving_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim')){
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = fgslowmoving_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        fgslowmoving_a_BG.splice(getINDX, 1)
                        fgslowmoving_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if(e.target.textContent.length!=0){
                        fgslowmoving_a_BG.push(e.target.previousElementSibling.innerText)
                        fgslowmoving_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    $("#fgslowmoving_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        fgslowmoving_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('fgslowmoving_txt_bg').value = strDisplay.substr(0,strDisplay.length-2)
    })
    $("#fgslowmoving_divku").css('height', $(window).height()   
    -document.getElementById('fgslowmoving_stack1').offsetHeight 
    -document.getElementById('fgslowmoving_stack3').offsetHeight     
    -100);
    function fgslowmoving_btn_gen_eCK(){
        const btnsync = document.getElementById('fgslowmoving_btn_gen')
        btnsync.disabled = true
        btnsync.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
        let bgroup = fgslowmoving_a_BG
        $.ajax({            
            url: "<?=base_url('ITH/fg_slow_moving')?>",
            data: {bsgrp: bgroup},
            dataType: "JSON",
            success: function (response) {
                btnsync.disabled = false
                btnsync.innerHTML = `<i class="fas fa-sync"></i>`
                let ttlrows = response.data.length;
                let mydes = document.getElementById("fgslowmoving_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("fgslowmoving_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                let tabell = myfrag.getElementById("fgslowmoving_tbl");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;                
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].STKQTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newcell.innerHTML = response.data[i].ITH_ITMCD
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].MITM_ITMD1                    
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].STKQTY).format(',')
                    newcell = newrow.insertCell(3);
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].LTSDELV ? response.data[i].LTSDELV : response.data[i].LTSDT
                    newcell = newrow.insertCell(4);
                    newcell.classList.add('text-center')
                    newcell.innerHTML = response.data[i].LTSINC
                    newcell = newrow.insertCell(5);
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].DAYLEFT
                    newcell = newrow.insertCell(6);
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].DAYLEFTINC
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('fgslowmoving_txt_total').value = numeral(ttlqty).format(',')
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                btnsync.disabled = false
                btnsync.innerHTML = `<i class="fas fa-sync"></i>`
            }
        })
    }
</script>