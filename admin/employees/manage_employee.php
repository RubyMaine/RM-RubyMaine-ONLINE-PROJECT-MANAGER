<?php
require_once('../../config.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `employee_list` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0){
        $res = $qry->fetch_array();
        foreach($res as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<div class="container-fluid">
    <form id="employee-form" action="" method="post">
        <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
        <input type="hidden" name="generated_password" value="<?= isset($generated_password) ? $generated_password : '' ?>">
        <div class="row">
            <div class="form-group col-md-4">
                <input type="text" name="code" id="code" placeholder="IT-001122-2023" autofocus required class="form-control form-control-sm form-control-border" value="<?= isset($code) ? $code :"" ?>">
                <small class="mx-2"> Код сотрудника: </small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <input type="text" name="firstname" id="firstname" placeholder="Имя ..." autofocus required class="form-control form-control-sm form-control-border" value="<?= isset($firstname) ? $firstname :"" ?>">
                <small class="mx-2"> Имя: </small>
            </div>
            <div class="form-group col-md-4">
                <input type="text" name="lastname" id="lastname" placeholder="Фамилия ..." required class="form-control form-control-sm form-control-border" value="<?= isset($lastname) ? $lastname :"" ?>">
                <small class="mx-2"> Фамилия: </small>
            </div>
            <div class="form-group col-md-4">
                <input type="text" name="middlename" id="middlename" placeholder="Отчество ..." class="form-control form-control-sm form-control-border" value="<?= isset($middlename) ? $middlename :"" ?>">
                <small class="mx-2"> Отчество: </small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <select name="gender" id="gender" class="form-control form-control-sm form-control-border" required>
                    <option <?= isset($gender) && $gender =='Male' ? 'selected' : "" ?>> Мужской </option>
                    <option <?= isset($gender) && $gender =='Female' ? 'selected' : "" ?>> Женский </option>
                </select>
                <small class="mx-2"> Пол: </small>
            </div>
            <div class="form-group col-md-4">
                <input type="text" name="department" id="department" placeholder="IT Департамент" required class="form-control form-control-sm form-control-border"  value="<?= isset($department) ? $department :"" ?>">
                <small class="mx-2"> Департамент: </small>
            </div>
            <div class="form-group col-md-4">
                <input type="text" name="position" id="position" placeholder="Web Developer" required class="form-control form-control-sm form-control-border" value="<?= isset($position) ? $position :"" ?>">
                <small class="mx-2"> Должность: </small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-10">
                <input type="email" name="email" id="email" placeholder="rubymaine@sample.com" required class="form-control form-control-sm form-control-border" value="<?= isset($email) ? $email :"" ?>">
                <small class="mx-2"> Электронная почта: </small>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-10">
                <div class="input-group input-group-sm">
                    <input type="password" name="password" id="password" class="form-control form-control-sm form-control-border" value="<?= isset($generated_password) && !empty($generated_password) ? $generated_password : '' ?>" readonly <?= !isset($id) ? 'required' : '' ?> >
                    <div class="input-group-append ">
                    <span class="input-group-text bg-transparent border-top-0 border-left-0 border-right-0 rounded-0"><a href="javascript:void(0)" id="pass_view" class="text-decoration-none text-muted"><i class="fas fa-eye-slash"></i></a></span>
                    </div>
                </div>
                <small class="mx-2"> Пароль: </small>
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-light border rounded-0" type="button" id="generate-btn"> Генерировать! </button>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="control-label"> Фотография нового сотрудника: </label>
            <div class="custom-file">
                <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
                <label class="custom-file-label" for="customFile"> Выберите файл ... </label>
            </div>
        </div>
        <div class="form-group d-flex justify-content-center">
            <img src="<?php echo validate_image(isset($avatar) ? $avatar :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
        </div>
        <div class="row">
            <div class="form-group col-md-4">
                <select name="status" id="status" class="form-control form-control-sm form-control-border" required>
                    <option value ='1' <?= isset($status) && $status == 1 ? 'selected' : "" ?>> Активный </option>
                    <option value ='0' <?= isset($status) && $status == 0 ? 'selected' : "" ?>> Неактивный </option>
                </select>
                <small class="mx-2"> Состояние: </small>
            </div>
        </div>
    </form>
</div>

<script>
    function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }else{
	        	$('#cimg').attr('src', "<?php echo validate_image(isset($avatar) ? $avatar :'') ?>");
        }
	}
    function generate_pass(){
        var randomstring = Math.random().toString(36).slice(-8);
        $('input[name="generated_password"]').val(randomstring)
        $('#password').val(randomstring)
        $('#uni_modal #employee-form #password').attr('type','text')
        $('#pass_view').html('<i class="fa fa-eye"></i>')
    }
    $(function(){
        $('#generate-btn').click(function(){
            generate_pass()
        })
        $('#pass_view').click(function(){
            var type = $('#uni_modal #employee-form #password').attr('type')
            if(type == 'password'){
                $('#uni_modal #employee-form #password').attr('type','text')
                $(this).html('<i class="fa fa-eye"></i>')
            }else{
                $('#uni_modal #employee-form #password').attr('type','password')
                $(this).html('<i class="fa fa-eye-slash"></i>')
            }
        })
        $('#uni_modal #employee-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.pop-msg').remove()
            var el = $('<div>')
                el.addClass("pop-msg alert")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=save_employee",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
                success:function(resp){
                    if(resp.status == 'success'){
                        location.reload();
                    }else if(!!resp.msg){
                        el.addClass("alert-danger")
                        el.text(resp.msg)
                        _this.prepend(el)
                    }else{
                        el.addClass("alert-danger")
                        el.text("An error occurred due to unknown reason.")
                        _this.prepend(el)
                    }
                    el.show('slow')
                    $('html,body,.modal').animate({scrollTop:0},'fast')
                    end_loader();
                }
            })
        })
    })
</script>