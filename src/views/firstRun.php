<?php
require "../../vendor/autoload.php";

$haveServers = $this->container->make("dhope0000\LXDClient\Model\Hosts\HostList");

if ($haveServers->haveAny() === true) {
    header("Location: /");
    exit;
}

?>
<!DOCTYPE html>
<head>
<link rel="apple-touch-icon" sizes="57x57" href="/assets/lxdMosaic/favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/assets/lxdMosaic/favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/assets/lxdMosaic/favicons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/assets/lxdMosaic/favicons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/assets/lxdMosaic/favicons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/assets/lxdMosaic/favicons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/assets/lxdMosaic/favicons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/assets/lxdMosaic/favicons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/assets/lxdMosaic/favicons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/assets/lxdMosaic/favicons/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/lxdMosaic/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/assets/lxdMosaic/favicons/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/lxdMosaic/favicons/favicon-16x16.png">
<link rel="manifest" href="/assets/lxdMosaic/favicons/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/assets/lxdMosaic/favicons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta charset="utf-8">
<title>LXD Mosaic</title>
<link rel="stylesheet" href="/assets/dist/login.dist.css">
<script src="/assets/dist/login.dist.js"></script>
<link rel="stylesheet" href="/assets/dist/external.fontawesome.css">
<style>
body {
    background-color: #a8a8a8;
}
</style>

</head>
<html>
<body>
    <nav class="navbar navbar-expand navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">
               <img src="/assets/lxdMosaic/logo.png" style="width: 25px; height: 25px; margin-left: 1px; margin-right: 5px;" alt="">
          LXD Mosaic
        </a>

      <ul class="nav navbar-nav ms-auto">
      <button class="btn btn-success nav-item" id="launchLxdMosaic">Launch LXD Mosaic <i class="fas fa-rocket"></i></button>
    </ul>
    </nav>
    <div class="container-fluid pt-5">
    <div class="row pt-3">
        <div class="col-sm-12 col-lg-6 offset-lg-1 mb-3 mb-lg-0">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h4 class=''><i class="fas fa-server me-2"></i>Hosts</h4>
                </div>
                <div class="card-body">
                    <small class="font-italic"><i class="fa fa-info-circle text-info me-2"></i>Add one host in a cluster and the rest will be discovered (attempted using the same trust password!)</small>
                    <div class="mt-2 mb-5 d-block">
                        <button class="btn btn-sm btn-primary float-start" id="addServer">
                            <i class="fas fa-plus me-2"></i>Host
                        </button>
                        <div class="form-check float-end">
                          <input class="form-check-input" type="checkbox" value="" id="showPasswordCheck" autocomplete="off">
                          <label class="form-check-label" for="showPasswordCheck">
                            Show Passwords
                          </label>
                        </div>
                    </div>
                    <div id="serverGroups" class="d-block mt-2"></div>
                    <div class="d-block text-center">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                    <h4 class=''><i class="fas fa-users me-2"></i>Users</h4>
                </div>
                <div class="card-body">
                    <small class="d-block font-italic mb-2"><i class="fa fa-info-circle text-warning me-2"></i>You will have to assign users other than <code>admin</code> to projects before they can login!</small>
                    <small class="d-block font-italic"><i class="fa fa-info-circle text-info me-2"></i>LDAP can also be configured later!</small>
                    <div class="mt-2 mb-5 d-block">
                        <button class="btn btn-sm btn-primary  float-start" id="addUser">
                            <i class="fas fa-plus me-2"></i>User
                        </button>
                        <div class="form-check float-end">
                          <input class="form-check-input" type="checkbox" value="" id="showUserPasswordCheck" autocomplete="off">
                          <label class="form-check-label" for="showUserPasswordCheck">
                            Show Passwords
                          </label>
                        </div>
                    </div>
                    <div id="userGroups" class="d-block mt-2">
                        <div class="input-group mb-3 userGroup" id="adminUserGroup">
                            <span class="input-group-text"><i class="fas fa-user-secret"></i></span>
                            <input placeholder="username" name="username" class="form-control" value="admin" autocomplete="new-password" disabled/>
                            <input placeholder="password" name="password" type="password" class="form-control" autocomplete="new-password"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
<script>

