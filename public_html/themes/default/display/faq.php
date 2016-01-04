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
                    <div class="">
                        <h2 class="text-left"><?=_('Need Help?')?></h2>
                        <p class="text-left note-alert">
                            <?=_('Look into our FAQ (frequently Asked Question) or knowledge base section first before contacting us')?>
                            <br/>
                            <?=sprintf(
                            _('If you still can\'t find the answer, please refer to the %s Contact Us %s on page and get in touch! Thank you.'), $contact_href, $contact_href_end
                            )?>
                        </p>
                    </div>
                    <div class="container-fluid">
                        <div id="iframe"></div>
                        <iframe onload="freshdeskFaq()" src="http://contact.<?= $server_name ?>/support/home" style="width:100%;height:1400px" scrolling="auto" frameborder="0"></iframe>

                    </div>
                </div>
            </div>
        </div>
</div>
<script>
    function freshdeskFaq() {


        $("iframe").contents().find(".heading").html("123");

        // var $iFrameContents = $('iframe').contents();
        // $entryContent   = $iFrameContents.find('#solutions-index-home');
        // $iFrameContents.find('html').replaceWith($entryContent);
    }
</script>