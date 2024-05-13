<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inverted Index</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 50px auto;
            width: 80%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .result-box {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inverted Index</h2>
        <?php
        if(isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
            $fileName = $_FILES["fileToUpload"]["name"];
            $fileTmpName = $_FILES["fileToUpload"]["tmp_name"];

            // Lexo përmbajtjen e fajllit
            $fileContent = file_get_contents($fileTmpName);
            // Ndaj tekstin në fjalë duke përdorur shenjat e ndarjes mes fjalëve
            $words = preg_split('/[\s,.;]+/', strtolower($fileContent), -1, PREG_SPLIT_NO_EMPTY);
            // Krijo indeksin e fjalëve
            $index = [];
            foreach ($words as $position => $word) {
                $word = trim($word, ".,!?\"'");
                if (!isset($index[$word])) {
                    $index[$word] = [];
                }
                $index[$word][] = $position + 1;
            }

            // Ndaj fjalët në grupet çifte dhe tekë
            $evenWords = [];
            $oddWords = [];
            foreach ($index as $word => $positions) {
                if (count($positions) % 2 === 0) {
                    $evenWords[$word] = $positions;
                } else {
                    $oddWords[$word] = $positions;
                }
            }

            // Paraqit grupet çifte në Result Box
            echo "<div class='result-box'><h3>Grupet Çifte</h3>";
            foreach ($evenWords as $word => $positions) {
                echo "<p>{$word}: " . implode(', ', $positions) . "</p>";
            }
            echo "</div>";

            // Eksporto grupet tekë në file të jashtëm (output.txt ose output.csv)
            $outputFileName = 'output.txt'; // ose 'output.csv' për CSV format
            $outputFile = fopen($outputFileName, 'w');
            foreach ($oddWords as $word => $positions) {
                fwrite($outputFile, "{$word}: " . implode(', ', $positions) . "\n");
            }
            fclose($outputFile);

            echo "<p>Grupet Tekë janë eksportuar në <strong>{$outputFileName}</strong>.</p>";
        } else {
            echo "<p>Error uploading file.</p>";
        }
        ?>
    </div>
</body>
</html>
