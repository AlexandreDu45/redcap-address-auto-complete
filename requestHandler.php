<?php
/** @var \STPH\addressAutoComplete\addressAutoComplete $module */

if ($_REQUEST['action'] == 'mapResults') {


    if(empty($_POST["source"]) || empty($_POST["results"])) {
        header("HTTP/1.1 400 Bad Request");
        die("results or source missing!");
    }
    $source = htmlentities($_POST["source"], ENT_QUOTES);
    $results = json_decode($_POST["results"]);

    if (!is_array($results)) {
        $results = [$results];
    }

    $module->mapResults($source, $results);
}

else if($_REQUEST['action'] == 'getConfigDescription') {

    if(empty($_POST["pid"]) || empty($_POST["source"])) {
        header("HTTP/1.1 400 Bad Request");
        die("pid or source missing!");
    }    

    $pid = htmlentities($_POST["pid"], ENT_QUOTES);
    $source = htmlentities($_POST["source"], ENT_QUOTES);
    
    $module->getConfigDescription($pid,$source);
}


else if ($_REQUEST['action'] == 'nocSearch') {

    if (empty($_POST["term"])) {
        header("HTTP/1.1 400 Bad Request");
        die("term missing");
    }

    
    if (empty($_POST["lang"])) {
        $lang = 'fr';
    } else {
        $lang = $_POST["lang"];
    }

    if ($lang === 'en-US') {
        $lang = 'en';
    }

    $term = urlencode($_POST["term"]);


    $apiKey = $module->getProjectSetting("api-key");

    $url =
        "https://services.api.esdc-edsc.canada.ca/prd/stream1/lmi/noc/v1/v1.0/jobsearch" .
        "?LanguageName.LanguageName=" . $lang .
        "&OccupationCategoryText=" . $term;
    

    $opts = [
        'http' => [
            'method' => 'GET',
            'header' =>
                "Ocp-Apim-Subscription-Key: " . $apiKey . "\r\n"
        ]
    ];

    $context = stream_context_create($opts);

    $response = file_get_contents(
        $url,
        false,
        $context
    );

    $data = json_decode($response);

    $flattened = [];

    foreach ($data as $occupation) {

        $code = $occupation->occupationCategoryCode->occupationCategoryCode;

        $occupationText = '';

        foreach ($occupation->occupationCategoryText as $text) {
            if ($text->lang === $lang) {
                $occupationText = $text->value;
                break;
            }
        }

        foreach ($occupation->occupationCategoryTitleList as $titleList) {

            foreach ($titleList->occupationCategoryTitle as $title) {

                if ($title->occupationCategoryTitleText->lang !== $lang) {
                    continue;
                }

                $flattened[] = (object) [
                    'occupationCategoryCode' => $code,
                    'occupationCategoryText' => $occupationText,
                    'occupationTitle' => $title->occupationCategoryTitleText->value
                ];
            }
        }
    }

    header('Content-Type: application/json');

    echo json_encode($flattened);
    exit;
}

else {
    header("HTTP/1.1 400 Bad Request");
    header('Content-Type: application/json; charset=UTF-8');    
    die("The action does not exist.");
}