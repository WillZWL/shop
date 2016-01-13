<style type="text/css">
    .mfp-iframe-scaler {
        padding-top: 74.5%;
    }
</style>
<?php $contact_href = '<a href="' . base_url() . 'display/view/contact" style="">' ?>
<?php  $contact_href_end = '</a>' ?>
<div class="contact">
    <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="center-block">
                    <div>
                        <h2 class="text-left"><?=_('NEED HELP?')?></h2>
                        <p class="text-left note-alert">
                            <?=_("You've got a Question? We've got the Answer! Check out our FAQs.")?>
                        </p>
                    </div>
                    <div class="container-fluid">
                        <iframe src="//contact.<?= $server_name ?>/support/home"  style="width:100%; height:950px;" scrolling="auto" frameborder="0" id="faq" name="faq" ></iframe>

                    </div>
                    <div>
                        <p class="text-left note-alert">
                            <?=sprintf(_("If you still can't find the answer, please refer to the %s Contact Us %s on page and get in touch! Thank you."), $contact_href, $contact_href_end)?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
function iframeFaq(width,height) {
var appSubIframeObj=document.getElementById("faq");
appSubIframeObj.style.height=height+"px";
</script>