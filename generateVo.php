<?php
if (!function_exists('camelcase2underscore')) {
    function camelcase2underscore($name)
    {
        return strtolower(preg_replace('/(?<!^)([A-Z0-9]){1}/', '_$1', $name));
    }
}

if (!function_exists('underscore2camelcase')) {
    function underscore2camelcase($name)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
    }
}
$tableName = $argv[1];

foreach ($argv as $key => $tableName) {
    if ($key === 0) {
        continue;
    }

    generate_vo($tableName);
}

function generate_vo($tableName)
{

$voClassName = ucfirst(underscore2camelcase($tableName)."Vo");
$db = new PDO('mysql:dbname=panther;host=127.0.0.1', 'panther', 'panther');
$stmt = $db->query("desc {$tableName}");

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$fct = '';
foreach ($result as $column) {
    if ($column['Key'] === 'PRI') {
        $primaryKey[]= "'{$column['Field']}'";
    }

    if ($column['Extra'] === 'auto_increment') {
        $incrementField = "{$column['Field']}";
    }

    if (isset($column['Default'])) {
        $properties[$column['Field']] = "private $" . $column['Field'] ." = '{$column['Default']}';";
    } else {
        $properties[$column['Field']] = "private $" . $column['Field'] . ";";
    }

    if ($column['Field'] === 'modify_on') {
        $properties[$column['Field']] = "private $" . $column['Field'] . " = '';";
    }

    $set_fct_name = underscore2camelcase('set_' . $column['Field']);
    $get_fct_name = underscore2camelcase('get_' . $column['Field']);

    $fct .= <<<FCT

    public function {$set_fct_name}(\${$column['Field']})
    {
        if (\${$column['Field']} !== null) {
            \$this->{$column['Field']} = \${$column['Field']};
        }
    }

    public function {$get_fct_name}()
    {
        return \$this->{$column['Field']};
    }

FCT;
}

$file = <<<EOT
<?php
class $voClassName extends \BaseVo
{

EOT;

foreach ($properties as $property) {
    $file .= <<<PROPERTY
    $property

PROPERTY;
}

$primaryKey = implode(', ', $primaryKey);
$file .= <<<PRIMARYKEY

    private \$primary_key = [{$primaryKey}];
PRIMARYKEY;

$file .= <<<INCREMENTFIELD

    private \$increment_field = '{$incrementField}';

INCREMENTFIELD;

$file .= $fct;

$file .= <<<GETPRIMARYKEY

    public function getPrimaryKey()
    {
        return \$this->primary_key;
    }

GETPRIMARYKEY;

$file .= <<<GETINCREMENTFIELD

    public function getIncrementField()
    {
        return \$this->increment_field;
    }
GETINCREMENTFIELD;

$file .= "\n}\n";

file_put_contents("application/libraries/VoPSR4/{$voClassName}.php", $file);
}
