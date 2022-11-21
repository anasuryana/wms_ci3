<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="input-group mb-2">
                    <span class="input-group-text">Userid</span>
                    <input type="text" class="form-control" id="txtuserid_reg" required maxlength="9">
                    <button id="user_btn_find" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#USER_MODUSR"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="input-group mb-2">
                    <span class="input-group-text" onclick="txtuserpw_reg.focus()">First Name</span>
                    <input type="text" class="form-control" id="txtuserfirstname_reg" required maxlength="40">
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="input-group mb-2">
                    <span class="input-group-text">Last Name</span>
                    <input type="text" class="form-control" id="txtuserlastname_reg" placeholder="" value="" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="firstName" class="form-label">Password</label>
                <input type="password" class="form-control" id="txtuserpw_reg" required onkeyup="txtuserpw_reg_eKeyUp(event)">
            </div>
            <div class="col-md-6 mb-3" id="txtuserpw_div">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="firstName" class="form-label">Confirm password</label>
                <input type="password" class="form-control" id="txtuserpwconf_reg" required maxlength="40">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="firstName"><span class="fas fa-mobile-retro"></span> Handy Terminal Password</label>
                <input type="password" class="form-control" id="txtuserpw_reg_ht" required maxlength="8">
            </div>
            <div class="col-md-6 mb-3">
                <label for="firstName"><span class="fas fa-mobile-retro"></span> Confirm Handy Terminal Password</label>
                <input type="password" class="form-control" id="txtuserpwconf_reg_ht" required maxlength="8">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="input-group mb-2">
                    <span class="input-group-text">Group</span>
                    <select class="form-select" id="cmbGroup_reg" required>
                        <?php foreach ($lusergroup as $r) { ?>
                            <option value="<?php echo $r['MSTGRP_ID']; ?>"> <?php echo $r['MSTGRP_NM']; ?> </option>
                        <?php } ?>
                    </select>
                    <button id="btnsync_reg" onclick="btnsync_reg_eClick()" class="btn btn-lg btn-primary btn-sm"><i class="fas fa-sync"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <button id="btnSaveUser_reg" class="btn btn-lg btn-primary btn-sm" type="submit"><i class="fas fa-save"></i> Save</button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="USER_MODUSR">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Unregistered List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <select id="usr_srchby" class="form-select" onchange="usr_txtsearch.focus()">
                                <option value="id">User Id</option>
                                <option value="nm">User Name</option>
                            </select>
                            <input type="text" class="form-control" id="usr_txtsearch" maxlength="45" onkeypress="usr_txtsearch_eKP(event)" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="usr_tbl_div">
                            <table id="usr_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th>User Id</th>
                                        <th>User Name</th>
                                        <th>Department</th>
                                        <th class="text-center">Date of Join</th>
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
    $("#USER_MODUSR").on('shown.bs.modal', function() {
        usr_txtsearch.focus()
    });
    $("#USER_MODUSR").on('hidden.bs.modal', function() {        
        txtuserpw_reg.focus()
    });

    function btnsync_reg_eClick() {
        $.ajax({
            url: "<?= base_url('User/userGroup') ?>",
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length
                let strData = ``
                for (let i = 0; i < ttlrows; i++) {
                    strData += `<option value="${response.data[i].MSTGRP_ID}">${response.data[i].MSTGRP_NM}</option>`
                }
                cmbGroup_reg.innerHTML = strData
                cmbGroup_reg.focus()
            },
            error: function(xhr, ajaxOptions, throwError) {
                alert(throwError);
            }
        })
    }

    function usr_txtsearch_eKP(e) {
        if (e.key === 'Enter') {
            usr_tbl.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Please wait</td></tr>`
            $.ajax({
                url: "<?= base_url('User/search_unregistered') ?>",
                data: {
                    searchby: usr_srchby.value,
                    search: usr_txtsearch.value
                },
                dataType: "json",
                success: function(response) {
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("usr_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("usr_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("usr_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell;
                    tableku2.innerHTML = '';
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1);
                        newrow.onclick = () => {
                            txtuserid_reg.value = response.data[i].ID
                            const nice_name = response.data[i].user_nicename.trim().split(' ')                            
                            txtuserfirstname_reg.value = nice_name[0]
                            nice_name.shift()
                            txtuserlastname_reg.value = nice_name.join(" ")
                            
                            $("#USER_MODUSR").modal('hide')
                        }
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = i + 1
                        newcell = newrow.insertCell(1);
                        newcell.innerHTML = response.data[i].ID
                        newcell = newrow.insertCell(2);
                        newcell.innerHTML = response.data[i].user_nicename
                        newcell = newrow.insertCell(3);
                        newcell.innerHTML = response.data[i].user_dept
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].user_doj
                    }
                    mydes.innerHTML = '';
                    mydes.appendChild(myfrag);
                },
                error: function(xhr, ajaxOptions, throwError) {
                    alert(throwError);
                }
            });
        }
    }

    function txtuserpw_reg_eKeyUp(e) {
        let statusPW = smtPWValidator(e.target.value)
        if (statusPW.cd === '1') {
            txtuserpw_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
        } else {
            txtuserpw_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
        }
    }
    $('#btnSaveUser_reg').click(function() {
        const quserid = $("#txtuserid_reg").val();
        const qfirstname = $("#txtuserfirstname_reg").val();
        const qlastname = $("#txtuserlastname_reg").val();
        const qpw1 = $("#txtuserpw_reg").val();
        const qpw2 = $("#txtuserpwconf_reg").val();
        const grp = $("#cmbGroup_reg").val();
        const htpw = txtuserpw_reg_ht.value
        const htpw_c = txtuserpwconf_reg_ht.value
        if (quserid == '') {
            $("#txtuserid_reg").focus();
            return;
        }
        if (qpw1 == '') {
            alert('password should not be empty');
            $("#txtuserpw_reg").focus();
            return;
        }
        if (qpw2 == '') {
            alert('password should not be empty');
            $("#txtuserpwconf_reg").focus();
            return;
        }


        if (qpw1 != qpw2) {
            alert('please confirm password')
            txtuserpwconf_reg.focus()
            return;
        }
        if (htpw == '' || htpw_c == '') {
            alert('password should not be empty');
            txtuserpw_reg_ht.focus();
            return;
        }
        if (htpw != htpw_c) {
            alert('please confirm password')
            txtuserpwconf_reg_ht.focus()
            return;
        }

        //validate WEBApp password
        let statusPW = smtPWValidator(qpw1)
        if (statusPW.cd === '1') {
            txtuserpw_div.innerHTML = `<span class="badge bg-success">${statusPW.msg}</span>`
        } else {
            txtuserpw_div.innerHTML = `<span class="badge bg-warning">${statusPW.msg}</span>`
            txtuserpw_reg.focus()
            return
        }

        //validate HTApp password
        if (htpw.length < 7) {
            alertify.warning(`at least 7 characters`)
            txtuserpw_reg_ht.focus()
            return
        }
        if (confirm(`Are you sure ?`)) {
            jQuery.ajax({
                type: "POST",
                url: "<?= base_url("User/register") ?>",
                dataType: "text",
                data: {
                    inUsrid: quserid,
                    inNMF: qfirstname,
                    inNML: qlastname,
                    inUsrGrp: grp,
                    inPW: qpw1,
                    inPWHT: htpw
                },
                success: function(response) {
                    if (response == '1') {
                        swal.fire(
                            'Good',
                            'registered successfully',
                            'success'
                        );
                    } else if (response === "ada") {
                        alert('the user id is already exist');
                    }
                },
                error: function(xhr, ajaxOptions, throwError) {
                    alert(throwError);
                }
            })
        }
    });
</script>