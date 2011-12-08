<?php $message = $this->passedArgs; ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo Framework::getSystemName(); ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>webroot/css/login1.css" />
        <?php /*
        <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>webroot/css/style1.css" />*/ ?>
        <link rel="stylesheet" type="text/css" href="<?php echo $base; ?>webroot/css/meican3-theme/jquery-ui-1.8.16.custom.css" />
        <script language="JavaScript1.2" src="<?php echo $this->url(''); ?>webroot/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo $base; ?>webroot/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script language="JavaScript1.2" src="<?php echo $this->url(''); ?>webroot/js/main.js"></script>
        <script language="JavaScript">

            $(document).ready(function(){
                $("#login").focus();
                $("body").uify();
            }); //do ready

        </script>
    </head>


    <body>
        <div id="header" class="header">

            <div id="logo_box">
                <img src="<?php echo $this->url(''); ?>webroot/img/meican_white.png" class="logo" alt="MEICAN"/>
            </div>
            <div id="info_box">
                <ul>
                    <li><a href="#"><?php echo _('Create an account'); ?></a></li>
                    <li><a href="#"><?php echo _('About MEICAN'); ?></a></li>
                    <li><a href="#"><?php echo _('Support'); ?></a></li>
                </ul>
            </div>
        </div>
        <div id="content">
            <div id="figure">
                <img src="<?php echo $this->url(''); ?>webroot/img/logo_login.png" alt="MEICAN">
            </div>
            <div id="text_info">

            </div>


            <div id="login_form" class="tab_content">
                <h2 style="padding: 6px 0;"><?php echo _('Sign in to MEICAN'); ?></h2>
                <hr>
                <form name="login_form" method="POST" action="<?php echo $this->buildLink(array('action' => 'doLogin')); ?>">
                    <table>
                        <tr>
                            <td>
                                <?php echo _('Login'); ?>
                            </td>
                            <td>
                                <input class="text" type="text" name="login" id="login"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php echo _('Password'); ?>
                            </td>
                            <td>
                                <input class="text" type="password" name="password">
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                (<a href="#"><?php echo _('Forgot your password?'); ?></a>)
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div id='message'><?php echo $message ?></div>
                            </td>


                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <input class="next"  type="submit" name="submit_login" value="<?php echo _('Sign in'); ?>">
                            </td>

                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div id="footer">
            <!--            <a href="#">
            <?php //echo _('About us'); ?>
                        </a> |
                        <a href="#">
            <?php //echo _('Developers'); ?>
                        </a> |
                        <a href="#">
            <?php //echo _('Terms of service'); ?>
                        </a> |
                        <a href="#">
            <?php //echo _('Privacy policy'); ?>
                        </a>
                        <br>
                        2011
            -->
        </div>
    </body>
</html>