let hostTemplate = `<div class="input-group mb-3 serverGroup">
    <span class="input-group-text serverLabel"></span>
    <input placeholder="ip / hostname" name="connectDetails" class="form-control" autocomplete="new-password"/>
    <input placeholder="trust password" name="trustPassword" type="password" class="form-control trustPasswordInput" autocomplete="new-password"/>
    <input placeholder="Alias (Optional)" name="alias" type="text" class="form-control"/>
    <button class="btn btn-sm btn-outline-secondary removeRow" type="button">
        <i class="fas fa-trash"></i>
    </button>
</div>`;
let userTemplate = `<div class="input-group mb-3 userGroup">
    <span class="input-group-text"><i class="fas fa-user"></i></span>
    <input placeholder="username" name="username" class="form-control" autocomplete="new-password"/>
    <input placeholder="password" name="password" type="password" class="form-control trustPasswordInput" autocomplete="new-password"/>
    <button class="btn btn-sm btn-outline-secondary removeRow" type="button">
        <i class="fas fa-trash"></i>
    </button>
</div>`;

$(function(){
    $("#addServer").trigger("click");
});

function reLabelServers(){
    let i = 1;
    $("#serverGroups").find(".serverLabel").each(function(){
        $(this).text("Host " + i);
        i++;
    });
}

$(document).on("change", "#showPasswordCheck", function(){
    if($(this).is(":checked")){
        $("#serverGroups").find(".trustPasswordInput").attr("type", "text");
    }else{
        $("#serverGroups").find(".trustPasswordInput").attr("type", "password");
    }
});

$(document).on("change", "#showUserPasswordCheck", function(){
    if($(this).is(":checked")){
        $("#userGroups").find("input[name=password]").attr("type", "text");
    }else{
        $("#userGroups").find("input[name=password]").attr("type", "password");
    }
});

$(document).on("click", ".removeRow", function(){
    $(this).parents(".serverGroup").remove();
    $(this).parents(".userGroup").remove();
    reLabelServers();
});

$(document).on("click", "#addServer", function(){
    $("#serverGroups").append(hostTemplate);
    reLabelServers();
});
$(document).on("click", "#addUser", function(){
    $("#userGroups").append(userTemplate);
    reLabelServers();
});

$(document).on("click", "#launchLxdMosaic", function(){

    let adminUserGroup = $("#adminUserGroup");

    if (adminUserGroup.length === 0){
        toastr["error"]("Nice try hax0r!");
        return false;
    }

    let adminPasswordInput = adminUserGroup.find("input[name=password]");

    if(adminPasswordInput.length === 0){
        toastr["error"]("Nice try hax0r!");
        return false;
    }

    let adminPassword = adminPasswordInput.val();

    if(adminPassword === ""){
        adminPasswordInput.focus();
        toastr["error"]("Please provide an admin password!");
        return false;
    }

    let additionalUsers = [];
    let failed = false;

    $(".userGroup:gt(0)").each(function(){
        let userInputs = $(this);
        let userNameInput = userInputs.find("input[name=username]");
        let userPasswordInput = userInputs.find("input[name=password]");
        let username = userNameInput.val();
        let password = userPasswordInput.val();

        if (username == ""){
            failed = true;
            userNameInput.focus();
            toastr["error"](`Please provide a username!`);
            return false;
        }else if(password == false){
            failed = true;
            userPasswordInput.focus();
            toastr["error"](`Please provide ${username} a password!`);
            return false;
        }

        additionalUsers.push({
            username: username,
            password: password
        });
    });

    if(failed){
        return false;
    }

    if($(".serverGroup").length == 0){
        $("#addServer").trigger("click");
        toastr["error"]("Please provide atleast one host");
        return false;
    }

    let details = {
        hosts: [],
        adminPassword: adminPassword,
        users: additionalUsers
    };

    failed = false;

    $(".serverGroup").each(function(){

        let connectDetailsInput = $(this).find("input[name=connectDetails]");
        let trustPasswordInput = $(this).find("input[name=trustPassword]");
        let connectDetailsInputVal = connectDetailsInput.val();
        let trustPasswordInputVal = trustPasswordInput.val();
        if(connectDetailsInputVal == ""){
            failed = true;
            connectDetailsInput.focus();
            toastr["error"]("Please provide host details");
            return false;
        } else if(trustPasswordInputVal == ""){
            failed = true;
            trustPasswordInput.focus();
            toastr["error"]("Please provide trust password");
            return false;
        }

        let alias = $(this).find("input[name=alias]").val();
        alias = alias == "" ? null : alias;

        details.hosts.push({
            name: connectDetailsInputVal,
            trustPassword: trustPasswordInputVal,
            alias: alias
        });
    });

    if(failed){
        return false;
    }

    $.ajax({
         type: 'POST',
         data: details,
         url: "/api/InstanceSettings/FirstRunController/run",
         success: function(data){
             let result = $.parseJSON(data);
             if(result.state !== "success"){
                 toastr["error"](result.message);
                 return false;
             }
             location.reload();
         }
     });
});

</script>
