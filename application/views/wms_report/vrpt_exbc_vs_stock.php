<style type="text/css">

    thead tr.first th,
    thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th,
    thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row" id="xstock_stack0">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Item Code</label>
                    <input type="text" class="form-control" id="xstock_txt_search">
                    <button class="btn btn-outline-primary" id="xstock_btn_search" onclick="xstock_btn_doc_eClick(this)"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
            </div>
        </div>
        <div class="row" id="xstock_stack1">
            <div class="col-md-6 mb-1">
                <table id="xstock_resume_tbl" class="table table-striped table-bordered table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th colspan="3" class="align-middle text-center">Qty</th>
                        </tr>
                        <tr>
                            <th  class="align-middle text-center">Part</th>
                            <th  class="align-middle text-center">EXBC</th>
                            <th  class="align-middle text-center">Difference</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><span id="xstock_resume_part_qty">-</span></td>
                            <td class="text-center"><span id="xstock_resume_exbc_qty">-</span></td>
                            <td class="text-center"><span id="xstock_resume_diff_qty">-</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 mb-1 text-end position-relative">
                <div class="position-absolute bottom-0 end-0">
                    <button title="Copy to clipboard" id="xstock_resume_btnexcel" onclick="xstock_resume_btnexcel()" class="btn btn-success mt-auto" ><i class="fas fa-clipboard"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="xstock_divku">
                    <table id="xstock_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th class="align-middle" rowspan="2">Item Code</th>
                                <th class="align-middle" rowspan="2">Item Name</th>
                                <th class="align-middle" rowspan="2">Description</th>
                                <th class="align-middle text-center">Location</th>
                            </tr>
                            <tr class="second">
                                <th class="text-center">...</th>
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
<script>
    function xstock_resume_btnexcel(){
        cmpr_selectElementContents(document.getElementById('xstock_tbl'))
        document.execCommand("copy");
        alertify.message("Copied");
    }
    $("#xstock_divku").css('height', $(window).height()
    -document.getElementById('xstock_stack0').offsetHeight
    -document.getElementById('xstock_stack1').offsetHeight
    -100);
    function xstock_btn_doc_eClick(e)
    {
        xstock_txt_search.readOnly = true
        e.disabled = true
        e.innerHTML = `<i class="fas fa-search fa-spin"></i>`
        let mtabel = document.getElementById("xstock_tbl");
        mtabel.getElementsByTagName('thead')[0].innerHTML = `<tr class="first">
                                <th class="align-middle" rowspan="2">Item Code</th>
                                <th class="align-middle" rowspan="2">Item Name</th>
                                <th class="align-middle" rowspan="2">Description</th>
                                <th class="align-middle text-center">Location</th>
                            </tr>
                            <tr class="second">
                            <th class="text-center">...</th>
                            </tr>`
                            xstock_resume_part_qty.innerHTML = '-'
                            xstock_resume_exbc_qty.innerHTML = '-'
                            xstock_resume_diff_qty.innerHTML = '-'
        if(xstock_txt_search.value.trim().length <=3 ){
            mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center"></td></tr>`
            $.ajax({
                type: "GET",
                url: "<?=base_url('ITHHistory/compareStockVSExbc')?>",
                data: { item_code : xstock_txt_search.value, outputType:'spreadsheet' },
                success: function (response) {
                    let waktuSekarang = moment().format('YYYY MMM DD, h_mm')
                    const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                    const fileName = `exbc vs stock ${waktuSekarang}.xlsx`
                    saveAs(blob, fileName)
                    alertify.success('Done')
                    xstock_txt_search.readOnly = false
                    e.innerHTML = `<i class="fas fa-search"></i>`
                    e.disabled = false
                },
                xhr: function () {
                    const xhr = new XMLHttpRequest()
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 2) {
                            if (xhr.status == 200) {
                                xhr.responseType = "blob";
                            } else {
                                xstock_txt_search.readOnly = false
                                e.innerHTML = `<i class="fas fa-search"></i>`
                                e.disabled = false
                                xhr.responseType = "text";
                            }
                        }
                    }
                    return xhr
                },
            })
        } else {
            mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center">Please wait</td></tr>`
            $.ajax({
                url: "<?=base_url('ITHHistory/compareStockVSExbc')?>",
                data: {item_code : xstock_txt_search.value, outputType:'json'},
                dataType: "json",
                success: function (response) {
                    xstock_txt_search.readOnly = false
                    e.innerHTML = `<i class="fas fa-search"></i>`
                    e.disabled = false
                    let mydes = document.getElementById("xstock_divku");
                    let myfrag = document.createDocumentFragment();
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("xstock_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let resume_part_qty,resume_exbc_qty,resume_diff_qty
                    resume_part_qty = 0
                    resume_exbc_qty = 0
                    resume_diff_qty = 0
                    tableku2.innerHTML='';
                    let columnReport = '';
                    let totalColumn = response.data_location.length
                    for(let i=0; i<totalColumn ; i++)
                    {
                        columnReport += `<th class="text-center">${response.data_location[i]}</th>`
                    }
                    tabell.getElementsByTagName('thead')[0].innerHTML = `<tr class="first">
                                    <th class="align-middle" rowspan="2">Item Code</th>
                                    <th class="align-middle" rowspan="2">Item Name</th>
                                    <th class="align-middle" rowspan="2">Description</th>
                                    <th class="align-middle text-center" colspan="${totalColumn}">Location</th>
                                    <th class="align-middle text-center" rowspan="2">Balance</th>
                                </tr>
                                <tr class="second">
                                    ${columnReport}
                                </tr>`
                    for(let x of response.rsFix)
                    {
                        let ExbcQty = 0
                        let NonExbcQty = 0
                        let BalanceQty = 0
                        newrow = tableku2.insertRow(-1);
                        for(const [index, [key , value]] of Object.entries(Object.entries(x)) )
                        {
                            newcell = newrow.insertCell(index)
                            if(['ITRN_ITMCD','SPTNO', 'D1'].includes(key)){
                                newcell.innerHTML = value
                            } else {
                                newcell.innerHTML = numeral(value < 0 ? 0 : value).format(',')
                                newcell.classList.add('text-end')
                                if(key === 'EXBC'){
                                    ExbcQty = numeral(value).value()
                                    resume_exbc_qty += value
                                } else {
                                    NonExbcQty += numeral(value < 0 ? 0 : value).value()
                                    resume_part_qty += numeral(value < 0 ? 0 : value).value()
                                }
                            }
                        }
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        BalanceQty = ExbcQty-NonExbcQty
                        newcell.innerHTML = numeral(BalanceQty).format(',')
                        if(BalanceQty<0){
                            newcell.classList.add('bg-danger')
                        } else {
                            if(BalanceQty>0){
                                newcell.classList.add('bg-warning')
                            }
                        }
                    }
                    resume_diff_qty = resume_exbc_qty - resume_part_qty
                    xstock_resume_part_qty.innerHTML = numeral(resume_part_qty).format(',')
                    xstock_resume_exbc_qty.innerHTML = numeral(resume_exbc_qty).format(',')
                    xstock_resume_diff_qty.innerHTML = numeral(resume_diff_qty).format(',')
                    if(resume_diff_qty===0)
                    {
                        xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.remove('bg-warning')
                        xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.remove('bg-danger')
                        xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.add('bg-success')
                    } else {
                        if(resume_diff_qty<0)
                        {
                            xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.remove('bg-warning')
                            xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.remove('bg-success')
                            xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.add('bg-danger')
                        } else {
                            xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.add('bg-warning')
                            xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.remove('bg-success')
                            xstock_resume_tbl.getElementsByTagName('tbody')[0].rows[0].cells[2].classList.remove('bg-danger')
                        }
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    e.innerHTML = `<i class="fas fa-search"></i>`
                    e.disabled = false
                    mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">${xthrow}, please try again if still happen try contact administrator</td></tr>`
                    xstock_resume_part_qty.innerHTML = '-'
                    xstock_resume_exbc_qty.innerHTML = '-'
                    xstock_resume_diff_qty.innerHTML = '-'
                    xstock_txt_search.readOnly = false
                }
            });
        }
    }
</script>