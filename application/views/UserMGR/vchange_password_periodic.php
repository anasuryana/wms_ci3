<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="<?=base_url("assets/bootstrap/css/bootstrap.min.css")?>">
    <script type="text/javascript" src="<?php echo base_url("assets/jquery/jquery.min.js"); ?>"></script>
  </head>
  <body>
    <div class="container-sm p-3">
        <h1>Change password</h1>
        <div class="row">
            <div class="col">
                <form class="">
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" id="txt_new_pw" onkeyup="txt_new_pw_eKeyup()">
                    <label for="txt_new_pw">New password</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="password" class="form-control rounded-3" id="txt_new_pw_confirmation" onkeyup="txt_new_pw_confirmation_eKeyup()">
                    <label for="txt_new_pw_confirmation">Confirm password</label>
                  </div>
                  <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" onclick="btn_e_click(this,event)">OK</button>  
                  <input type="hidden" id="txt_uid" value="<?=$this->input->get('uid')?>">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col" id="info_container">

            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?=base_url("assets/js/popper.min.js")?>"></script>	
    <script type="text/javascript" src="<?=base_url("assets/bootstrap/js/bootstrap.min.js")?>"></script>
    <script>
        function btn_e_click(elem, evt)
        {
            evt.preventDefault()
            if(txt_new_pw.value.trim().length===0)
            {
                txt_new_pw.focus()
                info_container.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                new password should not be blank
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`
                return
            }
            if(txt_new_pw.value.trim()===txt_new_pw_confirmation.value.trim())
            {
                if(confirm("Are you sure ?"))
                {
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('User/set_new_password')?>",
                        data: {userid: txt_uid.value, npw : txt_new_pw_confirmation.value},
                        dataType: "json",
                        success: function (response) {
                            if(response.status.cd===1)
                            {
                                info_container.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                                                <strong>${response.status.msg}</strong>
                                                                <p>we will redirect you to Login page</p>
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                            </div>`
                                setTimeout(()=> {
                                    window.open("<?=base_url("/")?>","_self");
                                }, 5000)
                            } else {
                                info_container.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                                ${response.status.msg}
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                            </div>`
                            }
                        }
                    })
                }
            } else 
            {
                txt_new_pw_confirmation.focus()
                info_container.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                new password and confirmed password must be same
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`
            }
        }
        function txt_new_pw_eKeyup()
        {            
            info_container.innerHTML = ''
        }
        function txt_new_pw_confirmation_eKeyup()
        {            
            info_container.innerHTML = ''
        }        
    </script>
  </body>
</html>