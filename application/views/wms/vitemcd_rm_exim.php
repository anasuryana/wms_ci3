<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="mst_rm_stack0">
            <div class="col-md-6 mb-1"> 
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="mst_rm_exim_btn_save" onclick="mst_rm_exim_btn_save_e_click()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-outline-success" type="button" id="mst_rm_exim_btn_exp" onclick="mst_rm_exim_btn_exp_eCK()">Export to XLS</button>
                    <button class="btn btn-outline-success" type="button" id="mst_rm_exim_btn_import" data-bs-toggle="modal" title="Import" data-bs-target="#mst_rm_exim_IMPORTDATA"><i class="fas fa-file-import"></i></button>
                </div>                
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Search by</span>                    
                    <select class="form-select" id="mst_rm_exim_seachby">
                        <option value="itemcd">Item Code</option>
                        <option value="itemnm">Item Name</option>
                    </select>
                    <input type="text" class="form-control" id="mst_rm_exim_txt_search" title="press enter to start searching" onkeypress="mst_rm_exim_txt_search_e_search(event)">
                </div>         
            </div>            
        </div>
        <div class="row" id="mst_rm_stack1">
            <div class="col-md-12 mb-1">                       
                <span id="mst_rm_exim_lblinfo" class="badge bg-info">-</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="mst_rm_exim_divku">
                    <table id="mst_rm_exim_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">Item Code</th>
                                <th  class="align-middle">Item Name</th>
                                <th  class="align-middle">HS Code</th>
                                <th  class="align-middle">Net Weight</th>
                                <th  class="align-middle">Gross Weight</th>
                                <th  class="align-middle">BM</th>
                                <th  class="align-middle">PPN</th>
                                <th  class="align-middle">PPH</th>
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
<div class="modal fade" id="mst_rm_exim_IMPORTDATA">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Import data</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group input-group-sm">
                        <button title="Download a Template File (*.xls File)" id="mst_rm_exim_btn_download" onclick="mst_rm_exim_btn_download_eCK(this)" class="btn btn-outline-success btn-sm"><i class="fas fa-file-download"></i></button>
                        <input type="file" id="mst_rm_exim_xlf_new"  class="form-control">                            
                        <button id="mst_rm_exim_btn_startimport" onclick="mst_rm_exim_btn_startimport_eCK(this)" class="btn btn-primary btn-sm">Start Importing</button>                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">                
                    <div class="progress">
                        <div id="mst_rm_exim_lblsaveprogress" class="progress-bar progress-bar-success progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span class="sr-only">0</span>
                        </div>
                    </div>
                </div>                
            </div>
        </div>             
      </div>
    </div>
