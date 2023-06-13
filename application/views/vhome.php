 <style>
#home_div_document_list li:hover{
    background-color: #dee2e6;
}
.anastylesel_home{
        background: red;
        animation: anamoveHome 1s infinite;
    }
    @keyframes anamoveHome {
        from {background: #fc033d;}
        to {background: #11faea;}
    }
 </style>
 <div  data-options="region:'center',title:''" style="background: #D3CCE3;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #E9E4F0, #D3CCE3);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #E9E4F0, #D3CCE3); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    ">


    <div id="tt" class="easyui-tabs" style="width:100%" data-options="tools:'#tab-tools',fit:true,border:false,plain:true">
    </div>

    <div id="tab-tools">
    <a href="#" title="Tasks" class="easyui-linkbutton" onclick="home_btn_task_eCK()" id="linkNotif"><span class="fas fa-tasks"></span> <i class="badge bg-info" id="homeQTNotif"></i></a>
    <a href="#" title="Change your password" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-change_pw'" onclick="dlgChangePW();"></a>
    <a href="#" title="Exit" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-exit'" onclick="dlgExit()"></a>
      </div>
</div>
<div class="modal fade" id="HOME_MODCHGPW">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Password Changes</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <ul class="nav nav-tabs" id="TabPW" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#TabChildPWWEB" type="button" role="tab" aria-controls="home" aria-selected="true">Web App</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#TabChildPWHT" type="button" role="tab" aria-controls="profile" aria-selected="false"><span class="fas fa-mobile-retro"></span> Handy Terminal App</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="TabChildPWWEB" role="tabpanel">
                            <div class="container-fluid">
                                <div class="row mt-3">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >Old Password</span>
                                            <input type="password" class="form-control" id="txtoldpw" required>
                                        </div>
                                    </div>
                                    <div class="col mb-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >New Password</span>
                                            <input type="password" class="form-control" id="txtnewpw" required onkeyup="txtnewpw_eKeyUp(event)">
                                        </div>
                                    </div>
                                    <div class="col mb-1" id="newpwweb_div">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >Confirm Password</span>
                                            <input type="password" class="form-control" id="txtconfirmpw" required>
                                        </div>
                                    </div>
                                    <div class="col mb-1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-sm btn-primary" id="btncommit_changepw" onclick="btncommit_changepw_e_click()"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="TabChildPWHT" role="tabpanel">
                            <div class="container-fluid">
                                <div class="row mt-3">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >New Password</span>
                                            <input type="password" class="form-control" id="txtnewpw_ht" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-1">
                                        <div class="input-group input-group-sm mb-1">
                                            <span class="input-group-text" >Confirm Password</span>
                                            <input type="password" class="form-control" id="txtconfirmpw_ht" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-sm btn-primary" id="btncommit_changepw_ht" onclick="btncommit_changepw_ht_e_click()"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">

        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="HOME_MODTRF">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Receiving Confirmation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card text-center">
                                <div class="card-header">
                                    Need to confirm
                                </div>
                                <div class="card-body">
                                    <h1 class="card-title text-warning" style="cursor:pointer" id="home_h1_to_approve_qty" onclick="home_h1_to_approve_qty_eClick()">0</h1>
                                </div>
                                <div class="card-footer text-muted">
                                    <span id="home_h1_to_approve_time"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-1">
                            <div class="card text-center">
                                <div class="card-header">
                                    Need to follow up
                                </div>
                                <div class="card-body">
                                    <h1 class="card-title text-primary" style="cursor:pointer" id="home_h1_to_follow_qty" onclick="home_h1_to_follow_qty_eClick()">0</h1>
                                </div>
                                <div class="card-footer text-muted">
                                    <span id="home_h1_to_follow_time"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="bg-info border-2 border-top border-secondary">
                    <div class="row">
                        <div class="col" >
                            <ol class="list-group list-group-numbered" id="home_div_document_list">
                            </ol>
                        </div>
                        <div class="col">
                            <div class="table-responsive" id="home_div_of_tbl_doc">
                                <table id="home_tbl_doc" class="table table-hover table-sm table-bordered caption-top">
                                    <caption>Detail <span class="fas fa-arrow-turn-down"></span></caption>
                                    <thead class="table-light">
                                        <tr>
                                            <th class="align-middle">Item Code</th>
                                            <th class="align-middle">Item Name</th>
                                            <th class="text-end align-middle">Qty</th>
                                            <th class="text-center"><input id="home_ck_all" class="form-check-input" type="checkbox"></th>
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
</div>
<script>
    var uidnya = '<?php echo $sapaDiaID; ?>';
    var home_selected_id = ''
    function dlgExit(){
        if(confirm("Are You sure want to exit ?")){
            window.open("<?=base_url("pages/logout")?>","_self");
        }
    }
    function dlgChangePW(){
        $("#HOME_MODCHGPW").modal('show')
    }
    function home_btn_task_eCK() {
        home_get_open_documents()
        home_tbl_doc.getElementsByTagName('tbody')[0].innerHTML = ''
        home_div_document_list.innerHTML = ''
        $("#HOME_MODTRF").modal('show');
    }

    function home_h1_to_approve_qty_eClick(){
        home_div_document_list.innerHTML = ''
        home_tbl_doc.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="4" class="text-center">-</td></tr>'
        $.ajax({
            type: "GET",
            url: "<?=base_url('TRFHistory/openDocuments')?>",
            dataType: "JSON",
            success: function (response) {
                home_h1_to_approve_qty.innerText = response.data.TO_APPROVE_QTY
                home_h1_to_approve_time.innerText = response.data.TO_APPROVE_DATETIME==='-' ? '-' : moment(response.data.TO_APPROVE_DATETIME).startOf('hour').fromNow()
                home_h1_to_approve_time.title = response.data.TO_APPROVE_DATETIME
                homeSetNotifQT(response.data.TO_APPROVE_QTY+response.data.TO_FOLLOW_QTY)
                let rowNum = 1
                response.rs.forEach((arrayItem) => {
                    let _EleLi = document.createElement('li')
                    _EleLi.classList.add('list-group-item','d-flex', 'justify-content-between', 'align-items-start')
                    _EleLi.style.cssText = 'cursor:pointer'
                    _EleLi.onclick = function(event)  {
                        home_get_document_detail({doc: arrayItem['TRFH_DOC']})
                        let x = home_div_document_list.getElementsByTagName('li')
                        for (let i = 0; i < x.length; i++) {
                            x[i].classList.remove('active')
                        }
                        this.classList.add('active')
                    }
                    let _EleDiv = document.createElement('div')
                    _EleDiv.classList.add('ms-2','me-auto')
                    let _EleDivBold = document.createElement('div')
                    _EleDivBold.classList.add('fw-bold')
                    _EleDivBold.innerText = arrayItem['TRFH_DOC']
                    let _EleDivNonBold = document.createElement('span')
                    _EleDivNonBold.innerHTML = `from ${arrayItem['MSTLOCG_NM']} <br> to ${arrayItem['MSTLOCG_NMTO']}`
                    _EleDiv.append(_EleDivBold)
                    _EleDiv.append(_EleDivNonBold)
                    _EleLi.append(_EleDiv)
                    let _EleSpanBadge = document.createElement('span')
                    _EleSpanBadge.classList.add('badge', 'bg-info', 'rounded-pill')
                    _EleSpanBadge.innerText = arrayItem['TTLITEM']
                    _EleLi.append(_EleSpanBadge)
                    home_div_document_list.append(_EleLi)
                })
            }, error:function(xhr,ajaxOptions, throwError) {
                alertify.error('Please try again');
            }
        })
    }
    function home_h1_to_follow_qty_eClick(){
        home_div_document_list.innerHTML = ''
        home_tbl_doc.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="4" class="text-center">-</td></tr>'
        $.ajax({
            type: "GET",
            url: "<?=base_url('TRFHistory/openDocuments')?>",
            dataType: "JSON",
            success: function (response) {
                home_h1_to_follow_qty.innerText = response.data.TO_FOLLOW_QTY
                home_h1_to_follow_time.innerText = response.data.TO_FOLLOW_DATETIME==='-' ? '-' : moment(response.data.TO_FOLLOW_DATETIME).startOf('hour').fromNow()
                home_h1_to_follow_time.title = response.data.TO_FOLLOW_DATETIME
                homeSetNotifQT(response.data.TO_APPROVE_QTY+response.data.TO_FOLLOW_QTY)
                let rowNum = 1
                response.rs2.forEach((arrayItem) => {
                    let _EleLi = document.createElement('li')
                    _EleLi.classList.add('list-group-item','d-flex', 'justify-content-between', 'align-items-start')
                    _EleLi.style.cssText = 'cursor:pointer'
                    _EleLi.onclick = function(event)  {
                        home_get_document_detail_tofollow({doc: arrayItem['TRFH_DOC']})
                        let x = home_div_document_list.getElementsByTagName('li')
                        for (let i = 0; i < x.length; i++) {
                            x[i].classList.remove('active')
                        }
                        this.classList.add('active')
                    }
                    let _EleDiv = document.createElement('div')
                    _EleDiv.classList.add('ms-2','me-auto')
                    let _EleDivBold = document.createElement('div')
                    _EleDivBold.classList.add('fw-bold')
                    _EleDivBold.innerText = arrayItem['TRFH_DOC']
                    let _EleDivNonBold = document.createElement('span')
                    _EleDivNonBold.innerText = `from ${arrayItem['MSTLOCG_NMFROM']} to ${arrayItem['MSTLOCG_NM']}`
                    _EleDiv.append(_EleDivBold)
                    _EleDiv.append(_EleDivNonBold)
                    _EleLi.append(_EleDiv)
                    let _EleSpanBadge = document.createElement('span')
                    _EleSpanBadge.classList.add('badge', 'bg-info', 'rounded-pill')
                    _EleSpanBadge.innerText = arrayItem['TTLITEM']
                    _EleLi.append(_EleSpanBadge)
                    home_div_document_list.append(_EleLi)
                })
            }, error:function(xhr,ajaxOptions, throwError) {
                alertify.error('Please try again');
            }
        })
    }

    function home_get_document_detail(pdata){
        home_tbl_doc.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="4" class="text-center">Please wait</td></tr>'
        $.ajax({
            type: "GET",
            url: "<?=base_url('TRFHistory/getDetailUnconform')?>",
            data: {doc: pdata.doc},
            dataType: "json",
            success: function (response) {
                let mydes = document.getElementById("home_div_of_tbl_doc");
                let myfrag = document.createDocumentFragment();
                let cln = home_tbl_doc.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("home_tbl_doc");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let myCB = myfrag.getElementById('home_ck_all')
                myCB.onclick = () => {
                    if(confirm("Are you sure want to conform all items?")){
                        let ttlrows = tableku2.rows.length
                        for (let i = 0; i<ttlrows; i++)
                        {
                            tableku2.rows[i].cells[2].getElementsByTagName('input')[0].checked = myCB.checked
                        }
                    } else {
                        myCB.checked = false
                    }
                }
                myCB.checked = false
                tableku2.innerHTML = '';
                response.data.forEach((arrayItem) => {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = arrayItem['TRFD_ITEMCD']
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = arrayItem['MITM_ITMD1'].trim()
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(arrayItem['TRFD_QTY']).format(',')
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-center')
                    let _EleInput = document.createElement('input')
                    _EleInput.type = 'checkbox'
                    _EleInput.classList.add('form-check-input')
                    _EleInput.onclick = (event) => {
                        if(event.target.checked){
                            home_conform({doc: arrayItem['TRFD_DOC'], item: arrayItem['TRFD_ITEMCD']})
                        }
                    }
                    newcell.append(_EleInput)
                })
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            }, error:function(xhr,ajaxOptions, throwError) {
                alertify.error('Please try again');
                home_tbl_doc.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="4" class="text-center">Please try again</td></tr>'
            }
        });
    }
    function home_get_document_detail_tofollow(pdata){
        home_tbl_doc.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="4" class="text-center">Please wait</td></tr>'
        $.ajax({
            type: "GET",
            url: "<?=base_url('TRFHistory/getDetailUnconform')?>",
            data: {doc: pdata.doc},
            dataType: "json",
            success: function (response) {
                let mydes = document.getElementById("home_div_of_tbl_doc");
                let myfrag = document.createDocumentFragment();
                let cln = home_tbl_doc.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("home_tbl_doc");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let myCB = myfrag.getElementById('home_ck_all')
                myCB.onclick = () => {
                    if(confirm("Are you sure want to conform all items?")){
                        let ttlrows = tableku2.rows.length
                        for (let i = 0; i<ttlrows; i++)
                        {
                            tableku2.rows[i].cells[2].getElementsByTagName('input')[0].checked = myCB.checked
                        }
                    } else {
                        myCB.checked = false
                    }
                }
                myCB.checked = false
                myCB.disabled = true
                tableku2.innerHTML = '';
                response.data.forEach((arrayItem) => {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = arrayItem['TRFD_ITEMCD']
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = arrayItem['MITM_ITMD1']
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(arrayItem['TRFD_QTY']).format(',')
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-center')
                    let _EleInput = document.createElement('input')
                    _EleInput.type = 'checkbox'
                    _EleInput.disabled = true
                    _EleInput.classList.add('form-check-input')
                    newcell.append(_EleInput)
                })
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            }, error:function(xhr,ajaxOptions, throwError) {
                alertify.error('Please try again');
                home_tbl_doc.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="4" class="text-center">Please try again</td></tr>'
            }
        });
    }

    function home_conform(pdata){
        $.ajax({
            type: "POST",
            url: "<?=base_url('TRF/conformTransfer')?>",
            data: {doc: pdata.doc, item: pdata.item},
            dataType: "json",
            success: function (response) {
                if(response.data.length===0){
                    home_h1_to_approve_qty_eClick()
                } else {
                    home_get_document_detail({doc: pdata.doc})
                }
                alertify.message(response.status[0].msg)
            }, error:function(xhr,ajaxOptions, throwError) {
                alertify.error('Please try again');
            }
        });
    }

    home_get_open_documents()

    function homeSetNotifQT(total){
        if(total>0){
            homeQTNotif.innerText = total
            linkNotif.classList.add('anastylesel_home')
        } else {
            homeQTNotif.innerText = ''
            linkNotif.classList.remove('anastylesel_home')
        }
    }

    function home_get_open_documents(){
        $.ajax({
            type: "GET",
            url: "<?=base_url('TRFHistory/openDocuments')?>",
            dataType: "JSON",
            success: function (response) {
                home_h1_to_approve_qty.innerText = response.data.TO_APPROVE_QTY
                home_h1_to_approve_time.innerText = response.data.TO_APPROVE_DATETIME==='-' ? '-' : moment(response.data.TO_APPROVE_DATETIME).startOf('hour').fromNow()
                home_h1_to_approve_time.title = response.data.TO_APPROVE_DATETIME

                home_h1_to_follow_qty.innerText = response.data.TO_FOLLOW_QTY
                home_h1_to_follow_time.innerText = response.data.TO_FOLLOW_DATETIME==='-' ? '-' : moment(response.data.TO_FOLLOW_DATETIME).startOf('hour').fromNow()
                home_h1_to_follow_time.title = response.data.TO_FOLLOW_DATETIME
                homeSetNotifQT(response.data.TO_APPROVE_QTY+response.data.TO_FOLLOW_QTY)
            }, error:function(xhr,ajaxOptions, throwError) {
                alertify.error('Please try again');
            }
        })
    }

    function smtPWValidator(pvalue) {
        let numberList = [...Array(10).keys()]
        let specialCharList = ['~','!','@','#','$','%','^','&','*','(',')','_','+',':','"','<','>','?','{','}','|']
        if(pvalue.trim().length<8){
            return {cd: '0', msg : 'At least 8 characters'}
        }
        let isFound = false
        for(let i=0; i<numberList.length; i++) {
            if(pvalue.includes(numberList[i])) {
                isFound = true
                break
            }
        }
        if(!isFound) {
            return {cd: '0', msg : 'At least 1 numerical character'}
        }
        isFound = false
        for(let i=0; i<specialCharList.length; i++) {
            if(pvalue.includes(specialCharList[i])) {
                isFound = true
                break
            }
        }
        if(!isFound) {
            return {cd: '0', msg : 'At least 1 special character'}
        }
        return  {cd: '1', msg : 'OK'}
    }

    function txtnewpw_eKeyUp(e){
        let statusPW = smtPWValidator(e.target.value)
        if(statusPW.cd==='1') {
            newpwweb_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
        } else {
            newpwweb_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
        }
     }

    function btncommit_changepw_e_click(){
        let a = getOldPW()
        if (a=='1'){
            const txtNewPW = document.getElementById('txtnewpw')
            const txtConfirmPW = document.getElementById('txtconfirmpw')
            if(txtNewPW.value === txtConfirmPW.value){
                const statusPW = smtPWValidator(txtNewPW.value)
                if(statusPW.cd === '1') {
                    newpwweb_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
                } else {
                    newpwweb_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
                    statusPW.focus()
                    return
                }
                if(confirm("Are you sure ?")){
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('user/setnewpw')?>",
                        dataType: "text",
                        data: {inUID : uidnya,inPw: txtNewPW.value,apptype : 'webapp' },
                        async: false,
                        success:function(response) {
                            alert(response);
                        },
                        error:function(xhr,ajaxOptions, throwError) {
                            alert(throwError);
                        }
                    })
                }
            } else {
                txtConfirmPW.focus()
                alert('Password does not match with the confirmation');
            }
        } else {
            alertify.message("Invalid old password");
            $('#txtoldpw').focus();
        }
    }

    function btncommit_changepw_ht_e_click() {
        const txtNewPW = document.getElementById('txtnewpw_ht')
        const txtConfirmPW = document.getElementById('txtconfirmpw_ht')
        if(txtNewPW.value === txtConfirmPW.value){
            if(txtNewPW.value.trim().length<7) {
                alertify.warning(`at least 7 characters`)
                return
            }
            if(confirm("Are you sure ?")){
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('user/setnewpw')?>",
                    dataType: "text",
                    data: {inUID : uidnya,inPw: txtNewPW.value, apptype: 'htapp' },
                    async: false,
                    success:function(response) {
                        alert(response);
                    },
                    error:function(xhr,ajaxOptions, throwError) {
                        alert(throwError);
                    }
                })
            }
        } else {
            txtConfirmPW.focus()
            alert('Password does not match with the confirmation');
        }
    }

    function getOldPW(){
        let pw = $('#txtoldpw').val()
        let hasil =  $.ajax({
            type: "get",
            url: "<?=base_url('user/getpw_sts')?>",
            dataType: "text",
            data: {inUID : uidnya,inPw: pw },
            async: false,
            success:function(response) {
            },
            error:function(xhr,ajaxOptions, throwError) {
            alert(throwError);
            }
            })
        return hasil.responseText;
    }
</script>
