<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Assy Code</label>
                    <input type="text" class="form-control" id="mitmsa_txtAssycd" onkeypress="mitmsa_txtAssycd_eKP(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Part Code</label>
                    <input type="text" class="form-control" id="mitmsa_txtItemcd" onkeypress="mitmsa_txtItemcd_eKP(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Part Code SA</label>
                    <input type="text" class="form-control" id="mitmsa_txtItemcdSA" onkeypress="mitmsa_txtItemcdSA_eKP(event)">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Remark</label>
                    <input type="text" class="form-control" id="mitmsa_txtRemark" onkeypress="mitmsa_txtRemark_eKP(event)">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="mitmsa_btn_new" onclick="mitmsa_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-primary" id="mitmsa_btn_save" onclick="mitmsa_btn_save_eC()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-primary" id="mitmsa_btn_uploadm" onclick="mitmsa_btn_uploadm_eCK()" title="Upload"><i class="fas fa-upload"></i></button>
                    <button class="btn btn-success" id="mitmsa_btn_download" onclick="mitmsa_btn_download_eCK()" title="Download"><i class="fas fa-download"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-warning" id="mitmsa_btn_remove" onclick="mitmsa_btn_remove_eC()" title="Delete selected row"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">                
                <table id="mitmsa_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">                        
                    <thead class="table-light">
                        <tr>                                
                            <th>Assy Code</th>
                            <th>Assy Name</th>
                            <th>Part Code</th>
                            <th>Part Code SA</th>
                            <th>Part Name</th>
                            <th>Part Name SA</th>
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
<input type="hidden" id="mitmsa_assyold">
<input type="hidden" id="mitmsa_partold">
<input type="hidden" id="mitmsa_partsaold">
<div class="modal fade" id="mitmsa_MODITM">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Item List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="mitmsa_btn_clear" onclick="mitmsa_btn_clear_eC()">Clear</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mitmsa_tblupload_div" onpaste="mitmsa_e_pastecol1(event)">
                        <table id="mitmsa_tblupload" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Assy Code</th>
                                    <th>Part Code</th>
                                    <th>Part Code SA</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="mitmsa_btnupload" onclick="mitmsa_btnupload_eCK()">Upload</button>
        </div>
      </div>
    </div>
