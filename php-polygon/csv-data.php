<?php
require_once __DIR__ . '/vendor/autoload.php';
WpOrg\Requests\Autoload::register();

$apiKey = "zGqxFYkKxo0q5TgAM9DVRxHZv3sFd2Ra";
// https://api.polygon.io/v2/aggs/grouped/locale/us/market/stocks/2023-01-09?adjusted=true&apiKey=zGqxFYkKxo0q5TgAM9DVRxHZv3sFd2Ra
$base_url = "https://api.polygon.io/v2/aggs/grouped/locale/us/market/stocks/";

$start_date = "2021-11-10";
$start_date_url = $base_url . $start_date . "?adjusted=true&" . "apikey=" . $apiKey;
$end_date = "2023-10-25";
$end_date_url = $base_url . $end_date . "?adjusted=true&" . "apikey=" . $apiKey;

// Accessing the database to see if the required data exists
$user = "root";
$password = "1234";
$database = "stocks";
$table = "price_changes";
$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];


try {
    $pdo = new \PDO("mysql:host=localhost;dbname=$database", $user, $password, $options);
    // echo "<h2>Price Changes</h2>";

    $query = "SELECT count(*) FROM $table WHERE (start_date = '$start_date' AND end_date = '$end_date');";
    // echo "<p>query: " . $query . "</p>";
    $stmt = $pdo->query($query);
    $row_count = $stmt->fetchColumn();
    // echo "<p>" . "rowCount: " . $row_count . "</p>";
    if ($row_count == 0) {
        $start_date_response = WpOrg\Requests\Requests::get($start_date_url);
        $end_date_reponse = WpOrg\Requests\Requests::get($end_date_url);
        $start_data = json_decode($start_date_response->body);
        $end_data = json_decode($end_date_reponse->body);

        // Start and End Tickers Arrays
        $start_results = $start_data->results;
        $start_tickers_array = array();
        foreach ($start_results as $result) {
            $start_tickers_array[] = $result->T;   //inserts item into array, wierd syntax
        }

        $end_results = $end_data->results;
        $end_tickers_array = array();
        foreach ($end_results as $result) {
            $end_tickers_array[] = $result->T;
        }

        $common_tickers_array = array_intersect($start_tickers_array, $end_tickers_array);


        // Start and End Maps; ticker to result
        $start_ticker_to_result_map = array();
        foreach ($start_results as $result) {
            $start_ticker_to_result_map[$result->T] = $result;
        }

        $end_ticker_to_result_map = array();
        foreach ($end_results as $result) {
            $end_ticker_to_result_map[$result->T] = $result;
        }

        foreach ($common_tickers_array as $ticker) {
            // casting to int because value can be undefined. (int) cast changes undefined to 0
            $start_open = (int)$start_ticker_to_result_map[$ticker]->o;
            $start_close = (int)$start_ticker_to_result_map[$ticker]->c;
            $start_high = (int)$start_ticker_to_result_map[$ticker]->h;
            $start_low = (int)$start_ticker_to_result_map[$ticker]->l;
            $start_volume = (int)$start_ticker_to_result_map[$ticker]->v;
            $start_volume_weighted_price = (int)$start_ticker_to_result_map[$ticker]->vw;
            $end_open = (int)$end_ticker_to_result_map[$ticker]->o;
            $end_close = (int)$end_ticker_to_result_map[$ticker]->c;
            $end_high = (int)$end_ticker_to_result_map[$ticker]->h;
            $end_low = (int)$end_ticker_to_result_map[$ticker]->l;
            $end_volume = (int)$end_ticker_to_result_map[$ticker]->v;
            $end_volume_weighted_price = (int)$end_ticker_to_result_map[$ticker]->vw;

            $query = "INSERT INTO $table (ticker, start_date, end_date, start_open, start_close, start_high, start_low, start_volume, start_volume_weighted_price, end_open, end_close, end_high, end_low, end_volume, end_volume_weighted_price) VALUES ('$ticker', '$start_date', '$end_date', '$start_open', '$start_close', '$start_high', '$start_low', '$start_volume', '$start_volume_weighted_price', '$end_open', '$end_close', '$end_high', '$end_low', '$end_volume', '$end_volume_weighted_price');";
            // echo "<p>" . $query . "</p>";
            $stmt = $pdo->query($query);
        }
    }

    $query = "SELECT * FROM $table WHERE (start_date = '$start_date' AND end_date = '$end_date');";
    // echo "<p>query: " . $query . "</p>";
    $query_response = $pdo->query($query);

    // Create array
    $list = array();

    // Append results to array
    array_push($list, array("T", "d1", "d2", "o1", "c1", "h1", "l1", "v1", "vw1", "o2", "c2", "h2", "l2", "v2", "vw2"));
    while ($row = $query_response->fetch(PDO::FETCH_ASSOC)) {
        array_push($list, array_values($row));
    }

    $filename = "stocks.csv";
    // Output array into CSV file
    $fp = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    foreach ($list as $ferow) {
        fputcsv($fp, $ferow);
    }
    fclose($fp);

    // $filename = "stocks.csv";
    // // Output array into CSV file
    // $fp = fopen($filename, 'w');
    // foreach ($list as $ferow) {
    //     fputcsv($fp, $ferow);
    // }
    // fclose($fp);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
}
