<html>
    <head>
        <title>Mapping for feeds</title>
        <script src="/js/jquery.js"></script>
        <script>
            function submit(id)
            {
                var value = $("#"+id +" option:selected").val();
                url = '/<?= $this->tool_path ?>/set_feed_platform_json/' + value;
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
        <b>Mapping for feeds</b>
        <hr>
        <table>
        <?php
            $c = 1;
            foreach ($current_mapping as $obj) :
        ?>
            <tr>
                <td><?= $c ?></td>
                <td><?= $obj->getAffiliateId() ?></td>
                <td>
                    <select onchange="submit('current_mapping_<?= $obj->getAffiliateId() ?>')" id="current_mapping_<?= $obj->getAffiliateId() ?>">
                        <option value="<?=$obj->getAffiliateId()?>">---</option>
                        <?php foreach ($platform_list as $platform) :
                            $selected = ($obj->getPlatformId() == $platform) ? "selected='selected'" : ""; ?>
                            <option value="<?=$obj->getAffiliateId()?>/<?= $platform ?>" <?= $selected?>><?= $platform ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php
            $c++;
            endforeach;
        ?>
        </table>
    </body>
</html>