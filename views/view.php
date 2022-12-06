<!DOCTYPE html>
<html>
    <head>
        <title>Můj adresář</title>
        <meta name="keywords" content="Nějaká klíčová slova" />
        <meta name="description" content="Adresář mých kontaktů" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src='./views/jquery-1.12.4.min.js' type='text/javascript'></script>
        <script src='./views/jquery-migrate-1.3.0.min.js' type='text/javascript'></script>
        <script src='./views/js.js' type='text/javascript'></script>
    </head>
    <body>
        <style>
            table{
                width:100%;
                border-collapse: collapse;
            }
            th,td{
                border:1px solid grey;
                padding:10px;
            }
            .send_changes{
                display:none;
            }
        </style>
        <h1>Můj adresář</h1>
        <table>
            <thead>
                <tr>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Telefonní číslo</th>
                    <th>Email</th>
                    <th>Poznámka</th>
                    <th>Smazat/Editovat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) { ?>
                    <tr class="item-<?= $item->a("id"); ?>">
                        <td class="editable name" data-id="<?= $item->a("id"); ?>" data-original="<?= $item->a("name"); ?>">
                            <?= $item->a("name"); ?>
                        </td>
                        <td class="editable surname" data-id="<?= $item->a("id"); ?>" data-original="<?= $item->a("surname"); ?>">
                            <?= $item->a("surname"); ?>
                        </td>
                        <td class="editable phone" data-id="<?= $item->a("id"); ?>" data-original="<?= $item->a("phone"); ?>">
                            <?= $item->a("phone"); ?>
                        </td>
                        <td class="editable email" data-id="<?= $item->a("id"); ?>" data-original="<?= $item->a("email"); ?>">
                            <a href="mailto:<?= $item->a("email"); ?>"><?= $item->a("email"); ?></a>
                        </td>
                        <td class="editable note" data-id="<?= $item->a("id"); ?>" data-original="<?= $item->a("note"); ?>">
                            <?= $item->a("note"); ?>
                        </td>
                        <td>
                            <a href="#" data-id="<?= $item->a("id"); ?>" class="delete">X</a> /
                            <a href="#" data-id="<?= $item->a("id"); ?>" class="edit">E</a> /
                            <a href="#" data-id="<?= $item->a("id"); ?>" class="cancle">Zrušit</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button class="add_new">Vložit další</button>
        <button class="send_changes">Odešli úpravy</button>
    </body>
</html>