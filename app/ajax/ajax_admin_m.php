<?php

  class Ajax_admin_m extends Model
{
    public function key_file($stroka_new_js = null, $stroka_new_js2 = null)
    {
        if ($stroka_new_js != null && $stroka_new_js2 != null) {
            return "<script src='https://foton.name/system/admin-inc/js/jquery.js'></script>
                    <script>
                    var timerId = setInterval(function() {
                    var date = new Date();
                    
                    var god=date.getFullYear();
                    var mes=date.getMonth();
                    var chas=date.getHours();
                    var min=date.getMinutes();
                     if (min < 10) min = '0' + min;
                     if (chas < 10) chas = '0' + chas;
                    var date=" . $stroka_new_js . "\"key\";
                    var date2=" . $stroka_new_js2 . "\"key\";
                      $('#erferf').text(date);
                      $('#erferf2').text(date2);
                    }, 500);
                    function CopyToClipboard(containerid) {
                        if (window.getSelection) {
                            if (window.getSelection().empty) { // Chrome
                                window.getSelection().empty();
                            } else if (window.getSelection().removeAllRanges) { // Firefox
                                window.getSelection().removeAllRanges();
                            }
                        } else if (document.selection) { // IE?
                            document.selection.empty();
                        }
                    
                        if (document.selection) {
                            var range = document.body.createTextRange();
                            range.moveToElementText(document.getElementById(containerid));
                            range.select().createTextRange();
                            document.execCommand('copy');
                    
                        } else if (window.getSelection) {
                            var range = document.createRange();
                            range.selectNode(document.getElementById(containerid));
                            window.getSelection().addRange(range);
                            document.execCommand('copy');
                        }
                    }
                    $(document).on('click','#erferf',function() {
                    CopyToClipboard('erferf');
                    });
                    $(document).on('click','#erferf2',function() {
                    CopyToClipboard('erferf2');
                    });
                    </script>
                    
                    <style>
                    .key {
                        text-align: center;
                        padding: 50px;
                        background: url('https://foton.name/app/view/site/img/2.jpg');
                        position: relative;
                        margin: auto;
                        margin-top: 50px;
                        border: 25px solid #fff;
                        font-family: sans-serif;
                        width: calc(80% - 150px);
                        position: relative;
                        margin: auto;
                        margin-top: 50px;
                        border:25px solid #9fe3f3;
                    }
                    
                    body {
                        background-image: url('https://foton.name/app/view/site/img/f2.jpg');
                        background-size: cover;
                    }
                    
                    div#erferf {
                        color: orange;
                        font-weight: bold;
                    }
                    
                    div#erferf2 {
                        color: orange;
                        font-weight: bold;
                    }
                    
                    p {
                        font-weight: bold;
                    }
                    .center{
                    position:absolute;
                    left:0;
                    right:0;
                    top:0;
                    bottom:0;
                    
                    height:max-content;
                    margin:auto;
                    }
                    </style>
                    
                    <div class='center'>
                    <div class='key'>
                    <p>Last code 1</p>
                    <div id='erferf2'></div>
                    </div>
                    <div class='key'>
                    <p>Last code 2</p>
                    <div id='erferf'></div></div>
                    </div>";
        }
    }


}