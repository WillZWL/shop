<div id="content">
<style type="text/css">

dt.faq {color:#990000}

dt.faq:hover {cursor:hand}

dd.faq {color:#cc9900}

dd.faq:hover {cursor:text}

dt.faqspace {color:#ffffff}

dt.faqspace:hover {cursor:default}

</style>

<script type="text/javascript">

var oldID;

function expandorCollapse(ID)

{

document.getElementById(ID).style.color = "#ff813b";

num = ID.substr(2);

ddID = 'dd' + num;

if (document.getElementById(ddID).style.display == "block")

document.getElementById(ddID).style.display = "none";

else

{

if (oldID)

{

document.getElementById(oldID).style.display = "none";

}

document.getElementById(ddID).style.display = "block";

}

oldID = ddID;

}

</script>
<b><?=$lang_text['faq_heading']?></b>
<br /><br />
<b><?=$lang_text['faq_subtitle_1']?></b><br /><br />

<dt class="faq" id="dt1" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt1')"><?=$lang_text['faq_question_1_1']?></dt>

<dd class="faq" id="dd1" style="display:none"><?=$lang_text['faq_answer_1_1']?><br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt2" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt2')"><?=$lang_text['faq_question_1_2']?></dt>

<dd class="faq" id="dd2" style="display:none"><?=$lang_text['faq_answer_1_2']?>
<br />
</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt3" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt3')"><?=$lang_text['faq_question_1_3']?></dt>

<dd class="faq" id="dd3" style="display:none"><?=$lang_text['faq_answer_1_3']?>
<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt4" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt4')"><?=$lang_text['faq_question_1_4']?></dt>

<dd class="faq" id="dd4" style="display:none"><?=$lang_text['faq_answer_1_4']?>
<br />
</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt5" title="<?=$lang_text['click_to_view_answer']?> onclick="expandorCollapse('dt5')"><?=$lang_text['faq_question_1_5']?></dt>

<dd class="faq" id="dd5" style="display:none"><?=$lang_text['faq_answer_1_5']?>

<br />
</dd>
<!-- END CANCELLATION & RETURNS -->
<br /><br />
<b><?=$lang_text['faq_subtitle_2']?></b><br /><br />

<dt class="faq" id="dt6" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt6')"><?=$lang_text['faq_question_2_1']?>
</dt>

<dd class="faq" id="dd6" style="display:none"><?=$lang_text['faq_answer_2_1']?>
<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt7" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt7')"><?=$lang_text['faq_question_2_2']?>
</dt>

<dd class="faq" id="dd7" style="display:none"><?=$lang_text['faq_answer_2_2']?>

<br />
</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt8" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt8')"><?=$lang_text['faq_question_2_3']?>
</dt>

<dd class="faq" id="dd8" style="display:none"><?=$lang_text['faq_answer_2_3']?>

<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt9" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt9')"><?=$lang_text['faq_question_2_4']?>
</dt>

<dd class="faq" id="dd9" style="display:none"><?=$lang_text['faq_answer_2_4']?>

<br />
</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt10" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt10')"><?=$lang_text['faq_question_2_5']?>

</dt>

<dd class="faq" id="dd10" style="display:none"><?=$lang_text['faq_answer_2_5']?>
<br />
</dd>

<!-- END DELIVERY -->


<br /><br />
<b><?=$lang_text['faq_subtitle_3']?></b><br /><br />

<dt class="faq" id="dt11" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt11')"><?=$lang_text['faq_question_3_1']?>
</dt>

<dd class="faq" id="dd11" style="display:none"><?=$lang_text['faq_answer_3_1']?>
<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt12" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt12')"><?=$lang_text['faq_question_3_2']?>

</dt>

<dd class="faq" id="dd12" style="display:none"><?=$lang_text['faq_answer_3_2']?>
<br />
</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt13" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt13')"><?=$lang_text['faq_question_3_3']?>
</dt>

<dd class="faq" id="dd13" style="display:none"><?=$lang_text['faq_answer_3_3']?>

<br />

</dd>

<!-- END ORDER STATUS -->

<br /><br />
<b><?=$lang_text['faq_subtitle_4']?></b><br /><br />

<dt class="faq" id="dt14" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt14')"><?=$lang_text['faq_question_4_1']?>

</dt>

<dd class="faq" id="dd14" style="display:none"><?=$lang_text['faq_answer_4_1']?>

<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt15" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt15')"><?=$lang_text['faq_question_4_2']?>

</dt>

<dd class="faq" id="dd15" style="display:none"><?=$lang_text['faq_answer_4_2']?>


<br />
</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt16" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt16')"><?=$lang_text['faq_question_4_3']?>

</dt>

<dd class="faq" id="dd16" style="display:none"><?=$lang_text['faq_answer_4_3']?>
<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt17" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt17')"><?=$lang_text['faq_question_4_4']?>
</dt>

<dd class="faq" id="dd17" style="display:none"><?=$lang_text['faq_answer_4_4']?>


<br />
</dd>

<!-- END PAYMENTS -->

<br /><br />
<b><?=$lang_text['faq_subtitle_5']?></b><br /><br />

<dt class="faq" id="dt18" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt18')"><?=$lang_text['faq_question_5_1']?>
</dt>

<dd class="faq" id="dd18" style="display:none"><?=$lang_text['faq_answer_5_1']?>
<br />

</dd>

<!-- END WARRANTY -->

<br /><br />
<b><?=$lang_text['faq_subtitle_6']?></b><br /><br />

<dt class="faq" id="dt19" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt19')"><?=$lang_text['faq_question_6_1']?>

<dd class="faq" id="dd19" style="display:none"><?=$lang_text['faq_answer_6_1']?>

<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt20" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt20')"><?=$lang_text['faq_question_6_2']?>
</dt>

<dd class="faq" id="dd20" style="display:none"><?=$lang_text['faq_answer_6_2']?>

<br />

</dd>

<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>

<dt class="faq" id="dt21" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt21')"><?=$lang_text['faq_question_6_3']?>
</dt>

<dd class="faq" id="dd21" style="display:none"><?=$lang_text['faq_answer_6_3']?>

<br />

</dd>

<!-- END PLACING AN ORDER -->

<br /><br />
<b><?=$lang_text['faq_subtitle_7']?></b><br /><br />
<dt class="faq" id="dt22" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt22')"><?=$lang_text['faq_question_7_1']?>
</dt>
<dd class="faq" id="dd22" style="display:none"><?=$lang_text['faq_answer_7_1']?>
<br />
</dd>
<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>
<dt class="faq" id="dt23" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt23')"><?=$lang_text['faq_question_7_2']?>
</dt>
<dd class="faq" id="dd23" style="display:none"><?=$lang_text['faq_answer_7_2']?>
<br />
</dd>
<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>
<dt class="faq" id="dt24" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt24')"><?=$lang_text['faq_question_7_3']?></dt>
<dd class="faq" id="dd24" style="display:none"><?=$lang_text['faq_answer_7_3']?>
<br />
</dd>
<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>
<dt class="faq" id="dt25" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt25')"><?=$lang_text['faq_question_7_4']?>
</dt>
<dd class="faq" id="dd25" style="display:none"><?=$lang_text['faq_answer_7_4']?>
<br />
</dd>
<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>
<dt class="faq" id="dt26" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt26')"><?=$lang_text['faq_question_7_5']?>
</dt>
<dd class="faq" id="dd26" style="display:none"><?=$lang_text['faq_answer_7_5']?>
<br />
</dd>

<br /><br />
<b><?=$lang_text['faq_subtitle_8']?></b><br /><br />
<dt class="faq" id="dt27" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt27')"><?=$lang_text['faq_question_8_1']?>
</dt>
<dd class="faq" id="dd27" style="display:none"><?=$lang_text['faq_answer_8_1_1']?><a href='<?=$base_url?>contact#enquiry_box_anchor'><?=$lang_text['faq_answer_8_1_2']?></a><?=$lang_text['faq_answer_8_1_3']?>
<br />
</dd>
<dt class="faqspace">This is a blank line if required. Change colour of class to suit background colur of page.</dt>
<dt class="faq" id="dt28" title="<?=$lang_text['click_to_view_answer']?>" onclick="expandorCollapse('dt28')"><?=$lang_text['faq_question_8_2']?>
</dt>
<dd class="faq" id="dd28" style="display:none"><?=$lang_text['faq_answer_8_2']?>
<br />
</dd>
</div>