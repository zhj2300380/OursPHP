<?php /* Smarty version Smarty-3.1.12, created on 2017-03-18 09:26:53
         compiled from "/home/zhaojin/GitOursPHP/Demo/manage/template/login/index.html" */ ?>
<?php /*%%SmartyHeaderCode:166122230458cc8cdd7e8c57-19066581%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c36da2636c7c965b58e89676f607a0b6b947bf31' => 
    array (
      0 => '/home/zhaojin/GitOursPHP/Demo/manage/template/login/index.html',
      1 => 1489800107,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '166122230458cc8cdd7e8c57-19066581',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'msg' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_58cc8cdd80aba0_94251591',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58cc8cdd80aba0_94251591')) {function content_58cc8cdd80aba0_94251591($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>管理员登录</title>

    <meta name="description" content="User login page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" href="/assets/css/font-awesome.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="/assets/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="/assets/css/ace.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/css/ace-part2.css" />
    <![endif]-->
    <link rel="stylesheet" href="/assets/css/ace-rtl.css" />

    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/assets/css/ace-ie.css" />
    <![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.js"></script>
    <![endif]-->
</head>

<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="center">
                        <h1>
                            <i class="ace-icon "></i>
                            <span class="red">OursPHP Demo</span>
                            <span class="white" id="id-text2">Application</span>
                        </h1>
                        <h4 class="blue" id="id-company-text">&copy; 公司名称</h4>
                    </div>

                    <div class="space-6"></div>

                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="ace-icon fa fa-coffee green"></i>
                                        Please Enter Your Information
                                    </h4>

                                    <div class="space-6"></div>

                                    <form method="post">
                                        <fieldset>
                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" placeholder="Username" name="vars[username]" required/>
															<i class="ace-icon fa fa-user"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="password" class="form-control" placeholder="Password" name="vars[password]" required/>
															<i class="ace-icon fa fa-lock"></i>
														</span>
                                            </label>

                                            <label class="block clearfix">
														<span class="block input-icon input-icon-right">
                                                            <img id="img-captcha" src="/?_c=login&_a=Captcha"/>
                                                            <input class="input-small pull-right" maxlength="4" type="text" name="vars[code]" required/>
															<i class="ace-icon fa fa-lock"></i>
														</span>
                                            </label>


                                            <div class="space"></div>
                                            <?php if (!empty($_smarty_tpl->tpl_vars['msg']->value)){?>
                                            <div class="center red">
                                                <i class="ace-icon glyphicon	glyphicon-volume-down  green"></i><span class="bigger-110"><?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
</span>
                                            </div>
                                            <?php }?>
                                            <div class="clearfix">
                                                <label class="inline">

                                                </label>

                                                <button id="btn-login" type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                    <i class="ace-icon fa fa-key"></i>
                                                    <span class="bigger-110">Login</span>
                                                </button>
                                            </div>

                                            <div class="space-4"></div>
                                        </fieldset>
                                    </form>

                                    <div class="social-or-login center">

                                    </div>

                                    <div class="space-6"></div>

                                </div><!-- /.widget-main -->

                                <div class="toolbar clearfix">

                                </div>
                            </div><!-- /.widget-body -->
                        </div><!-- /.login-box -->


                    </div><!-- /.position-relative -->

                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->



<!-- basic scripts -->

<!--[if !IE]> -->
<script src="/assets/js/jquery.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="/assets/js/jquery1x.js"></script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
</script>


<script type="text/javascript">


    //you don't need this, just used for changing background
    jQuery(function($) {
        $('#btn-login').on('click', function(e) {

        });
        $('#img-captcha').on('click', function(e) {
            $("#img-captcha").attr("src","/login/Captcha?t="+Date.parse(new Date()));
        });

    });
</script>
<!-- inline scripts related to this page -->
</body>
</html><?php }} ?>