</div>
<script>
    var mitmsa_pub_tbl
    var mitmsa_selected_table = 'mitmsa_tblupload'
    var mitmsa_selected_row = 1;
    var mitmsa_selected_col = 1;
    mitmsa_init_Tbl()
    function mitmsa_init_Tbl(){        
        mitmsa_pub_tbl =  $('#mitmsa_tbl').DataTable({
            select: true,
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("MSTITM/get_sa")?>',
                type: 'get', 
            },
            columns:[               
                { "data": 'FG'},
                { "data": 'FGNM'},
                { "data": 'MITMSA_ITMCD'},
                { "data": 'MITMSA_ITMCDS'},                
                { "data": 'SPTNO'},
                { "data": 'SPTNOS'},
                { "data": 'MITMSA_RMRK'},
            ]
        })
        mitmsa_pub_tbl.on('select', function(e, dt, type, indexes) {
            if (type === 'row') {
                document.getElementById('mitmsa_txtAssycd').value = mitmsa_pub_tbl.rows(indexes).data().pluck('FG')[0]
                document.getElementById('mitmsa_txtItemcd').value = mitmsa_pub_tbl.rows(indexes).data().pluck('MITMSA_ITMCD')[0]
                document.getElementById('mitmsa_txtItemcdSA').value = mitmsa_pub_tbl.rows(indexes).data().pluck('MITMSA_ITMCDS')[0]
                document.getElementById('mitmsa_assyold').value = mitmsa_pub_tbl.rows(indexes).data().pluck('FG')[0]
                document.getElementById('mitmsa_partold').value = mitmsa_pub_tbl.rows(indexes).data().pluck('MITMSA_ITMCD')[0]
                document.getElementById('mitmsa_partsaold').value = mitmsa_pub_tbl.rows(indexes).data().pluck('MITMSA_ITMCDS')[0]
                document.getElementById('mitmsa_txtRemark').value = mitmsa_pub_tbl.rows(indexes).data().pluck('MITMSA_RMRK')[0]
            }
        })
    }

    function mitmsa_btn_download_eCK() {
        if(confirm('Are you sure ?')) {
            window.open("<?=base_url('MSTITM/downloadsa')?>",'_blank');
        }
    }

    function mitmsa_btn_clear_eC() {
        document.getElementById('mitmsa_tblupload').getElementsByTagName('tbody')[0].innerHTML = ''
        mitmsa_addrow(mitmsa_selected_table);
    }

    function mitmsa_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/html')
        const mcona_tbllength = document.getElementById(mitmsa_selected_table).getElementsByTagName('tbody')[0].rows.length
        const columnLength = document.getElementById(mitmsa_selected_table).getElementsByTagName('tbody')[0].rows[0].cells.length        
        if(datapas===""){
            datapas = event.clipboardData.getData('text')
            let adatapas = datapas.split('\n')
            let ttlrowspasted = 0
            for(let c=0;c<adatapas.length;c++){
                if(adatapas[c].trim()!=''){
                    ttlrowspasted++
                }
            }
            let table = $(`#${mitmsa_selected_table} tbody`)
            let incr = 0
            if ((mcona_tbllength-mitmsa_selected_row)<ttlrowspasted) {       
                const needRows = ttlrowspasted - (mcona_tbllength-mitmsa_selected_row)
                for (let i = 0;i<needRows;i++) {
                    mitmsa_addrow(mitmsa_selected_table)
                }
            }            
            for(let i=0;i<ttlrowspasted;i++){                
                const mcol = adatapas[i].split('\t')
                const ttlcol = mcol.length                
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){             
                    table.find('tr').eq((i+mitmsa_selected_row)).find('td').eq((k+mitmsa_selected_col)).text(mcol[k].trim())
                }                
            }                            
        } else {            
            let tmpdom = document.createElement('html')
            tmpdom.innerHTML = datapas
            let myhead = tmpdom.getElementsByTagName('head')[0]
            let myscript = myhead.getElementsByTagName('script')[0]
            let mybody = tmpdom.getElementsByTagName('body')[0]
            let mytable = mybody.getElementsByTagName('table')[0]
            let mytbody = mytable.getElementsByTagName('tbody')[0]
            let mytrlength = mytbody.getElementsByTagName('tr').length
            let table = $(`#${mitmsa_selected_table} tbody`)
            let incr = 0
            let startin = 0
            
            if(typeof(myscript) != 'undefined'){ //check if clipboard from IE
                startin = 3
            }
            if((mcona_tbllength-mitmsa_selected_row)<(mytrlength-startin)){
                let needRows = (mytrlength-startin) - (mcona_tbllength-mitmsa_selected_row);                
                for(let i = 0;i<needRows;i++){
                    mitmsa_addrow(mitmsa_selected_table);
                }
            }
            
            let b = 0
            for(let i=startin;i<(mytrlength);i++){
                let ttlcol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td').length
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                    let dkol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td')[k].innerText
                    table.find('tr').eq((b+mitmsa_selected_row)).find('td').eq((k+mitmsa_selected_col)).text(dkol.trim())
                } 
                b++
            }
        }
        event.preventDefault()
    }

    $("#mitmsa_MODITM").on('hidden.bs.modal', function(){
        mitmsa_init_Tbl()
    })

    function mitmsa_tbl_tbody_tr_eC(e){
        mitmsa_selected_row = e.srcElement.parentElement.rowIndex - 1        
        mitmsa_selected_col = e.srcElement.cellIndex
    }

    function mitmsa_addrow(ptable){
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)  
        newrow.onclick = (event) => {mitmsa_tbl_tbody_tr_eC(event)}      
        newcell = newrow.insertCell(0)        
        newcell.innerHTML = 'Click here and then paste...'
        newcell.focus()

        newcell = newrow.insertCell(1)        

        newcell = newrow.insertCell(2)                                

        newcell = newrow.insertCell(3)        
        newcell.innerHTML = '_'      
    }

    function mitmsa_btn_uploadm_eCK() {
        let mymodal = new bootstrap.Modal(document.getElementById("mitmsa_MODITM"), {backdrop: 'static', keyboard: false});
        mymodal.show()        
    }

    function mitmsa_btn_new_eC() {
        document.getElementById('mitmsa_txtAssycd').value= ''
        document.getElementById('mitmsa_txtItemcd').value= ''
        document.getElementById('mitmsa_txtItemcdSA').value= ''
        document.getElementById('mitmsa_txtAssycd').focus()

        document.getElementById('mitmsa_assyold').value= ''
        document.getElementById('mitmsa_partold').value= ''
        document.getElementById('mitmsa_partsaold').value= ''
        mitmsa_txtRemark.value= ''
    }
    function mitmsa_txtAssycd_eKP(e) {
        if (e.key === 'Enter') {
            document.getElementById('mitmsa_txtItemcd').focus()
        }
    }
    function mitmsa_txtItemcd_eKP(e) {
        if(e.key === 'Enter') {
            document.getElementById('mitmsa_txtItemcdSA').focus()
        }
    }
    function mitmsa_txtItemcdSA_eKP(e) {
        if (e.key === 'Enter') {
            document.getElementById('mitmsa_txtRemark').focus()
        }
    }
    function mitmsa_txtRemark_eKP(e) {
        if (e.key === 'Enter') {
            mitmsa_btn_save.focus()
        }
    }
    function mitmsa_btn_save_eC() {
        let assyCD = document.getElementById('mitmsa_txtAssycd')
        let partCD = document.getElementById('mitmsa_txtItemcd')
        let partCDSA = document.getElementById('mitmsa_txtItemcdSA')

        let assyCDold = document.getElementById('mitmsa_assyold').value.trim()
        let partCDold = document.getElementById('mitmsa_partold').value.trim()
        let partCDSAold = document.getElementById('mitmsa_partsaold').value.trim()

        if(assyCDold.length==0) {
            assyCDold = assyCD.value
            partCDold = partCD.value
            partCDSAold = partCDSA.value
        }

        if(assyCD.value.trim().length<=3) {
            assyCD.focus()
            alertify.warning("assy code is required");
            return;
        }
        if(partCD.value.trim().length<=3) {
            partCD.focus()
            alertify.warning("part code is required");
            return;
        }
        if(partCDSA.value.trim().length<=3) {
            partCDSA.focus()
            alertify.warning("part code SA is required");
            return;
        }
        if (confirm("Are you sure ?")) {
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTITM/saveSA')?>",
                data: {oldassy: assyCDold
                , oldpart : partCDold
                , oldpartSA : partCDSAold
                , newassy : assyCD.value
                , newpart : partCD.value
                , newpartSA : partCDSA.value
                , remark : mitmsa_txtRemark.value
                },
                dataType: "JSON",
                success: function (response) {
                    if(response.status[0].cd==='1') {
                        alertify.success(response.status[0].msg)
                        mitmsa_init_Tbl()
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            })
        }
    }

    function mitmsa_btnupload_eCK() {
        const rmtable = document.getElementById('mitmsa_tblupload').getElementsByTagName('tbody')[0]
        const rmtablecount = rmtable.getElementsByTagName('tr').length
        let arm_assycd = []
        let arm_itemCd = []
        let arm_itemCdsa = []        
        for(let i = 0; i<rmtablecount; i++) {
            const tmpassycd = rmtable.rows[i].cells[0].innerText.replace(/\n+/g, '')
            let tmpitemcd = rmtable.rows[i].cells[1].innerText.replace(/\n+/g, '')
            let tmpitemcdsa = rmtable.rows[i].cells[2].innerText.replace(/\n+/g, '')
            if(tmpitemcd.length) {
                arm_assycd.push(tmpassycd)
                arm_itemCd.push(tmpitemcd)
                arm_itemCdsa.push(tmpitemcdsa)
            }
        }
        if(arm_assycd.length) {
            if(confirm("Are you sure ?")) {
                document.getElementById('mitmsa_btnupload').disabled = true
                document.getElementById('mitmsa_btnupload').innerHTML = 'Please wait'
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('MSTITM/uploadSA')?>",
                    data: {arm_assycd: arm_assycd, arm_itemCd: arm_itemCd, arm_itemCdsa: arm_itemCdsa},
                    dataType: "json",
                    success: function (response) {
                        document.getElementById('mitmsa_btnupload').innerHTML = 'Upload'
                        document.getElementById('mitmsa_btnupload').disabled = false
                        const ttlrows = response.data.length
                        if(ttlrows) {
                            for(let i = 0; i<rmtablecount; i++) { 
                                const tmpassycd = rmtable.rows[i].cells[0].innerText.replace(/\n+/g, '')
                                let tmpitemcd = rmtable.rows[i].cells[1].innerText.replace(/\n+/g, '')
                                let tmpitemcdsa = rmtable.rows[i].cells[2].innerText.replace(/\n+/g, '')
                                for(let r=0; r<ttlrows; r++) {
                                    if(tmpassycd==response.data[r].newassy
                                    && tmpitemcd == response.data[r].newpart
                                    && tmpitemcdsa == response.data[r].newpartSA
                                    ) {
                                        rmtable.rows[i].cells[3].innerText = response.data[r].status
                                        break;
                                    }
                                }
                            }
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow)
                        document.getElementById('mitmsa_btnupload').disabled = false
                        document.getElementById('mitmsa_btnupload').innerHTML = 'Upload'
                    }
                })
            } else {
    
            }
        } else {
            alertify.message('Nothing to be uploaded')
        }
    }

    function mitmsa_btn_remove_eC() {
        let assyCDold = document.getElementById('mitmsa_assyold').value.trim()
        let partCDold = document.getElementById('mitmsa_partold').value.trim()
        let partCDSAold = document.getElementById('mitmsa_partsaold').value.trim()
        if(assyCDold.length==0) {
            alertify.message('select a data on the the table below first')
            return
        }
        if (confirm("Are yous sure ?")) {
            $.ajax({
                type: "post",
                url: "<?=base_url('MSTITM/removeSA')?>",
                data: {assyCDold: assyCDold, partCDold: partCDold, partCDSAold: partCDSAold},
                dataType: "json",
                success: function (response) {
                    if (response.status[0].cd==='1') {
                        alertify.success(response.status[0].msg)
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                    mitmsa_init_Tbl()
                    document.getElementById('mitmsa_txtAssycd').value = ''
                    document.getElementById('mitmsa_txtItemcd').value = ''
                    document.getElementById('mitmsa_txtItemcdSA').value = ''
                }
            });
        }
    }
</script>