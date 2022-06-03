<?php


class Film
{
    private $db;

    function __construct()
    {
        $this->db= mysqli_connect('localhost', '202410101074', 'secret', 'uas202410101074');
        // $this->db = mysqli_connect('localhost', 'root', '', 'sakila');
    }

    public function read()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $query = "SELECT * FROM film ORDER BY title LIMIT {$page}, 12";
        $sql = $this->db->query($query);
        $data = [];

        while ($row = $sql->fetch_assoc()) {
            array_push($data, $row);
        }

        header("Content-Type: application/json");
        echo json_encode($data);
    }

    public function create($data)
    {
        foreach ($data as $key => $value) {
            $value = is_array($value) ? trim(implode(',', $value)) : trim($value);
            $data[$key] = (strlen($value) > 0 ? $value : NULL);
        }

            $query = "INSERT INTO film VALUES (Null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $sql = $this->db->prepare($query);
            $sql->bind_param(
                'ssiiiididss',
                $data['title'],
                $data['description'],
                $data['release_year'],
                $data['language_id'],
                $data['original_language_id'], 
                $data['rental_duration'],
                $data['rental_rate'],
                $data['length'],
                $data['replacement_cost'], 
                $data['rating'],
                $data['special_features'],
            );
        
        try {
            $sql->execute();
        } catch (PDOException $e) {
            $sql->close();
            http_response_code(500);
            die($e->getMessage());
        }
        $sql->close();

    }
}

$film = new Film();

switch (isset($_GET['action'])?$_GET['action']:'read') {
    case 'create':
        $film->create($_POST);
        break;

    default:
        $film->read();
        break;
}