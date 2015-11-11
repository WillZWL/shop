<?php include_once "email_tpl_header.php" ?>

    <h1><?= $header; ?></h1>

    <ul>

        <?php  foreach ($templates as $template) { ?>

            <li>
                <p><?php  echo '<a href="/' . SELF . '/test/test/update/' . $email->get_id() . '">From: ' . $email->get_emailfrom() . ' To: ' . $email->get_emailgoesto(); ?></a></p>
            </li>

        <?php  } ?>

    </ul>

<?php include_once "email_tpl_footer.php" ?>