</div>
<script>
    var mst_rm_exim_selected_index = 0;
    $("#mst_rm_exim_divku").css('height', $(window).height()   
    -document.getElementById('mst_rm_stack0').offsetHeight 
    -document.getElementById('mst_rm_stack1').offsetHeight     
    -100);
    function mst_rm_exim_btn_save_e_click(){        
        let tabell = document.getElementById("mst_rm_exim_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let itemcd = tableku2.rows[mst_rm_exim_selected_index].cells[0].innerText
        let itemhscd = tableku2.rows[mst_rm_exim_selected_index].cells[2].innerText
        let netweight = tableku2.rows[mst_rm_exim_selected_index].cells[3].innerText
        let grosweight = tableku2.rows[mst_rm_exim_selected_index].cells[4].innerText
        let beamasuk = tableku2.rows[mst_rm_exim_selected_index].cells[5].innerText
        let ppn = tableku2.rows[mst_rm_exim_selected_index].cells[6].innerText
        let pph = tableku2.rows[mst_rm_exim_selected_index].cells[7].innerText
        if(confirm("Are you sure ?")) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('MSTITM/rm_exim')?>",
                data:  {itemcd : itemcd, itemhscd: itemhscd, netweight: netweight, grosweight: grosweight
                ,beamasuk: beamasuk, ppn: ppn, pph: pph },
                dataType: "json",
                success: function (response) {
                    alertify.message(response.status[0].msg)
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            })
        }
    }
    function mst_rm_exim_txt_search_e_search(e){
        if(e.which==13){
            let search = document.getElementById('mst_rm_exim_txt_search').value;
            let searchby = document.getElementById('mst_rm_exim_seachby').value;
            document.getElementById('mst_rm_exim_lblinfo').innerText = "Please wait...";
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTITM/searchrm_exim')?>",
                data: {insearch: search, insearchby: searchby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    document.getElementById('mst_rm_exim_lblinfo').innerText = ttlrows + " row(s) found";
                    let mydes = document.getElementById("mst_rm_exim_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("mst_rm_exim_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                
                    let tabell = myfrag.getElementById("mst_rm_exim_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newrow.onclick =  function(){mst_rm_exim_selected_index = i};
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.data[i].MITM_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].MITM_ITMD1);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newcell.contentEditable = true;
                        newText = document.createTextNode(response.data[i].MITM_HSCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newcell.contentEditable = true;
                        newcell.title ="Net Weight";
                        newText = document.createTextNode(response.data[i].MITM_NWG);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newcell.contentEditable = true;
                        newcell.title ="Gross Weight";
                        newText = document.createTextNode(response.data[i].MITM_GWG);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.contentEditable = true;
                        newcell.title ="BM";
                        newText = document.createTextNode(response.data[i].MITM_BM);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        newcell.contentEditable = true;
                        newcell.title ="PPN";
                        newText = document.createTextNode(response.data[i].MITM_PPN);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);
                        newcell.contentEditable = true;
                        newcell.title ="PPH";
                        newText = document.createTextNode(response.data[i].MITM_PPH);
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            })
        }        
    }
    function mst_rm_exim_btn_exp_eCK(){
        let search = document.getElementById('mst_rm_exim_txt_search').value;
        let searchby = document.getElementById('mst_rm_exim_seachby').value;        
        Cookies.set('CKPSEARCH', search , {expires:365})
        Cookies.set('CKPSEARCH_BY', searchby , {expires:365})
        window.open("<?=base_url('master_hscode_as_xls')?>" ,'_blank')
    }

    function mst_rm_exim_btn_download_eCK(p)
    {
        p.disabled = true
        $.ajax({
            type: "GET",
            url: "<?=base_url('MSTITMHistory/file_tmp_hscode')?>",
            success: function (response) {
                const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                const fileName =`tmp_ITEM-HSCODE_master.xlsx`
                saveAs(blob, fileName)
                p.disabled = false
                alertify.success('Done')
            },
            xhr: function () {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            p.disabled = false
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            },
        })
    }

    function mst_rm_exim_btn_startimport_eCK(p)
    {
        if (mst_rm_exim_xlf_new.files.length==0){
            alert('please select file to upload');
        } else {	
            p.disabled = true
            const fileUpload = $("#mst_rm_exim_xlf_new")[0]; 
            //Validate whether File is valid Excel file.
            const regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(fileUpload.value.toLowerCase())) {                
                if (typeof (FileReader) != "undefined") {
                    const reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        console.log('saya perambaan selain IE');
                        reader.onload = function (e) {
                            mst_rm_exim_ProcessExcel(e.target.result);
                        };
                        reader.readAsBinaryString(fileUpload.files[0]);
                    } else {
                        //For IE Browser.
                        reader.onload = function (e) {
                            let data = "";
                            let bytes = new Uint8Array(e.target.result);
                            for (var i = 0; i < bytes.byteLength; i++) {
                                    data += String.fromCharCode(bytes[i]);
                            }
                            mst_rm_exim_ProcessExcel(data);
                        };
                        reader.readAsArrayBuffer(fileUpload.files[0]);
                    }
                } else {
                    alert("This browser does not support HTML5.");
                }
            } else {
                alert("Please upload a valid Excel file.");
            }
        }
    }

    function mst_rm_exim_ProcessExcel(data) {
        //Read the Excel File data.
        let workbook = XLSX.read(data, {
            type: 'binary'
        });
 
        //Fetch the name of First Sheet.
        let firstSheet = workbook.SheetNames[0];			
        //Read all rows from First Sheet into an JSON array.
        let excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
				        
        //Add the data rows from Excel file.
        let itm_ttlxls = excelRows.length
        let itm_ttlxls_savd = 0
        let functionList = []
        let dis = parseInt(((itm_ttlxls_savd)/itm_ttlxls)*100) + "%";
        mst_rm_exim_lblsaveprogress.style.width = dis
        mst_rm_exim_lblsaveprogress.innerText = dis
        for (let i = 0; i < excelRows.length; i++) {         
            functionList.push(
                $.ajax({
                    type: "post",
                    url: "<?=base_url('MSTITM/import_hscode')?>",
                    data: {itemcode: excelRows[i].ITEM_CODE, hscode: excelRows[i].HS_CODE},
                    dataType: "json",
                    success: function (response) {
                        itm_ttlxls_savd++;
                        dis = parseInt(((itm_ttlxls_savd)/itm_ttlxls)*100) + "%";
                        mst_rm_exim_lblsaveprogress.style.width = dis
                        mst_rm_exim_lblsaveprogress.innerText = dis
                    }, error: function(xhr,xopt,xthrow)
                    {
                        mst_rm_exim_btn_startimport.disabled = false
                        alertify.error(xthrow);
                    }
                })
            )                   
        } 
        $.when.apply($,functionList).then(function() {
            alertify.message('done')
            mst_rm_exim_btn_startimport.disabled = false
        })
	};

    $("#mst_rm_exim_IMPORTDATA").on('hidden.bs.modal', function(){
        mst_rm_exim_lblsaveprogress.style.width = "0%"
        mst_rm_exim_lblsaveprogress.innerText = "0%"
        mst_rm_exim_xlf_new.value = null
    });
</script>