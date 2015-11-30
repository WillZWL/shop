<html>
    <head>
        <title><?= $sku ?> for <?= $platform_id ?></title>
        <script src="/js/jquery.js"></script>
        <script>
            function submit(radio)
            {
                url = '/<?= $this->tool_path ?>/set_sku_feed_status_json/' + radio.name + '/<?= $sku ?>/<?= $platform_id ?>/' + radio.value;
                $.ajax
                (
                    {
                        type: "GET",
                        url: url,
                        dataType: "json"
                    }
                );
            }
        </script>
    </head>
    <body>
        <b><?= $sku ?> for <?= $platform_id ?></b>
        <hr>
        <table>
        <?php
            $c = 1;
            $prev_checked = -1;
            foreach ($list as $item) :
                $box[0] = "";
                $box[1] = "";
                $box[2] = "";
                $box[$item['status']] = "checked";

                $checked = $item['chk'];
                if ($prev_checked == -1) :
                    $prev_checked = $checked;
                endif;

                if ($checked != $prev_checked) :
                    $html .= "</table><hr><table>";
                endif;

                $prev_checked = $checked;

                $html .= <<<asdf
                    <tr>

                        <td>{$c}</td>
                        <td>{$item['affiliate_id']}</td>
                        <td><input type="radio" name="{$item['affiliate_id']}" value="0" $box[0] onClick="submit(this);">Auto</td>
                        <td><input type="radio" name="{$item['affiliate_id']}" value="1" $box[1] onClick="submit(this);">Exclude</td>
                        <td><input type="radio" name="{$item['affiliate_id']}" value="2" $box[2] onClick="submit(this);">Include</td>
                    </tr>
asdf;
            $c++;
            endforeach;
        echo $html;
?>
        </table>
    </body>
</html>