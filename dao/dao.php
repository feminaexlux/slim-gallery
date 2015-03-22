<?php
class DAO
{
    public $conn = null;

    function __construct() {
        try {
            $this->conn = new PDO("mysql:host=192.168.0.108;port=3306;dbname=gallery", 'gallery', 'gallery');
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection could not be established: {$e->getMessage()}";
        }
    }

    function __destruct() {
        $this->conn = null;
    }

    public function getTopAlbums() {
        $query = <<<QUERY
SELECT a.name, a.url, a.parent, i.filename, i.created
FROM album a
JOIN image i ON a.url = i.album
LEFT JOIN image i2 ON i2.album = i.album AND i2.created > i.created
WHERE i2.filename IS NULL
ORDER BY a.parent, a.url
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
                        if ($top["url"] == $row["parent"]) {
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

    public function getAlbum($url) {
        try {
            $stmt = $this->conn->prepare("SELECT name, comment, url, parent FROM album WHERE url = :url");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array('url' => $url));
            $album = $stmt->fetch();

            $stmt = $this->conn->prepare("SELECT name FROM album WHERE url = :url");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array('url' => $album["parent"]));
            $album["parentName"] = $stmt->fetch()["name"];

            $stmt = $this->conn->prepare("SELECT filename, name, url FROM image WHERE album = :url ORDER BY created DESC, name");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array('url' => $url));
            $album["images"] = $stmt->fetchAll();

            $query = <<<QUERY
SELECT a.name, a.url, a.parent, i.filename, i.created
FROM album a
JOIN image i ON a.url = i.album
LEFT JOIN image i2 ON i2.album = i.album AND i2.created > i.created
WHERE i2.filename IS NULL
AND a.parent = :url
ORDER BY a.parent, a.url
QUERY;

            $stmt = $this->conn->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array('url' => $url));
            $album["children"] = $stmt->fetchAll();

            return json_encode($album);
        } catch(PDOException $e) {
            echo "Error getting image: {$e->getMessage()}";
        }
    }

    public function getImage($url) {
        $query = <<<QUERY
SELECT i.*, a.name albumName, a2.url parentUrl, a2.name parentName
FROM image i
JOIN album a ON i.album = a.url
LEFT JOIN album a2 ON a2.url = a.parent
WHERE i.url = :url
QUERY;


        try {
            $stmt = $this->conn->prepare($query);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute(array('url' => $url));
            return json_encode($stmt->fetch());
        } catch(PDOException $e) {
            echo "Error getting image: {$e->getMessage()}";
        }
    }
}