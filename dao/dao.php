<?php
class DAO
{
    public $conn = null;

    function __construct() {
        try {
            $this->conn = new PDO("mysql:host=192.168.0.108;dbname=gallery", 'gallery', 'gallery');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection could not be established: $e->getMessage()";
        }
    }

    function __destruct() {
        $this->conn = null;
    }

    public function getTopAlbums() {
        $query = <<<QUERY
SELECT a.name albumName, a.url albumUrl, a.`comment` albumComment, a.parent,
    i.filename, i.name imageName, i.url imageUrl, i.`comment` imageComment, i.created
FROM album a
JOIN image i ON i.album = a.url
GROUP BY i.album
ORDER BY a.parent, a.url, i.created DESC;
QUERY;

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            $hierarchy = [];
            foreach ($rows as $row) {
                if ($row["parent"] == null) {
                    $hierarchy[] = $row;
                } else {
                    for ($index = 0; $index < sizeof($hierarchy); $index++) {
                        $top = $hierarchy[$index];
                        if ($top["albumUrl"] == $row["parent"]) {
                            if (!array_key_exists("children", $top)) {
                                $top["children"] = [];
                            }

                            $top["children"][] = $row;
                            $hierarchy[$index] = $top;
                            break;
                        }
                    }
                }
            }

            return json_encode($hierarchy);
        } catch(PDOException $e) {
            echo "Error getting image: $e->getMessage()";
        }
    }

    public function getLatestImages() {
        try {
            $stmt = $this->conn->prepare('');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();
            return json_encode($stmt->fetchAll());
        } catch(PDOException $e) {
            echo "Error getting image: $e->getMessage()";
        }
    }

    public function getImage($filename) {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM image WHERE filename = :filename');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array('filename' => $filename));
            return json_encode($stmt->fetch());
        } catch(PDOException $e) {
            echo "Error getting image: $e->getMessage()";
        }
    }
}