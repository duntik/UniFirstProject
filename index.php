<?php

ver_dump($_SERVER);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php
$start = 20;
$end = 90;
$sum = 0;
for($i = $start; $i <= $end; $i++) {
    if(fmod($i, 5) == 0) {
        $sum += $i;
    }
}
echo $sum;
?>
</body>
</html>