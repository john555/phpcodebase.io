<?php

$router->usePrefix("/api");

$router->get("/Users", function($req, $res){
    $user = new \System\Identity\User();
    $users = $user->findAll();
    echo "<h1>Users</h1>";

    echo "<ol>";
        foreach($users as $user){
            echo "<li>".$user['first_name']." ". $user['last_name'] ." - (". $user['id'].")</li>";
        }
    echo "</ol>";
});

$router->get("/user", function($req, $res){
    // \Models\User::_create(array(
    //     "first_name" => "Jacob",
    //     "last_name" => "Taylor"
    // ));
    $password = "rawpassword";
    $user = new \System\Identity\User();
    $user->first_name = "Jacob";
    $user->last_name = "Taylor";
    $user->email = "jtaylor@example.com";
    $user->username = "jtaylor";

    $userManager = new \System\Identity\UserManager();

    var_dump($userManager->createUser($user, $password));

});

$router->dropPrefix();

$router->get("/compose", function(){
    $post = new \Models\Post();
    $post->message = "I find js quite <b>entriging</b>";
    $post->id = $post->guid();
    $post->user_id = "1648427515";
    $post->save();
});


$router->get("/signin", function($req, $res){
    $userManager = new \System\Identity\UserManager();
    $login = "jtaylor@example.com";
    $password = "rawpassword";
    if(filter_var($login, FILTER_VALIDATE_EMAIL)):
        var_dump($userManager->emailSignIn($login, $password));
    else:
        var_dump($userManager->usernameSignIn($login, $password));
    endif;
});


$router->get("/", function($req, $res){
    $userManager = new \System\Identity\UserManager();
    $user = $userManager->findById("1648427515");
    //echo time();
    
    $res->render('home', array('name'=> $user->first_name. " " . $user->last_name));
});


$router->get("/data", function($req, $res){
echo '<form action="?g=zAer5765de56dtd" method="POST" enctype="multipart/form-data"><div>
            <input type="file" name="upload">
            <input type="text" name="full_name" placeholder="First name, Last name">
        </div>
        <button>Submit</button>
    </form>'; 

    var_dump($res);
});

$router->post("/data", function($req, $res){
    $form_data = $req->getBody();

    echo "<h1>{$form_data['full_name']}</h1>";
    echo "<p>{$req->getQueryString('g')}</p>";
    var_dump($req->getFile("upload"));

});




$router->get("/auth/signin", function($req, $res){
    $returnUrl = ($req->getQueryString("return_url"));
    var_dump($returnUrl);
});

$router->get("/auth", function($req, $res){
    var_dump($req);
}, true);

$router->get("/:username", function($req, $res){
    echo "<h1>Hello {$req->params['username']}</h1>";
});

