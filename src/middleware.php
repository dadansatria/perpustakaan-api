<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// Application middleware

$app->add(function ($request, $response, $next) {
    
    $key = $request->getQueryParam("key");

    if(!isset($key)){
        return $response->withJson(["status" => "API Key required"], 401);
    }
    
    $sql = "SELECT * FROM user_api WHERE api_key=:api_key";
    $query = $this->db->prepare($sql);
    $query->execute([":api_key" => $key]);
    
    if($query->rowCount() > 0){
        $result = $query->fetch();
        if($key == $result["api_key"]){
        
            // update hit
            $sql = "UPDATE user_api SET hit=hit+1 WHERE api_key=:api_key";
            $query = $this->db->prepare($sql);
            $query->execute([":api_key" => $key]);
            
            return $response = $next($request, $response);
        }
    }

    return $response->withJson(["status" => "Unauthorized"], 401);

});