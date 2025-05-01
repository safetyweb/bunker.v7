<?php
require 'config.php';
$id = 0;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>editor email</title>

        <!-- styles -->

        <link href="css/colpick.css" rel="stylesheet"  type="text/css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link href="css/themes/default.css" rel="stylesheet" type="text/css"/>
        <link href="css/template.editor.css" rel="stylesheet"/>
        <link href="css/responsive-table.css" rel="stylesheet"/>

        <!--[if lt IE 9]>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
        <![endif]-->
        <script type="text/javascript"> var path = '<?= $path; ?>';</script>
        <!-- <script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.22.1/feather.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

        <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.2.6/plugins/colorpicker/plugin.min.js"></script>
        <script type="text/javascript" src=" js/colpick.js"></script>
        <script type="text/javascript" src=" js/template.editor.js"></script>


    </head>
    <body class="edit">
        <div class="navbar navbar-inverse navbar-fixed-top navbar-layoutit">
            <div class="navbar-header">
                <button data-target="navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="glyphicon-bar"></span>
                    <span class="glyphicon-bar"></span>
                    <span class="glyphicon-bar"></span>
                </button>

            </div>
            <div class="collapse navbar-collapse">

                <ul class="nav" id="menu-layoutit">
                    <li>
                        <div class="btn-group" data-toggle="buttons-radio">
                            <button type="button" class="btn btn btn-default" id="sourcepreview"><i class="glyphicon-eye-open glyphicon"></i> Preview</button>
                        </div>
                        <div class="btn-group">
                            <a class="btn btn btn-warning" href="#save" id="save" ><i class="glyphicon glyphicon-floppy-disk"></i> Save</a>
                        </div>
                    </li>
                </ul>
            </div><!--/.navbar-collapse -->
        </div><!--/.navbar-fixed-top -->

        <div class="row">
            <div class="sidebar-nav">
                <!-- Nav tabs -->

                <div id="elements">
                    <ul class="nav nav-list accordion-group">
                        <li class="rows" id="estRows">
                            <!-- TITOLO E SUBTITOLO  -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-trash glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>
                                <div class="preview">
                                    <div class="icon title-block"></div>
                                    <label>Title</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color:#FFFFFF;" data-type="title">
                                            <tbody>
                                                <tr>
                                                    <td align="left" class="title" style="padding:5px 50px 5px 50px">
                                                        <h1 style="font-family:Arial"> Enter your Title here! </h1>
                                                        <h4>Your Subtitle</h4>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- ------------------------ -->

                            <!-- filetto orizzontale -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon divider-block"></div>
                                    <label>Divider</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table class="main" width="640" style="border:0px; background-color: #FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" data-type='line'>
                                            <tr>
                                                <td class="divider-simple" style="padding: 15px 50px 0px 50px;">
                                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: 1px solid #DADFE1;">
                                                        <tr>
                                                            <td width="100%" height="15px"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- default element text -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>

                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon text-block"></div>
                                    <label>Text</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" style="background-color:#FFFFFF" align="center" data-type='text-block'>
                                            <tbody>
                                                <tr>
                                                    <td class="block-text" data-clonable="true" align="left" style="padding:10px 50px 10px 50px;font-family:Arial;font-size:13px;color:#000000;line-height:22px">
                                                        <p style="margin:0px 0px 10px 0px;line-height:22px">This is a Text Block! Click on this text to edit it. You can add content easily by dragging content blocks from the right sidebar. Drag this and other blocks around to re-order them.</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- immagine -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon image-block"></div>
                                    <label>Image</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" align="center" style="background-color:#FFFFFF;" data-type="image">
                                            <tbody>
                                                <tr>
                                                    <td align="center" style="padding:15px 50px 15px 50px;" class="image">  <!-- https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg -->
                                                        <img class=""  border="0"  align="one_image" style="display:block;max-width:540px" alt="" src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" tabindex="0">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- buttons -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon button-block"></div>
                                    <label>Button</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">

                                        <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF"  align="center"  style="background-color:#FFFFFF;" data-type="button">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 15px 50px 15px 50px;" class="buttons-full-width">
                                                        <table width="" cellspacing="0" cellpadding="0" border="0" align="center" class="button">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="padding: 10px 10px 10px 10px;" class="button" >
                                                                        <a style="background-color: #3498DB;color: #FFFFFF;font-family: Arial;font-size: 15px;line-height:21px;display: inline-block;border-radius: 6px;text-align: center;text-decoration: none;font-weight: bold;display: block;margin: 0px 0px; padding: 12px 20px;" class="button-1" href="#" data-default="1">Click me</a>                   <!--[if mso]>             </center>           </v:roundrect>         <![endif]-->
                                                                    </td>

                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <!--Image + Text -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon image-text-block"></div>
                                    <label>Image/Text</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table class="main" width="640" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" style="background-color:#FFFFFF;" data-type="imgtxt">
                                            <tbody>
                                                <tr>
                                                    <td class="image-text" align="left" style="padding: 15px 50px 10px 50px; font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;">
                                                        <table class="image-in-table" width="190" align="left">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="gap" width="30"></td>
                                                                    <td width="160">
                                                                        <img  border="0"  align="left" src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" style="display: block;margin: 0px;max-width: 540px">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                            </tbody>
                                                        </table>
                                                        <h2 style="line-height: 30px; text-decoration: none; font-weight: normal;font-family: Arial; font-weight: normal; font-size: 20px; color: #34495E; margin: 0px 0px 10px 0px;"> this is a title </h2>
                                                        <p style="margin: 0px 0px 10px 0px; line-height: 22px;">
                                                            Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- TESTO E IMMAGINE IN COLONNA TEXT ON RIGHT SIDE-->

                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon image-text-col-2"></div>
                                    <label>Image/Text</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table class="main" width="640" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" style="background-color:#FFFFFF;" data-type="imgtxtcol">
                                            <tbody>
                                                <tr>
                                                    <td class="image-text" align="left" style="padding: 15px 50px 10px 50px; font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;">
                                                        <table class="image-in-table" width="190" align="left">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="gap" width="30"></td>
                                                                    <td width="160"> <!-- https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg --> 
                                                                        <img  border="0"  align="left" src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" style="display: block;margin: 0px;max-width: 340px">
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                        <table width="190">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-block"  >
                                                                        <p style="margin-left:10px; line-height: 22px;">
                                                                            Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <!-- TESTO E IMMAGINE IN COLONNA TEXT ON LEFT SIDE-->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon image-text-col-1"></div>
                                    <label>Image/Text</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table class="main" width="640" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" style="background-color:#FFFFFF;" data-type="imgtxtcol">
                                            <tbody>
                                                <tr>
                                                    <td class="image-text" align="left" style="padding: 15px 50px 10px 50px; font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;">
                                                        <table width="190" align="left">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="text-block"  >
                                                                        <p style="line-height: 22px;">
                                                                            Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table class="image-in-table" width="190" align="right">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="gap" width="30"></td>
                                                                    <td width="160">
                                                                        <img  border="0"  align="left" src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" style="display: block;margin: 0px;max-width: 340px">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- IMG + TEXT 2 columns -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon image-text-incol-2"></div>
                                    <label>Image/Text</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table class="main" width="640" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" style="background-color:#FFFFFF;" data-type="imgtxtincol">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table class="main" align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="640">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="image-caption" style="padding: 0px 50px 0px 50px;">
                                                                        <table class="image-caption-column" align="left" border="0" cellpadding="0" cellspacing="0" width="255">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content image" style="font-family: Arial; font-size: 13px; color: #000000;">
                                                                                        <img src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" alt="" style="display: block;" height="154" align="2" border="0" width="255">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content text" style="font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;" align="left">
                                                                                        <p style="margin: 0px 0px 10px 0px;        line-height: 22px;"> Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </P>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-bottom-gap" height="5" width="100%"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table class="image-caption-column" align="right" border="0" cellpadding="0" cellspacing="0" width="255">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="image-caption-top-gap" height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content image" style="font-family: Arial; font-size: 13px; color: #000000;">
                                                                                        <img src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" alt="" style="display: block;" height="154" align="2" border="0" width="255">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content text" style="font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;" align="left">
                                                                                        <p style="margin: 0px 0px 10px 0px;        line-height: 22px;">  Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="5" width="100%"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- IMG +TEXT 3 columns -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon image-text-incol-3"></div>
                                    <label>Image/Text</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table class="main" width="640" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" style="background-color:#FFFFFF;" data-type="imgtxtincol">
                                            <tbody>
                                                <tr>
                                                    <td class="image-caption" style="padding: 0px 50px 0px 50px;">
                                                        <table class="image-caption-container" align="left" border="0" cellpadding="0" cellspacing="0" width="350">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <table class="image-caption-column" align="left" border="0" cellpadding="0" cellspacing="0" width="160">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content image" style="font-family: Arial; font-size: 13px; color: #000000;">
                                                                                               <img src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" alt="" style="display: block;" height="154" align="2" border="0" width="160">
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content text" style="font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;" align="left">
                                                                                          <p style="margin: 0px 0px 10px 0px;        line-height: 22px;">  Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. </p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-bottom-gap" height="5" width="100%"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <table class="image-caption-column" align="right" border="0" cellpadding="0" cellspacing="0" width="160">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td class="image-caption-top-gap" height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content image" style="font-family: Arial; font-size: 13px; color: #000000;">
                                                                                             <img src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" alt="" style="display: block;" height="154" align="2" border="0" width="160">
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="15" width="100%"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-content text" style="font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;" align="left">
                                                                                        <p style="margin: 0px 0px 10px 0px;        line-height: 22px;">Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="image-caption-bottom-gap" height="5" width="100%"></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>

                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table class="image-caption-column" align="right" border="0" cellpadding="0" cellspacing="0" width="160">
                                                            <tbody>
                                                                <tr>
                                                                    <td class="image-caption-top-gap" height="15" width="100%"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="image-caption-content image" style="font-family: Arial; font-size: 13px; color: #000000;">
                                                                        <img src="https://cdn.pixabay.com/photo/2019/06/24/17/49/mythical-4296587_960_720.jpg" alt="" style="display: block;" height="154" align="2" border="0" width="160">
                                                                      </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="15" width="100%"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="image-caption-content text" style="font-family: Arial; font-size: 13px; color: #000000; line-height: 22px;" align="left">
                                                                        <p style="margin: 0px 0px 10px 0px;        line-height: 22px;">Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="5" width="100%"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- SOCIAL LINKS -->
                            <div class="lyrow dragitem">
                                <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                <span class="drag label label-default hide"><i class="glyphicon glyphicon-move"></i></span>
                                <span class="configuration"> <a href="#" class="btn btn-default btn-xs clone"><i class="fa fa-clone"></i> </a>  </span>

                                <div class="preview">
                                    <div class="icon social-block"></div>
                                    <label>Social Links</label>
                                </div>
                                <div class="view">
                                    <div class="row clearfix">
                                        <table class="main" align="center" width="640" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFFFFF" data-type="social-links">
                                            <tbody>
                                                <tr>
                                                    <td class="social" align="center" style="padding: 15px 50px 15px 50px;">
                                                        <a href="#" style="border: none;" class="facebook">
                                                            <img border="0" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAACQUlEQVRo3u2az0sCQRTH5+q/0Enw7D8wBB2ioEvnCRQyunTqonjpZHT3EIE+CY26FHTsEB3ck0kFddjwlEEiQYeIjG0Nm1BmYdtWRfft7A8a+F5UZr6fdX68N28JQWiUQZQySFEGRcpAoQzalEGHMuBCHfGZIn7T/22UeNkogxnKIEsZqCajk0oVfczINB6jDAqUQdeBcau6os+Ym8YjlEGOMtARjVulizEi2ObjlEHDReNW9ceKY5lPUgaaRPOG+mMmnZrPeGDcqkyQzU8HIaYN95mSkyxYzYcA2tiFLbbKhg/Nm3enyCiAHNZgi2sVDsfXvPHwwrXPL25tDvrOjTphUQ6p5Y0j3np+46Oaw8MuZgdQwHr61XqTj2sOxyjYBWYosc3S+gHv9b7dBuj+CgBFRIjy9Dd3zmwN126f+O7hJc9XagMhjJU1A6hYAFv5C1uA+dUy9o6kmpMRtI6396puTJlhihKRHQUVIEVEiueoo7nkPi+d3AykXD3aAhjfG5pdKWEAFInIUx0fWpO09w8d6x9QiEi2pQI0W69YAG1iuT2QAlC/a2EBdAhGRwup8mBa9GUX9xjTxtDp+T3aQibYO4PkXYijTCEPATooi9hDgDbKNuohgIJykHkIUAxFKBHsYA47nJYIoLqS0EgEyLqSUkoC6P6pKWAl9ZIACq5dq0gA0IcWQjAutiQA5MJ7tRj4y10TRMKHAIlJawRpH5lPT1ulSQfWvGU6eVXkS/yXWUNR6A7FqwahednDb6/b/ADOkP58IFTtDQAAAABJRU5ErkJggg==" />

                                                        </a>
                                                        <a href="#" style="border: none;" class="twitter">

                                                            <img border="0" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAE0ElEQVRo3t1aTWwbRRSeqqGgIgRc6KlVDghxIP0TEkg9VAL1wAGIxKVSD1RFFKlCioLSwoV7VCE4BQFF/Dh/tsdRgsC0JE5JCyFBzk9pqFApSUVJKrvZHXvttdfrn/3Q7s6miRvHs/amBI/0LvbMvO+bmffmvTdLCGXEA2khlLUSys4SykKEsklCWZxQluQS57+FeJ9WPqZ+3XUM3kMo6yCUnSeUlQhlcCklPraDz3XfCDQTyroIZYs1gK4ki3zO5s0ksJNQdoZQlvAQeLkkuI6dXhM4QCib2ETg5TLBdXpC4CShTLmP4B1RuO66CLT9B8DLpa1WAqe2AHhHTrklcIJQZmwhAgbHJETAvGDULQTeEXXdy6/shx2EspktCN6RGY6xIoGOTQXQJ4N0SyA+CaRHBgnUNE9HJQK7CWVpz0GHmA3UJ+HhwQQORhQ8P5rCk+eTIP2yTcTp28sJbjxnmmO9h0BXVSCOQjcrZ44JMrRdyeBXVoDTEnkD/bd0HP4xBeKTQb6WsH9EwetRFSRgj9lg3q5yAmYwxaoBeWU8jXMLOTSF+GqJgPfL+PxmDpVapgh0/qFhcFGHrBt4ezZj7cK20IZzs5UAkBNorwqmR8bH8zaQwD86tvvtY1Gxf9AmefpqFqKt928dz0ZS2D+SwnZ/1V1odwiYVh0RIfDJ/N2VHI0X8Mz3irX11pEqV+ZneGwogXiuJAQ+mTcwIRVwTSnh0GhKZIcjFnbuW0siBM4t6GuUmlt++koWD/H/LQN0Vq5Pxsvjabhpt7USDgwr9s6GhPKJFpPAMSFj7JXx1nRmXcVzySLaZzNoMT2LCd7clc+WcXRSFQZvADgYSYF8sezGyx0zCXQKdfYzNFGGqVWepLwVAYRv53H2uoY3J1V8cF2zgIm0payBXd8mxZzDXek0CYSFOgcZHvQz7BtWMJ8uwes2zYp4ZDBhX3biBMImgahQZ9MG5nO4GC9gUip6TuDSnQIeGOCuV5xA1CQQEyXw3lwWm9W6/srZxht0ddPHiHD44Gd4fIhhIVPaFAInpzMgXy67DVXSxFX80y3jyFgK2aLhKXimG2j+Lun2+KwQiAkPCNpHad8PihXHaB6Zgv+WLhLEVTxCUVeDeiW8MJbGwKKOm2r9x8ncy70jilvvs8aIw64GdUt46Sf7hvXCGj6d123fH6wpXA+LX2Rlt/K7HnikJc3AE98kQHqkWvONTvFQYh1beO0XFVeTRcS0EjSXhm12f/FSutazvyaUaHFdnHWyrK8kS45cTmPBpT28MZWxE5lgzeBXgjmxcDrIb8k+npX5JDw6mMCJqQymmLg7YnkDreOqvfLButLVlXB644TGVBKQsXdYwfGoijO/ZfH+XBZDizpuuIyJhmN5PH0haUerwbrz7XbxlDLArIT8ndkMflfcO/+ZRBHHJ1VsC5Ql8bXLPSll9aSep4hNAwxHJ1R8dEPD5eWC5UlWt3TBwJJWwsU7BXz4p4ZXf1btsd1SrWUUoaRevKwS4AmLT8KOAYY94aQVYh8eS+G50RSeuqBgdzhpEbWCMx8HHvIMfMWyirvClmPUvauKVU7ByvytX/binLsqbDVEafF/X9xtiPJ6QzxwNMQTU0M88jXEM2tDPHQ3xKcGDfOxx5b63OZfyJtrHlNMOoUAAAAASUVORK5CYII="/>


                                                        </a>
                                                        <a href="#" style="border: none;" class="linkedin">

                                                            <img  border="0" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAC2ElEQVRo3mNgoAao2qnAULUzkaFq5yyGqp0HGap2PmOo2vmFoWrnfyj+AhU7CFWTCNYzoKBqpxRD1c5yhqqdV5EcSiq+CjVDip4OV2ao2jmToWrnLwocjo5/Qc1UpqXDORmqdjYxVO38SUWHo+OfUDs4qe14XYaqnTdo6HB0fANsJ5UcH8tQtfM7HR0Pw9/BdlPo+NIBcDg6Lh3KjifTE5Bk83+Q4VhSMuz3QeiB74QzNqSovDEIHY9cOnHi80ATMQalrLvy48SjD/9vvP7yf9apx/+FW/bR0xNN+GpYgpVUxoZrv/+jAZBn6OiBn9hrbEhVTtCA448+/McGVHsP09MTM7E1zIhq2+y/9xarB+S7DtHTA79QG4CQFiFRmv0Xn///7x+q41dffjEQGboc2QMkNYkd55z+v+DsU7DDszZe+89Ss2sgPHAVuTPyf4hiBQZo74iuFiv1HPofvPTC/6iVl/7bzTr1n71uN7lmJTJAu3hEa6rfcwcjA99/9w0uz9+0F2smB+Udxe5D//fcwSwEPv34/b9m9+3/TNUkJ8VZDNB+Ks09kLPp+v8Xn3/+xwfWXCG5MDjIAO1s09wDoFAmBqStv0qKB54xoI0e0MwDMPDh+2+w+j9//2GVv/P2Kyke+MJAasahxAOgdA4rckEV35WXn7GqU+sjoVanlweWXXyOYZbb/DNY1YYsu0CSB+iShMKXX8Qwi6NuN1a16RuukpSEntGrGMVmHrbMnb/lOkmZ+OBAeuD9d4o8cJCuFRkNPDCL5KbEIPNAIsmNuUHmAQWSm9ODyANXyerQDCIPlJPVpRwkHviFOadAZKd+kHhgJtnDKqA2jP3sUyjYfPoJuDxzzS4MeRDGNXZkPfMkhlrJ9gN/SR9WIWFga4Bx0zAeWhzyg7sIT8QMQg/EkDpHUDKIHF9C7ixNydB1PGpyGqhJvpjRadbhMdE9LJYaDJvFHoNsuQ0A6KEGKxcns7UAAAAASUVORK5CYII="/>


                                                        </a>
                                                        <a href="#" style="border: none;" class="youtube">

                                                            <img border=0 src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABldJREFUeNrMWmlsVFUU/mY63aYr3QuUfa0gSyuiCAJRQX+wpRAsaIIxCAKhEjVR3KJCEMQSCaL8QRsoiWJARXatLUIKUipIoAsVy6alZWY6ZdrS6XQ8586dMrTMzJut5SRf5r2Z++79vrn3nnPuvU9TkjESfrAkwhTCeMIwQn9CIiFC/m4i1BIuE8oIxYQCwk1fG9b48Gw84UVCNiGDoHJRNpQQRxhKmC6/sxJKCPmEPMItb0iovXgmlbCRUE34jJDphrwzU8lnuY4rhM2EnoEUwL31BqGCsMphePjDtITlhHLZRrC/BQwmnCKsJ0QicBYp2+C20v0lYCbhNGEMus5GE04SZvkqYDHhe0I0ut64N3ZLDl4JWEr4ihCE7rMgyWGppwJmSK/woNhmyUmRgOGEnd38z9+vJ3ZKbi4FsPvapdTTxM+Yjf7rPkVo7zRxnzBrju0+rU+g5sSuji62o4AcwiilNbbqdejx1DOImfSkTcDc+Ygam4mW69cC1RPM7TVnAjgKvudJbcbiE7A0NCB6/ARoYmKhHTwEhoKjsLa12fo9IhIqjcbfIt51jNiOAt72NEhZzWYYCgsQmZGJ6AkTqTY19EcOCzHD8vIxqvAERhcVI2n+AlG+z+r3MeKng52uvRhKqzsKSCC85E1thiOHoA4NReriJTDfqkPDmdNIyJoHbfoIXF2/Frf/LEWvlatET6jDwxEUZQspjtde2CLJuV3AQkK4NzUZTxXDYjSKiWz45QhAw4fJsdUXFcJ04TxUwcFQh4X5cxiFS873CPDKxDAq+s1G+FhhV7rWBXYBvBgZ60tN5rpa8dnW1NSVAngN0pMFTPUyn+9uY85TWMCj/q7ZUl8vPmMmTUbkw6NhbWlBG8Ha2kquNQKpS5a1xw4fLVMt17C+ETbW4w4FLybJVrdnNxpOnUTam29BOzwd13I3CBENf5wUZcL69IXh6GG03Ljua9NDVbSor6SLQYHoY/Y8QpQMbAGwSxwmkwNVe1tzc6DnQTILiPJo5pBP5/wnYuQoaOLi/MKiVaeD6fw5iuKHhFv2wKJ4CFkVR48hQzFgQy5Ce/UOyN/Jc6Lq9Rw0VZQrfoQFGJX0QnBiIobn74amRw9YGk0oe37uvRs/ffth0OdfdHpOd+Bn/PvlFjz0w36FGa4eF7OzYK6tVVL8FnuhGiUlUxa9LMiLYUR5TSwNI8772ftEZjxCGenj9/dQJpMoo3jvhtrgthSangVcUlIy5om7flsdEopeK3IQM3mquE/KXijQdKkS9b8X2dIK+uR7u925Ug39oQPtY9z01znU5H0N44njLttyY5U8icsctvucD6Fk985Kf3A/dISYfZNwdd0axE1/DsEpqeK36o8/EJlp6tUrIjZU5SxHWL/+aL78NwbmbkbMxLukg1NSlAooV8v9F/feJ8i3JfKQbdsF4TvV/8B4/BjNqSSkf7dXrB1ul5y+ty214g3DEi7Ju8RWdIEJYlarCGwWgwGVry4WCWBbs1dJIHP+VSMn8RmZ3XWZcZTmeMIIGzDQmyqY8w17X+1wK9fP6YBaq0XCnCzh+803a7xpK99xQcMCXPajuVbBWYRahVaDHtc2ftKJFE9uXnJyJNfEJ4hr3f59qNnxTft6woO2mGueo4A6wnZXT9QfK+r0XUhSMqLGjUeQVivuORZwbGCCsU9Po+u09jKNtLQMSe2J+FlzkDhvvih7fdNGRD82AfEzZ7ttq4Ntl5yhcjhi4q2Kcmc7EyHJKRi281toYmMDOjda6w0oy56Hlpr/nBW5DdtJz42O2yr8xYdOkw6qsCpnGQ2NmwEjz3VXrVzmijzbR3byHXvAvrXIcWGMq8kXN+1ZRNBKy7774I+023S2FDqK1G2Nja6KlsoVpNmZADY+jeHI0h1nAq6sAbYztQpXe6MivyC8wHnYA0TeIrd+KjqNCCcP/EhY8QAJWCE5QakAtq2EV7q5JyySw1anc9JNBdsIWbyD2A3k2V3OlRzgrQC2vXLylHYh+bPS2+xxG/yVLhxgew9itbuUw0fjut8hjCNcUJRTebLmJqyVbjYXthc4/GXs/LfwsoGwRrYFfwuwG2+n8asG/eRniZfrCfvLHlxHX9heNfD4bErlp9dteL05RY5bPkm0v24T6TAh7a/bXJTRvkDphoIr+1+AAQCQa/h+D2ew0QAAAABJRU5ErkJggg=="/>


                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <br>
                    Powered by <a href="http://www.emaileditor.net" target="_blank">Emaileditor.net</a>

                </div>
                <!-- END DROP ELEMENTS -->
                <!-- START ELEMENT -->
                <div  class="hide" id="settings">
                    <form class="form-inline" id="common-settings">
                        <h4 class="text text-info">Padding</h4>

                            <center>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td><input type="text" class="form-control" placeholder="top" value="15px" id="ptop" name="ptop" style="width: 60px; margin-right: 5px"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" class="form-control" placeholder="left" value="15px" id="pleft" name="mtop" style="width: 60px; margin-right: 5px"></td>
                                            <td></td>
                                            <td><input type="text" class="form-control" placeholder="right" value="15px" id="pright" name="mbottom" style="width: 60px; margin-right: 5px"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><input type="text" class="form-control" placeholder="bottom" value="15px" id="pbottom" name="pbottom" style="width: 60px; margin-right: 5px"></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </center>

                    </form>

                    <h4  class="text text-info">Style</h4>
                    <form id="background"  class="form-inline">
                        <div class="form-group">
                            <label for="bgcolor">Background</label>
                            <div class="color-circle" id="bgcolor"></div>
                            <script type="text/javascript">
            $('#bgcolor').colpick({
                layout: 'hex',
                onBeforeShow: function () {
                    $(this).colpickSetColor($('#bgcolor').css('backgroundColor').replace('#', ''));
                },
                onChange: function (hsb, hex, rgb, el, bySetColor) {

                    if (!bySetColor)
                        $(el).css('background-color', '#' + hex);
                },
                onSubmit: function (hsb, hex, rgb, el) {
                    $(el).css('background-color', '#' + hex);

                    $('#' + $('#path').val()).css('background-color', '#' + hex);
                    $(el).colpickHide();
                }

            }).keyup(function () {
                $(this).colpickSetColor(this.value);
            });
                            </script>

                        </div>
                    </form>

                    <form class="form-inline" id="font-settings" style="margin-top:5px">
                        <div class="form-group">
                            <label for="fontstyle">Font style</label>
                            <div id="fontstyle" class="color-circle"><i class="fa fa-font"></i></div>

                        </div>
                    </form>

                    <div class="hide" id='font-style'>
                        <div id="mainfontproperties" >
                            <div class="input-group" style="margin-bottom: 5px">
                                <span class="input-group-addon" style="min-width: 60px;">Color</span>
                                <input type="text" class="form-control picker" id="colortext" >
                                <span class="input-group-addon"></span>
                                <script type="text/javascript">
                                    $('#colortext').colpick({
                                        layout: 'hex',
                                        // colorScheme: 'dark',
                                        onChange: function (hsb, hex, rgb, el, bySetColor) {
                                            if (!bySetColor)
                                                $(el).val('#' + hex);
                                        },
                                        onSubmit: function (hsb, hex, rgb, el) {
                                            $(el).next('.input-group-addon').css('background-color', '#' + hex);
                                            $(el).colpickHide();
                                        }

                                    }).keyup(function () {
                                        $(this).colpickSetColor(this.value);
                                    });
                                </script>
                            </div>
                            <div class="input-group" style="margin-bottom: 5px">
                                <span class="input-group-addon" style="min-width: 60px;">Font</span>
                                <input type="text" class="form-control " id="fonttext" readonly>
                            </div>
                            <div class="input-group" style="margin-bottom: 5px">
                                <span class="input-group-addon" style="min-width: 60px;">Size</span>
                                <input type="text" class="form-control " id="sizetext" style="width: 100px">
                                &nbsp;
                                <a class="btn btn-default plus" href="#">+</a>
                                <a class="btn btn-default minus" href="#">-</a>
                            </div>

                            <hr/>
                            <div class="text text-right">
                                <a class="btn btn-info" id="confirm-font-properties">OK</a>
                            </div>
                        </div>

                        <div id="fontselector" class="hide" style="min-width: 200px">
                            <ul class="list-group" style="overflow: auto ;display: block;max-height: 200px" >
                                <li class="list-group-item" style="font-family: arial">Arial</li>
                                <li class="list-group-item" style="font-family: verdana">Verdana</li>
                                <li class="list-group-item" style="font-family: helvetica">Helvetica</li>
                                <li class="list-group-item" style="font-family: times">Times</li>
                                <li class="list-group-item" style="font-family: georgia">Georgia</li>
                                <li class="list-group-item" style="font-family: tahoma">Tahoma</li>
                                <li class="list-group-item" style="font-family: pt sans">PT Sans</li>
                                <li class="list-group-item" style="font-family: Source Sans Pro">Source Sans Pro</li>
                                <li class="list-group-item" style="font-family: PT Serif">PT Serif</li>
                                <li class="list-group-item" style="font-family: Open Sans">Open Sans</li>
                                <li class="list-group-item" style="font-family: Josefin Slab">Josefin Slab</li>
                                <li class="list-group-item" style="font-family: Lato">Lato</li>
                                <li class="list-group-item" style="font-family: Arvo">Arvo</li>
                                <li class="list-group-item" style="font-family: Vollkorn">Vollkorn</li>
                                <li class="list-group-item" style="font-family: Abril Fatface">Abril Fatface</li>
                                <li class="list-group-item" style="font-family: Playfair Display">Playfair Display</li>
                                <li class="list-group-item" style="font-family: Yeseva One">Yeseva One</li>
                                <li class="list-group-item" style="font-family: Poiret One">Poiret One</li>
                                <li class="list-group-item" style="font-family: Comfortaa">Comfortaa</li>
                                <li class="list-group-item" style="font-family: Marck Script">Marck Script</li>
                                <li class="list-group-item" style="font-family: Pacifico">Pacifico</li>
                            </ul>
                        </div>
                    </div>
                    <div id="imageproperties" style="margin-top:5px">
                        <div class="form-group">

                            <div class="form-group">
                              <div class="row">
                                <div class="col-xs-8">
                                   <input type="text" id="image-url" class="form-control" data-id="none"/>
                                </div>
                                <div class="col-xs-4">
                                   <a id="popupimg" class="btn btn-default" data-toggle="modal" data-target="#previewimg">Browse</a>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-1">
                                         W:
                                    </div>
                                    <div class="col-xs-3">
                                         <input type="text" id="image-w" class="form-control" name="director" />
                                    </div>

                                    <div class="col-xs-1">
                                         H:
                                    </div>

                                    <div class="col-xs-3">
                                        <input type="text" id="image-h"class="form-control" name="writer" />
                                    </div>

                                    <div class="col-xs-4">

                                        <a class="btn btn-warning" href="#" id="change-image"><i class="fa fa-edit"></i>&nbsp;Apply</a>
                                    </div>

                                </div>
                            </div>

                            <!--<a href="#" class="btn btn-default form-control" id="editimage"><i class="fa fa-image"></i>&nbsp;Edit image</a> -->
                        </div>
                    </div>
                    <form id="editor" style="margin-top:5px">
                        <div class="panel panel-body panel-default html5editor" id="html5editor"></div>
                    </form>
                    <form id="editorlite" style="margin-top:5px">
                        <div class="panel panel-body panel-default html5editorlite" id="html5editorlite"></div>
                    </form>

                    <div id="social-links">
                        <ul class="list-group" id="social-list">
                            <li>
                                <div class="input-group">
                                    <span class="input-group-addon" ><i class="fa fa-2x fa-facebook-official"></i></span>
                                    <input type="text" class="form-control social-input" name="facebook" style="height:48px"/>
                                    <span class="input-group-addon" ><input  type="checkbox" checked="checked" name="facebook" class="social-check"/></span>
                                </div>
                            </li>
                            <li>
                                <div class="input-group">
                                    <span class="input-group-addon" ><i class="fa fa-2x fa-twitter"></i></span>
                                    <input type="text" class=" form-control social-input" name="twitter" style="height:48px"/>
                                    <span class="input-group-addon" ><input type="checkbox" checked="checked" name="twitter" class="social-check"/></span>
                                </div>
                            </li>
                            <li>
                                <div class="input-group">
                                    <span class="input-group-addon" ><i class="fa fa-2x fa-linkedin"></i></span>
                                    <input type="text" class=" form-control social-input" name="linkedin" style="height:48px"/>
                                    <span class="input-group-addon" ><input type="checkbox" checked="checked" name="linkedin" class="social-check"/></span>
                                </div>
                            </li>
                            <li>
                                <div class="input-group">
                                    <span class="input-group-addon" ><i class="fa fa-2x fa-youtube"></i></span>
                                    <input type="text" class=" form-control social-input" name="youtube" style="height:48px"/>
                                    <span class="input-group-addon" ><input type="checkbox" checked="checked" name="youtube" class="social-check" /></span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div id="buttons" style="max-width: 400px">
                        <div class="form-group">
                            <select class="form-control">
                                <option value="center">Align buttons to Center</option>
                                <option value="left">Align buttons to Left</option>
                                <option value="right">Align buttons to Right</option>
                            </select>
                        </div>
                        <ul id="buttonslist" class="list-group">
                            <li class="hide" style="padding:10px; border:1px solid #DADFE1; border-radius: 4px">
                                <span class="orderbutton"><i class="fa fa-bars"></i></span>
                                <span class="pull-right trashbutton"><i class="fa fa-trash"></i></span>

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Enter Button Title" name="btn_title"/>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1"><i class="fa fa-paperclip"></i></span>
                                    <input type="text" class="form-control"  placeholder="Add link to button" aria-describedby="basic-addon1" name="btn_link"/>
                                </div>
                                <div class="input-group" style="margin-top:10px">
                                    <label for="buttonStyle">Button Style</label>
                                    <div   class="color-circle buttonStyle" data-original-title="" title="">
                                        <i class="fa fa-font"></i>
                                    </div>
                                    <div class="stylebox hide" style="width:400px">
                                        <!--
                                     <div class="input-group " style="margin-bottom: 5px">
                                         <span class="input-group-addon"><i class="fa fa-font"></i></span>
                                         <input type="text" class="form-control fontstyle" name="fontstyle" readonly style="cursor:pointer;background-color: #fff"/>
                                     </div>-->
                                        <label> Button Size</label>
                                        <div class="input-group " style="margin-bottom: 5px">
                                            <span class="input-group-addon button"  ><i class="fa fa-plus" style="  cursor : pointer;"></i></span>
                                            <input type="text" class="form-control text-center"  placeholder="Button Size"  name="ButtonSize"/>
                                            <span class="input-group-addon button"  ><i class="fa fa-minus" style="  cursor : pointer;"></i></span>
                                        </div>
                                        <label> Font Size</label>
                                        <div class="input-group " style="margin-bottom: 5px">

                                            <span class="input-group-addon font"  ><i class="fa fa-plus" style="  cursor : pointer;"></i></span>
                                            <input type="text" class="form-control text-center"  placeholder="Font Size"  name="FontSize"/>
                                            <span class="input-group-addon font"  ><i class="fa fa-minus" style="  cursor : pointer;"></i></span>
                                        </div>
                                        <div class="input-group background" style="margin-bottom: 5px">
                                            <span class="input-group-addon " style="width: 50px;">Background Color</span>
                                            <span class="input-group-addon picker" data-color="bg"></span>
                                        </div>

                                        <div class="input-group fontcolor" style="margin-bottom: 5px" >
                                            <span class="input-group-addon" style="width: 50px;">Font Color</span>
                                            <span class="input-group-addon picker" data-color="font"></span>
                                            <script type="text/javascript">
                                                $('.picker').colpick({
                                                    layout: 'hex',
                                                    // colorScheme: 'dark',
                                                    onChange: function (hsb, hex, rgb, el, bySetColor) {
                                                        if (!bySetColor)
                                                            $(el).css('background-color', '#' + hex);
                                                        var color = $(el).data('color');
                                                        var indexBnt = getIndex($(el).parent().parent().parent().parent().parent(), $('#buttonslist li')) - 1;
                                                        if (color === 'bg') {
                                                            $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('background-color', '#' + hex);
                                                            $(el).parent().parent().parent().parent().find('div.color-circle').css('background-color', '#' + hex);
                                                        } else {
                                                            $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('color', '#' + hex);
                                                            $(el).parent().parent().parent().parent().find('div.color-circle').css('color', '#' + hex);
                                                        }

                                                    },
                                                    onSubmit: function (hsb, hex, rgb, el) {
                                                        $(el).css('background-color', '#' + hex);
                                                        $(el).colpickHide();
                                                        var color = $(el).data('color');
                                                        var indexBnt = getIndex($(el).parent().parent().parent().parent().parent(), $('#buttonslist li')) - 1;
                                                        if (color === 'bg') {
                                                            $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('background-color', '#' + hex);
                                                        } else {
                                                            $($('#' + $('#path').val()).find('table tbody tr td:eq(' + indexBnt + ') a')).css('color', '#' + hex);
                                                        }


                                                    }

                                                }).keyup(function () {
                                                    $(this).colpickSetColor(this.value);
                                                });
                                            </script>

                                        </div>
                                        <div class="text text-right">
                                            <a href="#" class="btn btn-xs btn-default confirm">Ok</a>
                                        </div>
                                    </div>
                                    <div class="fontselector" class="hide" style="min-width: 200px">
                                        <ul class="list-group" style="overflow: auto ;display: block;max-height: 200px" >
                                            <li class="list-group-item" style="font-family: arial">Arial</li>
                                            <li class="list-group-item" style="font-family: verdana">Verdana</li>
                                            <li class="list-group-item" style="font-family: helvetica">Helvetica</li>
                                            <li class="list-group-item" style="font-family: times">Times</li>
                                            <li class="list-group-item" style="font-family: georgia">Georgia</li>
                                            <li class="list-group-item" style="font-family: tahoma">Tahoma</li>
                                            <li class="list-group-item" style="font-family: pt sans">PT Sans</li>
                                            <li class="list-group-item" style="font-family: Source Sans Pro">Source Sans Pro</li>
                                            <li class="list-group-item" style="font-family: PT Serif">PT Serif</li>
                                            <li class="list-group-item" style="font-family: Open Sans">Open Sans</li>
                                            <li class="list-group-item" style="font-family: Josefin Slab">Josefin Slab</li>
                                            <li class="list-group-item" style="font-family: Lato">Lato</li>
                                            <li class="list-group-item" style="font-family: Arvo">Arvo</li>
                                            <li class="list-group-item" style="font-family: Vollkorn">Vollkorn</li>
                                            <li class="list-group-item" style="font-family: Abril Fatface">Abril Fatface</li>
                                            <li class="list-group-item" style="font-family: Playfair Display">Playfair Display</li>
                                            <li class="list-group-item" style="font-family: Yeseva One">Yeseva One</li>
                                            <li class="list-group-item" style="font-family: Poiret One">Poiret One</li>
                                            <li class="list-group-item" style="font-family: Comfortaa">Comfortaa</li>
                                            <li class="list-group-item" style="font-family: Marck Script">Marck Script</li>
                                            <li class="list-group-item" style="font-family: Pacifico">Pacifico</li>
                                        </ul>
                                    </div>

                                </div>


                            </li>
                        </ul>

                        <hr/>
                        <div class="form-group">
                            <a  class="btn btn-default form-control" href="#" id="add-button">Add one more button</a>
                        </div>
                    </div>
                    
                    <div class="text text-right" style="margin-top:5px">
                        <a href="#" id="saveElement" class="btn btn-info">done</a>
                    </div>
                </div>
                <!-- END SETTINGS -->

            </div>
            <!--/span-->

            <a href="#" class="btn btn-info btn-xs" id="edittamplate">Edit background</a>
            <div id="tosave" data-id="<?php echo $id?>"  data-paramone="11" data-paramtwo="22" data-paramthree="33">
                <!-- inizio parte html da salvare -->
                <table  width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #eeeeee" >
                    <tr>
                        <td width="100%" id="primary" class="main demo" align="center" valign="top" >
                            <!-- inizio contentuto      -->


                            <div class="lyrow">
                                <div class="view">
                                    <div class="row clearfix">
                                        <!-- Content starts here-->
                                        <table width="640" class="preheader" align="center" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td align="left" class="preheader-text" width="420" style="padding: 15px 0px; font-family: Arial; font-size: 11px; color: #666666"></td>
                                                <td class="preheader-gap" width="20"></td>
                                                <td class="preheader-link" align="right" width="200" style="padding: 15px 0px; font-family: Arial; font-size: 11px; color: #666666">
                                                    You don't view images? <a style="color: #666666;" href="[linkvsonline]">Click here</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <div class="column">


                                <!-- default element text -->
                                <div class="lyrow">
                                    <a href="#close" class="remove label label-danger"><i class="glyphicon-remove glyphicon"></i></a>
                                    <span class="drag label label-default"><i class="glyphicon glyphicon-move"></i></span>
                                    <div class="view">


                                        <div class="row clearfix">
                                            <table width="640" class="main" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center" data-type='text-block' style="background-color: #FFFFFF;">
                                                <tbody>
                                                    <tr>
                                                        <td  class="block-text" align="left" style="padding:10px 50px 10px 50px;font-family:Arial;font-size:13px;color:#000000;line-height:22px">
                                                            <p style="margin:0px 0px 10px 0px;line-height:22px">
                                                                <center>
                                                                    <i class="fa fa-arrow-up fa-3x"></i> <br><br>
                                                                Modify me or drag the content of email in top or bottom <br><br>
                                                                <i class="fa fa-arrow-down fa-3x"></i>
                                                                </center>
                                                            </p>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>



                            </div>


                        </td>
                    </tr>
                </table>

            </div>
            <div id="download-layout">

            </div>
        </div>
        <!--/row-->
        <!-- Button trigger modal -->


        <!-- Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="min-width:120px">
                    <div class="modal-header">
                        <input id="httphref" type="text" name="href" value="http://" class="form-control" />
                    </div>
                    <div class="modal-body" align="center">
                        <div class="btn-group  previewActions">
                            <a class="btn btn-default btn-sm active" href="#">iphone</a>
                            <a class="btn btn-default btn-sm " href="#">smalltablet</a>
                            <a class="btn btn-default btn-sm " href="#">ipad</a>
                        </div>
                        <iframe id="previewFrame"  class="iphone"></iframe>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <textarea id="imageid" class="hide"></textarea>
        <textarea id="download" class="hide"></textarea>
        <textarea id="selector" class="hide"></textarea>
        <textarea  id="path" class="hide"></textarea>



        <div class="modal fade" id="previewimg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="min-width:120px">
                    <div class="modal-header">
                        Imagegallery
                    </div>
                    <div class="modal-body" align="center">
                        <?include("media-popup.php");?>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>




    </body>

</html>
