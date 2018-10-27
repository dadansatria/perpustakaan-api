	<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

// Get container
$container = $app->getContainer();

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/hello', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get("/buku/", function (Request $request, Response $response){
    $sql = "SELECT * FROM buku";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->get("/buku/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "SELECT * FROM buku WHERE id=:id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->post("/buku/", function (Request $request, Response $response){

    $new_buku = $request->getParsedBody();

    $sql = "INSERT INTO buku (nama, tahun_terbit) VALUE (:nama, :tahun_terbit)";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":nama" => $new_buku["nama"],
        ":tahun_terbit" => $new_buku["tahun_terbit"],
    ];

    if($stmt->execute($data))
       return $response->withJson(["status" => "success", "data" => "1"], 200);
    
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

$app->post("/buku/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $new_book = $request->getParsedBody();
    $sql = "UPDATE buku SET nama=:nama, tahun_terbit=:tahun_terbit WHERE id=:id";
    $stmt = $this->db->prepare($sql);
	    
    $data = [
    	":id" => $id,
        ":nama" => $new_book["nama"],
        ":tahun_terbit" => $new_book["tahun_terbit"],	
    ];

    if($stmt->execute($data)){
       return $response->withJson(["status" => "success", "data" => "1"], 200);
    }
    
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});