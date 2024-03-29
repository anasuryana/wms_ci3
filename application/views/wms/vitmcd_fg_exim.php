<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="mst_fg_stack0">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="mst_fg_exim_btn_save" onclick="mst_fg_exim_btn_save_e_click()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-outline-success" type="button" id="mst_fg_exim_btn_exp" onclick="mst_fg_exim_btn_exp_eCK()">Export to XLS</button>
                    <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#importFGEXIMModal">Import</button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Search by</span>
                    <select class="form-select" id="mst_fg_exim_seachby">
                        <option value="itemcd">Item Code</option>
                        <option value="itemnm">Item Name</option>
                    </select>
                    <input type="text" class="form-control" id="mst_fg_exim_txt_search" title="press enter to start searching" onkeypress="mst_fg_exim_txt_search_e_search(event)">
                </div>
            </div>
        </div>
        <div class="row" id="mst_fg_stack1">
            <div class="col-md-12 mb-1">
                <span id="mst_fg_exim_lblinfo" class="badge bg-info">-</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="mst_fg_exim_divku">
                    <table id="mst_fg_exim_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle">Item Code</th>
                                <th  class="align-middle">Item Name</th>
                                <th  class="align-middle">HS Code</th>
                                <th  class="align-middle">Net Weight</th>
                                <th  class="align-middle">Empty Box Weight</th>
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
<!-- Modal -->
<div class="modal fade" id="importFGEXIMModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Import data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mst_fg_exim_ss_importContainer">
                        <div id="mst_fg_exim_ss_import"></div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnImportFGEXIM" onclick="btnImportFGEXIMOnClick()"><i class="fas fa-save"></i></button>
      </div>
    </div>
  </div>
</div>
<script>
    var mst_fg_exim_selected_index = 0;
    $("#mst_fg_exim_divku").css('height', $(window).height()
    -document.getElementById('mst_fg_stack0').offsetHeight
    -document.getElementById('mst_fg_stack1').offsetHeight
    -100);
    function mst_fg_exim_btn_save_e_click(){
        let tabell = document.getElementById("mst_fg_exim_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let itemcd = tableku2.rows[mst_fg_exim_selected_index].cells[0].innerText.trim()
        let itemhscd = tableku2.rows[mst_fg_exim_selected_index].cells[2].innerText.trim()
        let netweight = tableku2.rows[mst_fg_exim_selected_index].cells[3].innerText.trim()
        let grosweight = tableku2.rows[mst_fg_exim_selected_index].cells[4].innerText.trim()
        let beamasuk = tableku2.rows[mst_fg_exim_selected_index].cells[5].innerText.trim()
        let ppn = tableku2.rows[mst_fg_exim_selected_index].cells[6].innerText.trim()
        let pph = tableku2.rows[mst_fg_exim_selected_index].cells[7].innerText.trim()
        if(confirm("Are you sure ?")) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('MSTITM/fg_exim')?>",
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
    function mst_fg_exim_txt_search_e_search(e){
        if(e.which==13){
            let search = document.getElementById('mst_fg_exim_txt_search').value;
            let searchby = document.getElementById('mst_fg_exim_seachby').value;
            document.getElementById('mst_fg_exim_lblinfo').innerText = "Please wait...";
            $.ajax({
                type: "get",
                url: "<?=base_url('MSTITM/searchfg_exim')?>",
                data: {insearch: search, insearchby: searchby},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    document.getElementById('mst_fg_exim_lblinfo').innerText = ttlrows + " row(s) found";
                    let mydes = document.getElementById("mst_fg_exim_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("mst_fg_exim_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("mst_fg_exim_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newrow.onclick =  function(){mst_fg_exim_selected_index = i};
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
                        newText = document.createTextNode(response.data[i].MITM_BOXWEIGHT);
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

    function mst_fg_exim_btn_exp_eCK(){
        const search = document.getElementById('mst_fg_exim_txt_search').value
        const searchby = document.getElementById('mst_fg_exim_seachby').value
        Cookies.set('CKPSEARCH', search , {expires:365})
        Cookies.set('CKPSEARCH_BY', searchby , {expires:365})
        window.open("<?=base_url('master_hscode_fg_as_xls')?>" ,'_blank')
    }

    var itmdata = [
       [''],
       [''],
       [''],
    ];
    var mst_fg_exim_ss_category = jspreadsheet(document.getElementById('mst_fg_exim_ss_import'), {
        data:itmdata,
        columns: [
            {
                type: 'text',
                title:'Item Code',
                width:200,
            },
            {
                type: 'text',
                title:'HS Code',
                width:90,
            },
            {
                type: 'numeric',
                title:'Net Weight',
                width:90,
                mask:'#,##0.00',
            },
            {
                type: 'numeric',
                title:'Empty Box Weight',
                width:120,
                mask:'#,##0.00',
            },
            {
                type: 'numeric',
                title:'BM',
                width:80,
                mask:'#,##0.00',
            },
            {
                type: 'numeric',
                title:'PPN',
                width:80,
                mask:'#,##0.00',
            },
            {
                type: 'numeric',
                title:'PPH',
                width:80,
                mask:'#,##0.00',
            },
            {
                type: 'text',
                title:'Status',
                width:80,
                mask:'#,##0.00',
                readOnly : true
            }
        ]
    });

    function btnImportFGEXIMOnClick(){
        let rawData = mst_fg_exim_ss_category.getData()
        let dataList = rawData.filter((data) => data[0].length > 1)

        if(confirm(`Are you sure ?`)){
            btnImportFGEXIM.disabled = true
            btnImportFGEXIM.innerHTML = `<i class="fas fa-save fa-spin"></i>`
            let ItemCode = []
            let HSCode = []
            let NetWeight = []
            let BoxWeight = []
            let BM = []
            let PPN = []
            let PPH = []
            dataList.forEach((arrayItem) => {
                ItemCode.push(arrayItem[0].replace(/\s+/g, ' ').trim())
                HSCode.push(arrayItem[1].replace(/\s+/g, ' ').trim())
                NetWeight.push(arrayItem[2])
                BoxWeight.push(arrayItem[3])
                BM.push(arrayItem[4])
                PPN.push(arrayItem[5])
                PPH.push(arrayItem[6])
            })
            $.ajax({
                type: "POST",
                url: "<?=base_url('MSTITM/updateFGExim')?>",
                data: {ItemCode : ItemCode
                    ,HSCode : HSCode
                    ,NetWeight : NetWeight
                    ,BoxWeight : BoxWeight
                    ,BM : BM
                    ,PPN : PPN
                    ,PPH : PPH
                    },
                dataType: "json",
                success: function (response) {
                    btnImportFGEXIM.disabled = false
                    btnImportFGEXIM.innerHTML = `<i class="fas fa-save"></i>`
                    alertify.message(response.message)
                    if(response.data.length>0){
                        response.data.forEach((r) => {
                            let i = 1;
                            for(let rs in rawData){
                                if(r['ItemCode'] == rawData[rs][0]){
                                    mst_fg_exim_ss_category.setStyle(`A${i}`, 'background-color', '#91ffb4')
                                    mst_fg_exim_ss_category.setValue(`H${i}`, r['message'],true)
                                    break;
                                }
                                i++
                            }
                        })
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    btnImportFGEXIM.disabled = false
                    btnImportFGEXIM.innerHTML = `<i class="fas fa-save"></i>`
                }
            });
        }
    }
</script>