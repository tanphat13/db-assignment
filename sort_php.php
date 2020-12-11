<!DOCTYPE html>
<head>

</head>

<body>
    
    <?php
        $var = "Web programming";
        $newstring = "";
        $size = strlen($var);
        
        for($i=0; $i < $size; $i++) {
            $newstring[$i] = $var[$size - $i - 1];
        }
        echo $newstring;
    ?> 
    




    
    
</body>
</html>