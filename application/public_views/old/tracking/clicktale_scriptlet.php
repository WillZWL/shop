<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Clicktale_scriptlet
{
    private $active;

    public function __construct($active = 1)
    {
        $this->active = $active;
    }

    public function get_header_script()
    {
        if ($this->active) {
            ?>
            <!-- ClickTale Top part -->
            <script type="text/javascript">
                <!--
                var WRInitTime = (new Date()).getTime();
                //-->
            </script>
            <!-- ClickTale end of Top part -->
        <?php
        }
    }

    public function get_bottom_script()
    {
        if ($this->active) {
            ?>
            <script type="text/javascript">
                <!--
                // Integration function. Add custom code here
                function ClickTale_WhenAvailableCallback() {
                    ClickTale(15500, 1, "www02");
                }

                function ClickTale_GetRecorderFileUrl() {
                    return "https://clicktale.pantherssl.com/WRb.js";
                }

                window.CallClickTaleWhenAvailable = function () {
                    // on first call, remove the function from the window
                    window.CallClickTaleWhenAvailable = undefined;

                    var loopCount = 0,
                    // set to maximum number of tries running clicktale on page
                        loopMax = 200;

                    function CheckForClickTale() {
                        var ClickTaleCookieDomain = "valuebasket.com";

                        if (typeof ClickTale == "function") {
                            if (typeof ClickTale_WhenAvailableCallback != "function") {
                                return;
                            }
                            ClickTale_WhenAvailableCallback();
                        }
                        else if (loopCount < loopMax) {
                            loopCount++;
                            setTimeout(arguments.callee, 100);
                        }
                    }

                    // the first call
                    CheckForClickTale();
                };

                document.write('<div id="ClickTaleDiv" style="display: none;"></div>');
                document.write(unescape('%3Cscript src="' + ClickTale_GetRecorderFileUrl() + '" type="text/javascript"%3E%3C/script%3E'));
                document.write(unescape('%3Cscript type="text/javascript"%3E'));
                document.write('var ClickTaleSSL=1;');
                document.write('var ClickTaleCookieDomain = "valuebasket.com";');
                document.write('window.CallClickTaleWhenAvailable();');
                document.write(unescape('%3C/script%3E'));
                //-->
            </script>
        <?php
        }
    }
}
